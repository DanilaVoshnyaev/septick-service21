<?php
/**
 * Мультибрендовая витрина: данные моделей и марок, фильтры каталога,
 * форма подбора. Общие для главной, каталога и карточки станции.
 */

/**
 * Марки каталога в порядке показа. Страница марки — посадочная с ценами
 * производителя; у ТОПАС своей посадочной нет, ведём в каталог.
 *
 * @return array<string,array{name:string,page:string}>
 */
function ss_brands()
{
    return array(
        'topas'     => array('name' => 'ТОПАС', 'page' => ''),
        'astra'     => array('name' => 'Юнилос Астра', 'page' => '/septik-unilos-astra/'),
        'eko-grand' => array('name' => 'Эко-Гранд', 'page' => '/septik-eko-grand/'),
        'evrolos'   => array('name' => 'Евролос', 'page' => '/septik-evrolos/'),
        'ital-bio'  => array('name' => 'Итал Био', 'page' => '/septik-ital-bio/'),
        'kit'       => array('name' => 'КИТ', 'page' => '/septik-kit/'),
        'evrobion'  => array('name' => 'Евробион', 'page' => '/septik-evrobion/'),
        'biodevice' => array('name' => 'Биодевайс', 'page' => '/septik-biodevice/'),
    );
}

/**
 * Ключ марки по названию из поля «Марка» (пусто — ТОПАС).
 */
function ss_brand_key($brand_name)
{
    $needle = mb_strtolower(trim((string) $brand_name));
    foreach (ss_brands() as $key => $brand) {
        if ($needle === mb_strtolower($brand['name'])) {
            return $key;
        }
    }
    if ($needle === '' || mb_strpos($needle, 'топас') !== false) {
        return 'topas';
    }
    return sanitize_title($brand_name);
}

/**
 * Данные модели для карточек, фильтра и подбора.
 *
 * Исполнение и отвод для ТОПАС читаем из названия («Лонг», «Пр»): так их
 * называет производитель, а в карточке они должны совпадать с заголовком.
 */
function ss_station($post_id)
{
    static $cache = array();
    $post_id = (int) $post_id;
    if (isset($cache[$post_id])) {
        return $cache[$post_id];
    }

    $base = izex_pro_station_data($post_id);
    $title = $base['title'];
    $brand = izex_station_brand($post_id);
    $is_topas = izex_station_is_topas($post_id);

    $disposal_raw = mb_strtolower($base['disposal']);
    if (mb_strpos($disposal_raw, 'самот') !== false && (mb_strpos($disposal_raw, 'насос') !== false || mb_strpos($disposal_raw, 'принуд') !== false)) {
        $disposal = 'both';
    } elseif (mb_strpos($disposal_raw, 'принуд') !== false || preg_match('/\sПр$/u', $title)) {
        $disposal = 'pump';
    } else {
        $disposal = 'gravity';
    }
    $disposal_labels = array(
        'gravity' => 'самотёком',
        'pump'    => 'насосом (принудительный)',
        'both'    => 'самотёком или насосом',
    );
    $disposal_short = array('gravity' => 'самотёком', 'pump' => 'насосом', 'both' => 'самотёк или насос');

    $variant = '';
    if ($is_topas) {
        if (mb_stripos($title, 'Лонг Ус') !== false) {
            $variant = 'Лонг Ус';
        } elseif (mb_stripos($title, 'Лонг') !== false) {
            $variant = 'Лонг';
        } else {
            $variant = 'Стандарт';
        }
    } elseif (preg_match('/\b(Стандарт|Миди|Лонг)\b/u', $title, $m)) {
        $variant = $m[1];
    }

    $num = function ($key) use ($post_id) {
        return trim((string) carbon_get_post_meta($post_id, $key));
    };

    $data = array_merge($base, array(
        'brand'          => $brand,
        'brand_key'      => ss_brand_key($is_topas ? 'ТОПАС' : $brand),
        'is_topas'       => $is_topas,
        'series'         => $is_topas ? (mb_strpos($title, 'ТОПАС-С') === 0 ? 'ТОПАС-С' : 'ТОПАС') : $brand,
        'variant'        => $variant,
        'disposal_type'  => $disposal,
        'disposal_label' => $disposal_labels[$disposal],
        'disposal_short' => $disposal_short[$disposal],
        'in_stock'       => function_exists('station_is_in_stock') ? (bool) station_is_in_stock($post_id) : false,
        'price_s'        => (int) preg_replace('/[^\d]/', '', $num('crb_price_topas_s')),
        'price_topas'    => (int) preg_replace('/[^\d]/', '', $num('crb_price')),
        'model_number'   => (int) $num('crb_model_number'),
        'daily'          => $num('crb_daily_volume'),
        'peak'           => $num('crb_peak_discharge'),
        'power'          => $num('crb_power_consumption'),
        'dimensions'     => $num('crb_mounting_dimensions'),
        'pdf'            => $num('crb_manual_pdf'),
        'img_large'      => get_the_post_thumbnail_url($post_id, 'large') ?: '',
        'depth'          => $is_topas ? array('Стандарт' => 'standard', 'Лонг' => 'long', 'Лонг Ус' => 'longus')[$variant] : '',
    ));

    // У карточки ТОПАС-С своя цена — цена модели из заголовка, без подмены ценой ТОПАС.
    if ($is_topas) {
        $data['price'] = $data['series'] === 'ТОПАС-С' ? ($data['price_s'] ?: 0) : ($data['price_topas'] ?: $data['price_s']);
        $data['install'] = izex_pro_install_base();
    } else {
        $data['install'] = 0;
    }

    $cache[$post_id] = $data;
    return $data;
}

