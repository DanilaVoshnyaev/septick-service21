<?php
/**
 * ================= КАРТА ГЕОГРАФИИ РАБОТ (#29) =================
 *
 * Раз фотоотчётов нет, доверие держится на другом: человек должен увидеть, что
 * в его районе уже работали. Текстовый реестр это даёт, но плохо читается
 * целиком — карта показывает охват одним взглядом.
 *
 * Закрашиваем районы, а не ставим точки: точка на райцентре говорит «мы были
 * в этом городе», закрашенный район — «мы работаем в этой части республики»,
 * и это ближе к правде, потому что объекты стоят по деревням вокруг.
 *
 * Две отрисовки одних и тех же данных:
 *   1. SVG-схема — отдаётся сервером сразу, кликается без всякого API;
 *   2. Яндекс.Карта — если задан ключ, подгружается лениво и заменяет схему.
 *
 * Геометрия — inc/chuvashia-map.php (сгенерировано из данных OpenStreetMap,
 * лицензия ODbL: она требует указания источника, поэтому под картой стоит
 * строка атрибуции).
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
 * Основа названия для сопоставления: «Вурнарский район» → «вурнар».
 *
 * @param string $name
 * @return string
 */
function izex_geo_stem($name)
{
    $s = mb_strtolower(trim($name));
    $s = str_replace(array(' район', 'городской округ ', 'город '), '', $s);
    foreach (array('ский', 'ской', 'цкий', 'ый', 'ий', 'ая', 'ое') as $suffix) {
        if (mb_substr($s, -mb_strlen($suffix)) === $suffix) {
            $s = mb_substr($s, 0, -mb_strlen($suffix));
            break;
        }
    }

    return $s;
}

/**
 * Районы со счётчиком объектов.
 *
 * Объект относим к первому подошедшему району — иначе работа в Канаше попала бы
 * и в город, и в Канашский район, и счёт задвоился бы.
 *
 * @return array<int,array<string,mixed>>
 */
function izex_districts_with_works()
{
    return izex_cache_remember('districts_with_works', 6 * HOUR_IN_SECONDS, function () {
        $districts = izex_chuvashia_districts();

        // Сопоставляем и по названию района, и по райцентру: в поле объекта
        // пишут по-разному — «Вурнары», «Вурнарский район», «пос. Вурнары».
        foreach ($districts as $i => $d) {
            $stems = array(izex_geo_stem($d['name']));
            if (!empty($d['place'])) {
                $stems[] = izex_geo_stem($d['place']);
            }
            $districts[$i]['stems'] = array_values(array_unique(array_filter($stems)));
            $districts[$i]['count'] = 0;
        }

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
            foreach ($districts as $i => $d) {
                foreach ($d['stems'] as $stem) {
                    if ($stem !== '' && mb_strpos($location, $stem) !== false) {
                        $districts[$i]['count']++;
                        continue 3;
                    }
                }
            }
        }
        wp_reset_postdata();

        return $districts;
    });
}

/**
 * Шорткод карты: [topas_geo_map]
 *
 * Атрибут section="1" оборачивает карту в секцию с заголовком — так она
 * вставляется на главную; без него это просто блок для страницы объектов.
 */
