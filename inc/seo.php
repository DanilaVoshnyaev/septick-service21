<?php
/**
 * SEO-модуль темы: meta description, Open Graph, canonical, Schema.org, robots.txt.
 *
 * ВНИМАНИЕ: если установите SEO-плагин (Yoast / Rank Math), отключите этот модуль
 * (закомментируйте require в functions.php), чтобы не дублировать мета-теги.
 *
 * @package Topas_Template
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Формирует описание (description) для текущей страницы, обрезанное до ~160 символов.
 */
function izex_seo_description()
{
    $desc = '';

    // 1) Ручные значения из админки имеют приоритет
    if ((is_front_page() || is_home()) && function_exists('carbon_get_theme_option')) {
        $manual = carbon_get_theme_option('home_seo_description');
        if ($manual) {
            $desc = $manual;
        }
    } elseif (is_singular() && function_exists('carbon_get_post_meta')) {
        $manual = carbon_get_post_meta(get_queried_object_id(), 'crb_seo_description');
        if ($manual) {
            $desc = $manual;
        }
    }

    // 2) Авто-формирование, если вручную не задано
    if (!$desc && is_singular()) {
        $post = get_queried_object();
        if ($post instanceof WP_Post) {
            $desc = has_excerpt($post) ? get_the_excerpt($post) : $post->post_content;
        }
    } elseif (!$desc && is_post_type_archive('stations')) {
        $desc = 'Каталог автономных канализаций ТОПАС: цены, характеристики, доставка и монтаж под ключ.';
    } elseif (!$desc && (is_home() || is_front_page())) {
        $desc = get_bloginfo('description');
    } elseif (!$desc && (is_category() || is_tax())) {
        $desc = term_description();
    }

    if (!$desc) {
        $desc = get_bloginfo('description');
    }

    $desc = wp_strip_all_tags(strip_shortcodes($desc));
    $desc = trim(preg_replace('/\s+/u', ' ', $desc));

    if (function_exists('mb_substr') && mb_strlen($desc) > 160) {
        $desc = rtrim(mb_substr($desc, 0, 157)) . '…';
    }

    return $desc;
}

/**
 * Картинка для соцсетей: миниатюра записи либо логотип/заглушка темы.
 */
function izex_seo_image()
{
    if (is_singular() && has_post_thumbnail()) {
        $img = get_the_post_thumbnail_url(get_queried_object_id(), 'large');
        if ($img) {
            return $img;
        }
    }

    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $img = wp_get_attachment_image_url($custom_logo_id, 'full');
        if ($img) {
            return $img;
        }
    }

    return get_template_directory_uri() . '/assets/images/logo.png';
}

/**
 * Канонический URL текущей страницы.
 */
function izex_seo_canonical()
{
    if (is_singular()) {
        return get_permalink(get_queried_object_id());
    }
    if (is_post_type_archive()) {
        return get_post_type_archive_link(get_query_var('post_type') ?: 'post');
    }
    if (is_front_page() || is_home()) {
        return home_url('/');
    }
    if (is_category() || is_tag() || is_tax()) {
        $link = get_term_link(get_queried_object());
        if (!is_wp_error($link)) {
            return $link;
        }
    }

    global $wp;
    return home_url(add_query_arg(array(), $wp->request));
}

/**
 * Переопределение тега <title> вручную заданным SEO Title (если он указан).
 */
function izex_seo_document_title($title)
{
    if ((is_front_page() || is_home()) && function_exists('carbon_get_theme_option')) {
        $manual = carbon_get_theme_option('home_seo_title');
        if ($manual) {
            return $manual;
        }
    } elseif (is_singular() && function_exists('carbon_get_post_meta')) {
        $manual = carbon_get_post_meta(get_queried_object_id(), 'crb_seo_title');
        if ($manual) {
            return $manual;
        }
    }

    return $title;
}
add_filter('pre_get_document_title', 'izex_seo_document_title', 20);

/**
 * Вывод meta description, canonical, Open Graph и Twitter Card в <head>.
 */
function izex_seo_meta_tags()
{
    $desc      = izex_seo_description();
    $canonical = izex_seo_canonical();
    $image     = izex_seo_image();
    $title     = wp_get_document_title();
    $site_name = get_bloginfo('name');
    $type      = is_singular() && !is_front_page() ? 'article' : 'website';

    echo "\n<!-- SEO (тема) -->\n";

    if ($desc) {
        printf('<meta name="description" content="%s">' . "\n", esc_attr($desc));
    }
    if ($canonical) {
        printf('<link rel="canonical" href="%s">' . "\n", esc_url($canonical));
    }

    // Open Graph
    printf('<meta property="og:locale" content="%s">' . "\n", esc_attr(get_locale()));
    printf('<meta property="og:type" content="%s">' . "\n", esc_attr($type));
    printf('<meta property="og:title" content="%s">' . "\n", esc_attr($title));
    if ($desc) {
        printf('<meta property="og:description" content="%s">' . "\n", esc_attr($desc));
    }
    printf('<meta property="og:url" content="%s">' . "\n", esc_url($canonical));
    printf('<meta property="og:site_name" content="%s">' . "\n", esc_attr($site_name));
    if ($image) {
        printf('<meta property="og:image" content="%s">' . "\n", esc_url($image));
    }

    // Twitter
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    printf('<meta name="twitter:title" content="%s">' . "\n", esc_attr($title));
    if ($desc) {
        printf('<meta name="twitter:description" content="%s">' . "\n", esc_attr($desc));
    }
    if ($image) {
        printf('<meta name="twitter:image" content="%s">' . "\n", esc_url($image));
    }

    echo "<!-- /SEO -->\n";
}
add_action('wp_head', 'izex_seo_meta_tags', 1);