/**
 * Все опубликованные станции: сначала ТОПАС по цене, затем другие марки.
 *
 * @return array<int,array>
 */
function ss_catalog()
{
    static $list = null;
    if ($list !== null) {
        return $list;
    }
    $ids = get_posts(array(
        'post_type'      => 'stations',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
    ));
    $order = array_keys(ss_brands());
    $list = array_map('ss_station', $ids);
    usort($list, function ($a, $b) use ($order) {
        $ba = array_search($a['brand_key'], $order, true);
        $bb = array_search($b['brand_key'], $order, true);
        if ($ba !== $bb) {
            return ($ba === false ? 99 : $ba) <=> ($bb === false ? 99 : $bb);
        }
        $pa = $a['price'] ?: PHP_INT_MAX;
        $pb = $b['price'] ?: PHP_INT_MAX;
        return $pa === $pb ? strcmp($a['title'], $b['title']) : $pa <=> $pb;
    });
    return $list;
}

/**
 * Сводка по марке: сколько моделей в каталоге и есть ли что-то в наличии.
 */
function ss_brand_summary()
{
    $out = array();
    foreach (ss_brands() as $key => $brand) {
        $out[$key] = $brand + array('count' => 0, 'in_stock' => 0, 'min_price' => 0);
    }
    foreach (ss_catalog() as $s) {
        if (!isset($out[$s['brand_key']])) {
            continue;
        }
        $row = &$out[$s['brand_key']];
        $row['count']++;
        $row['in_stock'] += $s['in_stock'] ? 1 : 0;
        if ($s['price'] && (!$row['min_price'] || $s['price'] < $row['min_price'])) {
            $row['min_price'] = $s['price'];
        }
        unset($row);
    }
    return $out;
}

function ss_money($value)
{
    return number_format((int) $value, 0, ',', ' ') . ' ₽';
}

/**
 * Склонение: 1 модель, 2 модели, 5 моделей.
 */
function ss_models_word($n)
{
    return izex_plural($n, array('модель', 'модели', 'моделей'));
}

/* =====================================================================
   Фильтры каталога
   ===================================================================== */

/**
 * Значения фильтров. Ключи — GET-параметры каталога.
 */