add_shortcode('topas_geo_map', 'render_topas_geo_map');
function render_topas_geo_map($atts)
{
    if (!function_exists('izex_chuvashia_districts')) {
        return '';
    }

    $atts = shortcode_atts(array('section' => 0), $atts, 'topas_geo_map');
    $districts = izex_districts_with_works();

    $active = array_values(array_filter($districts, function ($d) {
        return $d['count'] > 0;
    }));
    $total = 0;
    foreach ($active as $d) {
        $total += $d['count'];
    }

    izex_enqueue_geo_map_script($active);

    ob_start(); ?>
    <?php if (!empty($atts['section'])) : ?>
    <section class="geo-map-section" id="geography">
        <div class="container">
            <div class="section-header">
                <span class="section-label">География</span>
                <h2 class="section-title">Где мы уже работали</h2>
                <p class="section-subtitle">
                    <?php echo esc_html($total
                        ? 'Закрашены районы, в которых стоят наши станции. Выезжаем по всей Чувашии и в соседние регионы Поволжья.'
                        : 'Выезжаем по всей Чувашии и в соседние регионы Поволжья.'); ?>
                </p>
            </div>
    <?php endif; ?>

    <figure class="geo-map" data-geo-map>
        <div class="geo-map__canvas">
            <svg viewBox="<?php echo esc_attr(izex_chuvashia_viewbox()); ?>"
                 role="img"
                 aria-label="Карта Чувашии: закрашены районы, где мы устанавливали станции"
                 preserveAspectRatio="xMidYMid meet">
                <?php // Соседние регионы Поволжья — фон и зона выезда. Рисуем первыми,
                      // чтобы контур республики и заливки районов легли поверх. ?>
                <?php foreach (izex_volga_neighbours() as $n) : ?>
                    <path class="geo-map__neighbour" d="<?php echo esc_attr($n['svg']); ?>">
                        <title><?php echo esc_html($n['name'] . ' — выезжаем'); ?></title>
                    </path>
                <?php endforeach; ?>

                <path class="geo-map__outline" d="<?php echo esc_attr(izex_chuvashia_outline()); ?>"/>

                <?php // Рисуем только районы с объектами: остальные границы на схеме
                      // такого размера превращаются в шум, а вес разметки утраивают. ?>
                <?php foreach ($active as $d) : ?>
                    <?php // data-stems — основы названий для фильтра списка: у города
                          // Канаша и Канашского района они разные, а поле объекта
                          // заполняют как придётся. ?>
                    <g class="geo-map__district" data-place="<?php echo esc_attr($d['name']); ?>"
                       data-stems="<?php echo esc_attr(implode('|', $d['stems'])); ?>"
                       role="button" tabindex="0"
                       aria-label="<?php echo esc_attr($d['name'] . ': объектов — ' . $d['count']); ?>">
                        <path d="<?php echo esc_attr($d['svg']); ?>"/>
                        <title><?php echo esc_html($d['name'] . ': объектов — ' . $d['count']); ?></title>
                    </g>
                <?php endforeach; ?>

                <?php // Подписи рисуем после заливок, иначе соседний район их перекроет. ?>
                <?php foreach ($active as $d) : ?>
                    <text class="geo-map__label"
                          x="<?php echo esc_attr($d['label'][0]); ?>"
                          y="<?php echo esc_attr($d['label'][1]); ?>"
                          text-anchor="middle"><?php echo esc_html($d['place'] ?: $d['name']); ?></text>
                <?php endforeach; ?>
            </svg>
        </div>

        <figcaption class="geo-map__caption">
            <?php if ($total) : ?>
                Закрашены районы, где уже стоят наши станции: нажмите на район,
                чтобы оставить в списке только его объекты. Пунктиром — соседние
                регионы Поволжья, куда мы тоже выезжаем.
            <?php else : ?>
                Работаем по всей Чувашии — районы закрасятся по мере пополнения
                списка объектов.
            <?php endif; ?>
            <?php // Атрибуция обязательна по лицензии ODbL, под которой отдаются
                  // границы OpenStreetMap. Оформить её можно как угодно тихо,
                  // но убирать нельзя. ?>
        </figcaption>
    </figure>

    <?php if (!empty($atts['section'])) : ?>
        </div>
    </section>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}

/**
 * Подключает скрипт интерактивной карты и передаёт ему геометрию.
 *
 * В JS уезжают только закрашенные районы плюс контур республики: полный набор
 * из 26 полигонов весит под сотню килобайт, а видно на карте всё равно только
 * то, что закрашено.
 *
 * @param array<int,array<string,mixed>> $active Районы с объектами.
 * @return void
 */
function izex_enqueue_geo_map_script($active)
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

    $path = '/assets/js/geo-map.js';
    $abs = get_template_directory() . $path;
    if (!file_exists($abs)) {
        return;
    }

    $regions = array();
    foreach ($active as $d) {
        $regions[] = array(
            'name'  => $d['name'],
            'label' => $d['place'] ?: $d['name'],
            'count' => (int) $d['count'],
            'stems' => array_values($d['stems']),
            'rings' => $d['rings'],
        );
    }

    wp_enqueue_script(
        'izex-geo-map',
        get_template_directory_uri() . $path,
        array(),
        filemtime($abs),
        true
    );
    $neighbours = array();
    foreach (izex_volga_neighbours() as $n) {
        $neighbours[] = array('name' => $n['name'], 'rings' => $n['rings']);
    }

    wp_localize_script('izex-geo-map', 'izexGeoMap', array(
        'apiKey'     => $key,
        'regions'    => $regions,
        'neighbours' => $neighbours,
        'outline'    => izex_chuvashia_outline_ring(),
    ));
}
