<?php
/**
 * Микроразметка Schema.org, которой не даёт Yoast.
 *
 * Yoast закрывает title/description/OG/canonical/sitemap и графы
 * Organization + WebPage + BreadcrumbList. Не закрывает:
 *  - LocalBusiness с адресом, координатами, часами и зоной обслуживания
 *    (в бесплатной версии это платный аддон Local SEO) — важно для запросов
 *    вида «септик Чебоксары»;
 *  - Product с ценой и наличием для записей типа stations — из-за этого
 *    карточки станций не могут показываться с ценой в выдаче;
 *  - ItemList для каталога станций.
 *
 * FAQPage уже выводится на главной (см. front-page.php) — здесь не дублируем.
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

/**
 * Печать JSON-LD блока.
 *
 * @param array<string,mixed> $data Граф.
 * @return void
 */
function izex_print_jsonld($data)
{
    if (empty($data)) {
        return;
    }
    echo '<script type="application/ld+json">'
        . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        . '</script>' . "\n";
}

/**
 * Часы работы из настроек темы в формат schema.org.
 *
 * «Пн-Вс: 9:00 - 20:00» → «Mo-Su 09:00-20:00». Если строку разобрать не удалось,
 * возвращаем null — лучше не отдать поле, чем отдать мусор.
 *
 * @param string $raw Строка из настроек.
 * @return string|null
 */
function izex_schema_opening_hours($raw)
{
    $days = array(
        'пн' => 'Mo', 'вт' => 'Tu', 'ср' => 'We', 'чт' => 'Th',
        'пт' => 'Fr', 'сб' => 'Sa', 'вс' => 'Su',
    );

    $raw = mb_strtolower((string) $raw, 'UTF-8');
    if (!preg_match('/(пн|вт|ср|чт|пт|сб|вс)\s*[-–—]\s*(пн|вт|ср|чт|пт|сб|вс)/u', $raw, $d)) {
        return null;
    }
    if (!preg_match('/(\d{1,2})[:.](\d{2})\s*[-–—]\s*(\d{1,2})[:.](\d{2})/u', $raw, $t)) {
        return null;
    }

    return sprintf(
        '%s-%s %02d:%s-%02d:%s',
        $days[$d[1]],
        $days[$d[2]],
        (int) $t[1],
        $t[2],
        (int) $t[3],
        $t[4]
    );
}

/**
 * LocalBusiness — только на главной.
 *
 * @return void
 */
function izex_schema_local_business()
{
    if (!is_front_page()) {
        return;
    }

    $company = getCompanyContacts();

    $data = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'LocalBusiness',
        '@id'         => home_url('/#localbusiness'),
        'name'        => get_bloginfo('name') ?: 'Сервис Септик21',
        'description' => 'Продажа, монтаж и сервисное обслуживание септиков ТОПАС '
            . 'в Чебоксарах, Новочебоксарске и по Чувашской Республике.',
        'url'         => home_url('/'),
        'image'       => get_template_directory_uri() . '/assets/images/logo-transparent-216.png',
        'priceRange'  => 'от 100 000 ₽',
        'areaServed'  => array(
            array('@type' => 'AdministrativeArea', 'name' => 'Чувашская Республика'),
            array('@type' => 'City', 'name' => 'Чебоксары'),
            array('@type' => 'City', 'name' => 'Новочебоксарск'),
        ),
        // Координаты офиса — те же, что использовались в карте на странице контактов.
        'geo'         => array(
            '@type'     => 'GeoCoordinates',
            'latitude'  => 56.076182,
            'longitude' => 47.228092,
        ),
    );

    if (!empty($company['phone_clean'])) {
        $data['telephone'] = $company['phone_clean'];
    }
    if (!empty($company['email'])) {
        $data['email'] = $company['email'];
    }

    $address = array(
        '@type'           => 'PostalAddress',
        'addressCountry'  => 'RU',
        'addressRegion'   => 'Чувашская Республика',
        'addressLocality' => 'Чебоксары',
    );
    if (!empty($company['address'])) {
        $address['streetAddress'] = $company['address'];
    }
    $data['address'] = $address;

    $hours = izex_schema_opening_hours($company['work_time'] ?? '');
    if ($hours) {
        $data['openingHours'] = $hours;
    }

    // Соцсети/мессенджеры из настроек — sameAs помогает связать профили с сайтом.
    $same_as = array();
    if (function_exists('getSocialLinks')) {
        foreach (getSocialLinks() as $link) {
            if (!empty($link['url'])) {
                $same_as[] = $link['url'];
            }
        }
    }
    if (!empty($company['telegram'])) {
        $same_as[] = 'https://t.me/' . $company['telegram'];
    }
    if ($same_as) {
        $data['sameAs'] = array_values(array_unique($same_as));
    }

    izex_print_jsonld($data);
}
add_action('wp_head', 'izex_schema_local_business', 20);