function ss_filter_options()
{
    return array(
        'brand'    => array_map(function ($b) { return $b['name']; }, ss_brands()),
        'users'    => array('3' => 'до 3', '4' => '4', '5' => '5', '6' => '6', '8' => '7–8', '10' => '9–10'),
        'disposal' => array('gravity' => 'Самотёком', 'pump' => 'Насосом (принудительный)'),
        'price'    => array('150' => 'до 150 тыс. ₽', '200' => '150–200 тыс. ₽', '250' => '200–250 тыс. ₽', '999' => 'от 250 тыс. ₽'),
        'depth'    => ss_depth_options(),
    );
}

/**
 * Глубина подводящей трубы — по исполнению ТОПАС. Показываем только те
 * исполнения, которые есть в каталоге: у других марок глубина зависит
 * от модели и в названии не видна.
 */
function ss_depth_options()
{
    $all = array('standard' => 'до 80 см — Стандарт', 'long' => '80–140 см — Лонг', 'longus' => 'до 240 см — Лонг Ус');
    $present = array();
    foreach (ss_catalog() as $s) {
        if ($s['depth']) {
            $present[$s['depth']] = true;
        }
    }
    return array_intersect_key($all, $present);
}

/**
 * Текущий выбор из адреса; неизвестные значения отбрасываем.
 *
 * @return array<string,string>
 */
function ss_filter_state()
{
    $state = array();
    foreach (ss_filter_options() as $key => $options) {
        $raw = isset($_GET[$key]) ? sanitize_text_field(wp_unslash($_GET[$key])) : '';
        if ($raw !== '' && isset($options[$raw])) {
            $state[$key] = $raw;
        }
    }
    $sort = isset($_GET['sort']) ? sanitize_key($_GET['sort']) : '';
    if (in_array($sort, array('price_desc', 'users'), true)) {
        $state['sort'] = $sort;
    }
    return $state;
}

/**
 * Подходит ли модель под фильтр. Та же логика продублирована в redesign.js.
 *
 * Число пользователей: показываем модели с запасом до двух человек —
 * на 4 жильцов подходят станции до 4–6 человек, но не на 10.
 */
function ss_station_matches($s, $state)
{
    if (!empty($state['brand']) && $s['brand_key'] !== $state['brand']) {
        return false;
    }
    if (!empty($state['users'])) {
        $n = (int) $state['users'];
        if (!$s['people_max'] || $s['people_max'] < $n || $s['people_max'] > $n + 2) {
            return false;
        }
    }
    if (!empty($state['disposal']) && $s['disposal_type'] !== 'both' && $s['disposal_type'] !== $state['disposal']) {
        return false;
    }
    if (!empty($state['depth']) && $s['depth'] !== $state['depth']) {
        return false;
    }
    if (!empty($state['price'])) {
        $hi = (int) $state['price'] * 1000;
        $lo = array(150000 => 0, 200000 => 150000, 250000 => 200000, 999000 => 250000)[$hi];
        if (!$s['price'] || $s['price'] <= $lo || ($hi < 999000 && $s['price'] > $hi)) {
            return false;
        }
    }
    return true;
}

/**
 * Параметры, при которых каталог — это отфильтрованная копия /stations/.
 * Включая прежние capacity/drainage/stock: ссылки с ними могли попасть в поиск.
 */
function ss_catalog_filter_params()
{
    return array('brand', 'users', 'disposal', 'price', 'depth', 'sort', 'capacity', 'drainage', 'stock');
}

function ss_is_filtered_catalog()
{
    if (!is_post_type_archive('stations')) {
        return false;
    }
    foreach (ss_catalog_filter_params() as $param) {
        if (isset($_GET[$param]) && $_GET[$param] !== '') {
            return true;
        }
    }
    return false;
}

/**
 * Отфильтрованный каталог не индексируем, но ссылки с него учитываем;
 * канонический адрес — сам каталог. Так фильтры не плодят дублей.
 */
