<?php
/**
 * ================= КАРТА ГЕОГРАФИИ РАБОТ (#29) =================
 *
 * Раз фотоотчётов нет, доверие держится на другом: человек должен увидеть в
 * списке свой район. Текстовый реестр это даёт, но плохо читается целиком —
 * схема республики показывает охват одним взглядом.
 *
 * Это иллюстрация, а не «карта объектов с адресами»: точка ставится на
 * райцентр, а не на участок клиента. Точки, где у нас есть объекты, залиты
 * брендовым зелёным и подписаны количеством; остальные райцентры — бледные,
 * как зона выезда.
 *
 * Геометрия и координаты — inc/chuvashia-map.php (сгенерировано из данных
 * OpenStreetMap, лицензия ODbL, поэтому под картой стоит атрибуция).
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

/**
 * Ключ Яндекс.Карт по умолчанию — чтобы карта работала сразу, без захода
 * в админку. Поле «API-ключ Яндекс.Карт» в настройках темы его перебивает.
 *
 * Ключ JS API публичный по своей природе: он в любом случае виден в исходном
 * коде страницы. Защита у него одна — привязка к домену в кабинете Яндекса,
 * её и нужно настроить для servis-septik21.ru.
 */
if (!defined('IZEX_YANDEX_MAPS_KEY')) {
    define('IZEX_YANDEX_MAPS_KEY', 'd6f7360a-d863-46d5-a687-a494b383f647');
}

/**
 * Сколько объектов приходится на каждый райцентр.
 *
 * Сопоставляем поле «Район / населённый пункт» с названиями из справочника:
 * «Вурнарский район» и «пос. Вурнары» одинаково попадают в «Вурнары».
 *
 * @return array<string,int> Название райцентра => число объектов.
 */
function izex_works_by_place()
{
    return izex_cache_remember('works_by_place', 6 * HOUR_IN_SECONDS, function () {
        $places = izex_chuvashia_places();
        $counts = array();

        $query = new WP_Query(array(
            'post_type'      => 'works',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'no_found_rows'  => true,
        ));

        foreach ($query->posts as $post) {
            $location = mb_strtolower((string) carbon_get_post_meta($post->ID, 'crb_work_location'));
            if ($location === '') {
                continue;
            }
            foreach (array_keys($places) as $place) {
                // Сравниваем по основе названия: «Вурнары» → «вурнар».
                $stem = mb_strtolower(mb_substr($place, 0, max(4, mb_strlen($place) - 2)));
                if (mb_strpos($location, $stem) !== false) {
                    $counts[$place] = isset($counts[$place]) ? $counts[$place] + 1 : 1;
                    break;
                }
            }
        }
        wp_reset_postdata();

        return $counts;
    });
}

/**
 * Шорткод карты: [topas_geo_map]
 */
add_shortcode('topas_geo_map', 'render_topas_geo_map');
function render_topas_geo_map($atts)
{
    if (!function_exists('izex_chuvashia_places')) {
        return '';
    }

    $places = izex_chuvashia_places();
    $counts = izex_works_by_place();
    $total = array_sum($counts);

    // Ключ Яндекс.Карт задан — подключаем ленивую подгрузку интерактивной карты
    // поверх схемы. Скрипт сам ничего не делает, пока блок не доскроллили.
    izex_enqueue_geo_map_script($places, $counts);

    ob_start(); ?>
    <figure class="geo-map" data-geo-map>
        <div class="geo-map__canvas">
            <svg viewBox="<?php echo esc_attr(izex_chuvashia_viewbox()); ?>"
                 role="img"
                 aria-label="Схема Чувашии: районы, где мы устанавливали станции"
                 preserveAspectRatio="xMidYMid meet">
                <path class="geo-map__outline" d="<?php echo esc_attr(izex_chuvashia_outline()); ?>"/>

                <?php foreach ($places as $name => $xy) : ?>
                    <?php $has = isset($counts[$name]); ?>
                    <g class="geo-map__point<?php echo $has ? ' is-active' : ''; ?>">
                        <circle cx="<?php echo esc_attr($xy['x']); ?>"
                                cy="<?php echo esc_attr($xy['y']); ?>"
                                r="<?php echo $has ? '5' : '2.6'; ?>">
                            <title><?php
                                echo esc_html($has
                                    ? $name . ': объектов — ' . $counts[$name]
                                    : $name . ' — выезжаем');
                            ?></title>
                        </circle>
                        <?php if ($has) : ?>
                            <text x="<?php echo esc_attr($xy['x'] + 9); ?>"
                                  y="<?php echo esc_attr($xy['y'] + 4); ?>"><?php echo esc_html($name); ?></text>
                        <?php endif; ?>
                    </g>
                <?php endforeach; ?>
            </svg>
        </div>

        <figcaption class="geo-map__caption">
            <?php if ($total) : ?>
                Зелёным — районы, где уже стоят наши станции.
            <?php else : ?>
                Точками отмечены районные центры Чувашии — выезжаем во все.
            <?php endif; ?>
            Точка ставится на райцентр, а не на участок клиента.
            <span class="geo-map__credit">Контур: © участники OpenStreetMap</span>
        </figcaption>
    </figure>
    <?php
    return ob_get_clean();
}

/**
 * Подключает скрипт интерактивной карты и передаёт ему точки.
 *
 * Ключ хранится в настройках темы: пока он не задан, ни скрипт, ни внешний
 * API не грузятся вообще — на странице остаётся только SVG-схема.
 *
 * @param array<string,array{x:float,y:float,lat:float,lon:float}> $places
 * @param array<string,int> $counts
 * @return void
 */
function izex_enqueue_geo_map_script($places, $counts)
{
    // Ключ из настроек темы, иначе — ключ по умолчанию из константы.
    // ?? здесь не работал бы: trim() возвращает пустую строку, а не null.
    $key = trim((string) carbon_get_theme_option('crb_yandex_maps_key'));
    if ($key === '') {
        $key = IZEX_YANDEX_MAPS_KEY;
    }
    if ($key === '') {
        return;
    }

    $points = array();
    foreach ($places as $name => $xy) {
        $points[] = array(
            'name'  => $name,
            'lat'   => $xy['lat'],
            'lon'   => $xy['lon'],
            'count' => isset($counts[$name]) ? (int) $counts[$name] : 0,
        );
    }

    $path = '/assets/js/geo-map.js';
    $abs = get_template_directory() . $path;
    if (!file_exists($abs)) {
        return;
    }

    wp_enqueue_script(
        'izex-geo-map',
        get_template_directory_uri() . $path,
        array(),
        filemtime($abs),
        true
    );
    wp_localize_script('izex-geo-map', 'izexGeoMap', array(
        'apiKey' => $key,
        'points' => $points,
    ));
}