/**
 * Schema.org JSON-LD: организация (LocalBusiness), товар (Product), хлебные крошки.
 */
function izex_seo_schema()
{
    $company = function_exists('getCompanyContacts') ? getCompanyContacts() : array();
    $option  = function ($key) {
        return function_exists('carbon_get_theme_option') ? carbon_get_theme_option($key) : '';
    };

    $org_name = $option('operator_name') ?: get_bloginfo('name');
    $logo     = izex_seo_image();

    $blocks = array();

    // --- Организация / локальный бизнес (на всех страницах) ---
    $local = array(
        '@context' => 'https://schema.org',
        '@type'    => 'LocalBusiness',
        'name'     => $org_name,
        'url'      => home_url('/'),
        'image'    => $logo,
        'logo'     => $logo,
    );
    if (!empty($company['phone_clean'])) {
        $local['telephone'] = $company['phone_clean'];
    }
    if (!empty($company['email'])) {
        $local['email'] = $company['email'];
    }
    if (!empty($company['address'])) {
        $local['address'] = array(
            '@type'           => 'PostalAddress',
            'streetAddress'   => $company['address'],
            'addressCountry'  => 'RU',
        );
    }
    if (!empty($company['work_time'])) {
        $local['openingHours'] = $company['work_time'];
    }
    $blocks[] = $local;

    // --- Товар (на странице станции) ---
    if (is_singular('stations')) {
        $id    = get_queried_object_id();
        $price = function_exists('carbon_get_post_meta')
            ? (carbon_get_post_meta($id, 'crb_price_topas_s') ?: carbon_get_post_meta($id, 'crb_price'))
            : '';
        $in_stock = function_exists('carbon_get_post_meta') ? carbon_get_post_meta($id, 'crb_in_stock') : false;
        $img      = has_post_thumbnail($id) ? get_the_post_thumbnail_url($id, 'large') : $logo;

        $product = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => get_the_title($id),
            'description' => izex_seo_description(),
            'image'       => $img,
            'brand'       => array('@type' => 'Brand', 'name' => 'ТОПАС'),
        );
        if ($price) {
            $product['offers'] = array(
                '@type'         => 'Offer',
                'price'         => preg_replace('/[^0-9.]/', '', (string) $price),
                'priceCurrency' => 'RUB',
                'availability'  => $in_stock ? 'https://schema.org/InStock' : 'https://schema.org/PreOrder',
                'url'           => get_permalink($id),
            );
        }
        $blocks[] = $product;
    }

    // --- Хлебные крошки (для одиночных записей) ---
    if (is_singular() && !is_front_page()) {
        $items = array(
            array('@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => home_url('/')),
        );
        $pos = 2;
        $pt  = get_post_type();
        if ($pt && $pt !== 'page') {
            $pt_obj = get_post_type_object($pt);
            $archive = get_post_type_archive_link($pt);
            if ($pt_obj && $archive) {
                $items[] = array('@type' => 'ListItem', 'position' => $pos++, 'name' => $pt_obj->labels->name, 'item' => $archive);
            }
        }
        $items[] = array('@type' => 'ListItem', 'position' => $pos, 'name' => get_the_title());

        $blocks[] = array(
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        );
    }

    foreach ($blocks as $block) {
        echo '<script type="application/ld+json">' .
            wp_json_encode($block, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) .
            '</script>' . "\n";
    }
}
add_action('wp_head', 'izex_seo_schema', 5);

/**
 * Дополняет виртуальный robots.txt ссылкой на карту сайта WordPress.
 */
function izex_seo_robots_txt($output, $public)
{
    if ('1' != $public) {
        return $output; // сайт закрыт от индексации в настройках — не вмешиваемся
    }

    $lines  = "User-agent: *\n";
    $lines .= "Disallow: /wp-admin/\n";
    $lines .= "Allow: /wp-admin/admin-ajax.php\n";
    $lines .= "Disallow: /?s=\n";
    $lines .= "Disallow: /search/\n";
    $lines .= 'Sitemap: ' . home_url('/wp-sitemap.xml') . "\n";

    return $lines;
}
add_filter('robots_txt', 'izex_seo_robots_txt', 10, 2);