add_filter('rank_math/frontend/robots', function ($robots) {
    if (ss_is_filtered_catalog()) {
        $robots['index'] = 'noindex';
        $robots['follow'] = 'follow';
    }
    return $robots;
});
add_filter('rank_math/frontend/canonical', function ($canonical) {
    return ss_is_filtered_catalog() ? get_post_type_archive_link('stations') : $canonical;
});
add_filter('wp_robots', function ($robots) {
    if (ss_is_filtered_catalog() && !defined('RANK_MATH_VERSION')) {
        $robots['noindex'] = true;
        $robots['follow'] = true;
    }
    return $robots;
});

/* =====================================================================
   Форма подбора
   ===================================================================== */

/**
 * Лёгкий список моделей для предварительного подбора в браузере.
 */
function ss_podbor_models()
{
    return array_map(function ($s) {
        return array(
            'title'    => $s['title'],
            'url'      => $s['url'],
            'brand'    => $s['brand_key'],
            'min'      => $s['people_min'],
            'max'      => $s['people_max'],
            'disposal' => $s['disposal_type'],
            'variant'  => $s['variant'],
            'price'    => $s['price'],
            'stock'    => $s['in_stock'],
            'topas'    => $s['is_topas'],
        );
    }, ss_catalog());
}

/**
 * Пошаговая форма подбора. Без JavaScript все шаги видны одним списком.
 * Ответы уходят в заявку типа «Подбор септика».
 */
