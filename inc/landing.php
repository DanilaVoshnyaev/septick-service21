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
 * (тип «Подбор септика»), инженер считает смету сам. В каждом вопросе
 * есть «Не знаю», обязательны только имя и телефон.
 *
 * @return string
 */
function izex_render_septik_podbor()
{
    $dunno = 'Не знаю';
    $questions = array(
        array('Населённый пункт', 'text', 'Например: Чебоксары, д. Сятракасы'),
        array('Сколько человек живёт постоянно', 'select', array('1–2', '3–4', '5–6', '7–8', '9–10', 'больше 10')),
        array('Как живёте в доме', 'select', array('Круглый год', 'Летом и наездами', 'Дом ещё строится')),
        array('Ванна или джакузи', 'select', array('Есть ванна', 'Только душ', 'Джакузи или большая ванна')),
        array('На какой глубине труба выходит из дома', 'select', array('до 60 см', '60–90 см', 'глубже 90 см')),
        array('Грунт на участке', 'select', array('Песок', 'Суглинок', 'Глина')),
        array('Грунтовые воды', 'select', array('Глубоко', 'Близко, весной подтапливает')),
        array('Куда отводить очищенную воду', 'select', array('В канаву', 'В грунт на участке', 'Не решили')),
        array('Бюджет на септик под ключ', 'select', array('до 150 тыс. ₽', '150–200 тыс. ₽', '200–250 тыс. ₽', 'больше 250 тыс. ₽')),
        array('Когда планируете установку', 'select', array('В ближайший месяц', 'В этом сезоне', 'Позже, пока выбираю')),
        array('Марка (необязательно)', 'select', array('ТОПАС', 'Юнилос Астра', 'Эко-Гранд', 'Евролос', 'Итал Био', 'КИТ', 'Евробион', 'Биодевайс')),
    );

    ob_start(); ?>
    <form class="modal-form-premium podbor-form" data-form-type="podbor">
        <input type="text" name="hp_email" class="form-hp" tabindex="-1" autocomplete="off" aria-hidden="true">
        <div class="podbor-form__grid">
            <?php foreach ($questions as $i => $q) : ?>
                <label class="podbor-form__field">
                    <span class="podbor-form__label"><?php echo esc_html($q[0]); ?></span>
                    <?php if ($q[1] === 'text') : ?>
                        <input type="text" data-label="<?php echo esc_attr($q[0]); ?>" placeholder="<?php echo esc_attr($q[2]); ?>">
                    <?php else : ?>
                        <select data-label="<?php echo esc_attr($q[0]); ?>">
                            <option value=""><?php echo esc_html($i === count($questions) - 1 ? 'Не важно' : $dunno); ?></option>
                            <?php foreach ($q[2] as $option) : ?>
                                <option><?php echo esc_html($option); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </label>
            <?php endforeach; ?>
        </div>
        <div class="podbor-form__contacts">
            <div class="form-group form-group-premium">
                <input type="text" name="name" placeholder="Ваше имя *" required>
                <span class="form-error"></span>
            </div>
            <div class="form-group form-group-premium">
                <input type="tel" name="phone" placeholder="+7 (___) ___-__-__ *" required>
                <span class="form-error"></span>
            </div>
        </div>
        <label class="form-consent"><input type="checkbox" name="consent" required checked> Согласен на обработку персональных данных</label>
        <button type="submit" class="btn-premium btn-full btn-gold">
            <span class="spinner"></span>
            <span class="btn-text">Отправить инженеру</span>
        </button>
        <p class="form-privacy">Инженер перезвонит, уточнит детали и предложит 2–3 подходящие станции с ценой под ключ. Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('septik_podbor', 'izex_render_septik_podbor');
