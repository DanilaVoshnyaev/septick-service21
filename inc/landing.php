<?php
/**
 * Посадочные страницы под поисковые запросы («септик для дачи», «при высоких
 * грунтовых водах», «в Новочебоксарске»…): шаблон page-landing.php и
 * подборка подходящих станций шорткодом [topas_models].
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

// Анонс страницы выводится подзаголовком под H1 посадочной.
add_action('init', function () {
    add_post_type_support('page', 'excerpt');
});

/**
 * Подборка станций под условия посадочной страницы.
 *
 * [topas_models people="5" drainage="принуд" limit="6" title="Подходящие модели"]
 *
 *  people   — модель рассчитана на такое число жильцов (входит в её диапазон);
 *  min/max  — верхняя граница модели («до N человек») в этих пределах;
 *  drainage — «принуд» (модели Пр) или «самот»;
 *  limit    — сколько карточек показать, самые доступные «под ключ» первыми.
 *
 * @param array<string,string>|string $atts Атрибуты шорткода.
 * @return string
 */
function izex_render_topas_models($atts)
{
    $atts = shortcode_atts(array(
        'people'   => 0,
        'min'      => 0,
        'max'      => 0,
        'drainage' => '',
        'limit'    => 6,
        'title'    => 'Подходящие модели',
    ), $atts, 'topas_models');

    $people = (int) $atts['people'];
    $min    = (int) $atts['min'];
    $max    = (int) $atts['max'];
    $stem   = (string) $atts['drainage'] === '' ? '' : (mb_stripos($atts['drainage'], 'принуд') !== false ? 'принуд' : 'самот');

    $query = new WP_Query(array(
        'post_type'      => 'stations',
        'post__not_in'   => izex_non_topas_station_ids(),
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'no_found_rows'  => true,
    ));

    $stations = array();
    foreach ($query->posts as $post) {
        $d = izex_pro_station_data($post->ID);
        if ($people && ($people < $d['people_min'] || $people > $d['people_max'])) {
            continue;
        }
        if ($min && $d['people_max'] < $min) {
            continue;
        }
        if ($max && $d['people_max'] > $max) {
            continue;
        }
        if ($stem && mb_stripos($d['disposal'], $stem) === false) {
            continue;
        }
        $stations[] = $d;
    }

    if (empty($stations)) {
        return '';
    }

    usort($stations, function ($a, $b) {
        return $a['turnkey'] <=> $b['turnkey'];
    });
    $stations = array_slice($stations, 0, max(1, (int) $atts['limit']));

    ob_start(); ?>
    <section class="landing-models">
        <?php if ($atts['title'] !== '') : ?>
            <h2 class="landing-models__title"><?php echo esc_html($atts['title']); ?></h2>
        <?php endif; ?>
        <div class="stations-grid pro-grid">
            <?php foreach ($stations as $d) : ?>
                <?php get_template_part('template-parts/station-card-pro', null, array('id' => $d['id'])); ?>
            <?php endforeach; ?>
        </div>
        <p class="landing-models__more">
            <a href="<?php echo esc_url(get_post_type_archive_link('stations')); ?>">Весь каталог ТОПАС с ценами под ключ →</a>
        </p>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('topas_models', 'izex_render_topas_models');

/**
 * Форма подбора септика для инженера: [septik_podbor].
 *
 * Автоматической цены здесь нет — ответы уходят в обычную заявку
 * (тип «Подбор септика»), инженер считает смету сам. Разметка — ss_render_podbor().
 *
 * @return string
 */
function izex_render_septik_podbor()
{
    return ss_render_podbor();
}
add_shortcode('septik_podbor', 'izex_render_septik_podbor');