function ss_render_podbor()
{
    $steps = array(
        array(
            'title' => 'Для какого объекта нужна канализация?',
            'groups' => array(
                array('Тип объекта', array('Жилой дом', 'Дача', 'Баня или гостевой дом', 'Коммерческий объект', 'Не знаю')),
            ),
        ),
        array(
            'title' => 'Сколько человек будет пользоваться?',
            'hint'  => 'Считайте тех, кто живёт постоянно, и гостей, которые бывают часто.',
            'groups' => array(
                array('Число пользователей', array('1–2', '3–4', '5–6', '7–8', '9–10', 'Больше 10', 'Не знаю')),
            ),
        ),
        array(
            'title' => 'Как будете жить в доме?',
            'groups' => array(
                array('Проживание', array('Постоянно', 'Сезонно, наездами', 'Не знаю')),
            ),
        ),
        array(
            'title' => 'Что известно об участке?',
            'hint'  => 'Если не знаете — отметьте «Не знаю», инженер проверит на выезде.',
            'groups' => array(
                array('Грунт', array('Песок', 'Суглинок', 'Глина', 'Не знаю')),
                array('Грунтовые воды', array('Глубоко', 'Близко, весной подтапливает', 'Не знаю')),
                array('Глубина выхода трубы из дома', array('до 80 см', '80–140 см', 'глубже 140 см', 'Не знаю')),
            ),
        ),
        array(
            'title' => 'Где и когда устанавливать?',
            'fields' => true,
            'groups' => array(
                array('Срок установки', array('В ближайший месяц', 'В этом сезоне', 'Позже, пока выбираю', 'Не знаю')),
            ),
        ),
    );
    $total = count($steps) + 1;
    $brands = ss_brands();

    ob_start(); ?>
    <form class="ss-podbor premium-contact-form" data-form-type="podbor" data-ss-podbor novalidate>
        <input type="text" name="hp_email" class="form-hp" tabindex="-1" autocomplete="off" aria-hidden="true">
        <div class="ss-podbor__progress" aria-hidden="true"><span class="ss-podbor__bar"></span></div>
        <?php foreach ($steps as $i => $step) : ?>
            <section class="ss-podbor__step" data-step="<?php echo $i + 1; ?>">
                <p class="ss-podbor__count">Шаг <?php echo $i + 1; ?> из <?php echo $total; ?></p>
                <h3 class="ss-podbor__title"><?php echo esc_html($step['title']); ?></h3>
                <?php if (!empty($step['hint'])) : ?>
                    <p class="ss-podbor__hint"><?php echo esc_html($step['hint']); ?></p>
                <?php endif; ?>
                <?php if (!empty($step['fields'])) : ?>
                    <label class="ss-field">
                        <span class="ss-field__label">Населённый пункт</span>
                        <input type="text" class="ss-input" data-label="Населённый пункт" placeholder="Например: Новочебоксарск" autocomplete="address-level2">
                    </label>
                <?php endif; ?>
                <?php foreach ($step['groups'] as $g => $group) : ?>
                    <fieldset class="ss-choice" data-label="<?php echo esc_attr($group[0]); ?>">
                        <legend class="ss-field__label"><?php echo esc_html($group[0]); ?></legend>
                        <?php foreach ($group[1] as $option) : ?>
                            <label class="ss-chip">
                                <input type="radio" name="q<?php echo $i . '_' . $g; ?>" value="<?php echo esc_attr($option); ?>">
                                <span><?php echo esc_html($option); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </fieldset>
                <?php endforeach; ?>
                <?php if (!empty($step['fields'])) : ?>
                    <label class="ss-field">
                        <span class="ss-field__label">Марка <span class="ss-field__opt">— необязательно</span></span>
                        <select class="ss-input" data-label="Марка" data-podbor-brand>
                            <option value="">Любая, посоветуйте</option>
                            <?php foreach ($brands as $key => $brand) : ?>
                                <option value="<?php echo esc_attr($brand['name']); ?>" data-key="<?php echo esc_attr($key); ?>"><?php echo esc_html($brand['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                <?php endif; ?>
                <div class="ss-podbor__nav">
                    <?php if ($i > 0) : ?>
                        <button type="button" class="ss-btn ss-btn--ghost" data-podbor-back>Назад</button>
                    <?php endif; ?>
                    <button type="button" class="ss-btn ss-btn--primary" data-podbor-next>Далее</button>
                </div>
            </section>
        <?php endforeach; ?>

        <section class="ss-podbor__step" data-step="<?php echo $total; ?>">
            <p class="ss-podbor__count">Шаг <?php echo $total; ?> из <?php echo $total; ?></p>
            <h3 class="ss-podbor__title">Предварительный подбор</h3>
            <div class="ss-podbor__result" data-podbor-result>
                <p class="ss-podbor__hint">Инженер посмотрит ваши ответы и предложит 2–3 подходящие станции с расчётом монтажа.</p>
            </div>
            <input type="hidden" data-label="Предварительно подобрано" data-podbor-picked value="">
            <div class="ss-form__row">
                <div class="form-group">
                    <label class="ss-field__label" for="podbor-phone">Телефон</label>
                    <input class="ss-input" id="podbor-phone" type="tel" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel" inputmode="tel" required>
                    <span class="form-error" role="alert"></span>
                </div>
                <div class="form-group">
                    <label class="ss-field__label" for="podbor-name">Имя <span class="ss-field__opt">— необязательно</span></label>
                    <input class="ss-input" id="podbor-name" type="text" name="name" autocomplete="given-name">
                    <span class="form-error" role="alert"></span>
                </div>
            </div>
            <?php echo ss_consent_field(); ?>
            <div class="ss-podbor__nav">
                <button type="button" class="ss-btn ss-btn--ghost" data-podbor-back>Назад</button>
                <button type="submit" class="ss-btn ss-btn--primary">Получить расчёт</button>
            </div>
            <p class="ss-form__note">Перезвоним, уточним детали и пришлём расчёт оборудования, монтажа и доставки. Точную стоимость называем после того, как инженер увидит участок.</p>
        </section>
        <script type="application/json" data-podbor-models><?php echo wp_json_encode(ss_podbor_models(), JSON_UNESCAPED_UNICODE); ?></script>
    </form>
    <?php
    return ob_get_clean();
}

/**
 * Галочка согласия: не отмечена заранее, ссылка на действующую политику.
 */
function ss_consent_field()
{
    return '<label class="form-consent ss-consent"><input type="checkbox" name="consent" value="1" required>'
        . '<span>Согласен на <a href="/privacy/" target="_blank">обработку персональных данных</a></span></label>';
}

/**
 * Опубликованные работы для главной: фото, место, модель, условия.
 *
 * @return WP_Post[]
 */
function ss_recent_works($count = 3)
{
    return get_posts(array(
        'post_type'      => 'works',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
    ));
}