/**
 * Product — на странице станции.
 *
 * Цена берётся та же, что показывается в карточке (линейка ТОПАС-С с фолбэком
 * на ТОПАС). Без цены Product не отдаём: offer без price Google игнорирует,
 * а «пустая» разметка только мешает.
 *
 * @return void
 */
function izex_schema_product()
{
    if (!is_singular('stations')) {
        return;
    }

    $id = get_queried_object_id();
    $d = izex_pro_station_data($id);
    if (empty($d['price'])) {
        return;
    }

    $data = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        '@id'         => $d['url'] . '#product',
        'name'        => $d['title'],
        'url'         => $d['url'],
        'brand'       => array('@type' => 'Brand', 'name' => 'ТОПАС'),
        'category'    => 'Септики и станции биологической очистки',
        'description' => wp_strip_all_tags(get_the_excerpt($id) ?: $d['title'] . ' — автономная канализация для дома и дачи.'),
        'offers'      => array(
            '@type'           => 'Offer',
            'url'             => $d['url'],
            'price'           => $d['price'],
            'priceCurrency'   => 'RUB',
            'availability'    => 'https://schema.org/InStock',
            'itemCondition'   => 'https://schema.org/NewCondition',
            // Цены пересматриваются, поэтому ограничиваем срок действия оффера.
            'priceValidUntil' => gmdate('Y-m-d', strtotime('+3 months')),
            'seller'          => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo('name') ?: 'Сервис Септик21',
                'url'   => home_url('/'),
            ),
        ),
    );

    if (!empty($d['img'])) {
        $data['image'] = $d['img'];
    }

    // Характеристики — в additionalProperty: Google их не показывает, но они
    // помогают сопоставить модель с запросами вида «топас 5 производительность».
    if (!empty($d['specs'])) {
        $props = array();
        foreach ($d['specs'] as $spec) {
            $props[] = array(
                '@type' => 'PropertyValue',
                'name'  => $spec['label'],
                'value' => $spec['value'],
            );
        }
        $data['additionalProperty'] = $props;
    }

    izex_print_jsonld($data);
}
add_action('wp_head', 'izex_schema_product', 20);

/**
 * ItemList — в архиве станций: показывает поисковику состав каталога.
 *
 * @return void
 */
function izex_schema_stations_list()
{
    if (!is_post_type_archive('stations')) {
        return;
    }

    $query = new WP_Query(array(
        'post_type'      => 'stations',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ));

    if (empty($query->posts)) {
        return;
    }

    $items = array();
    foreach ($query->posts as $i => $post) {
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => get_the_title($post->ID),
            'url'      => get_permalink($post->ID),
        );
    }
    wp_reset_postdata();

    izex_print_jsonld(array(
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'name'            => 'Каталог станций ТОПАС',
        'numberOfItems'   => count($items),
        'itemListElement' => $items,
    ));
}
add_action('wp_head', 'izex_schema_stations_list', 20);
