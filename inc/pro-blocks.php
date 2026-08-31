<?php
/**
 * Блоки, перенесённые из прототипа заказчика.
 *
 * Содержит:
 *  - izex_pro_station_data()      — единый набор данных станции для карточек;
 *  - izex_pro_install_base()      — базовая стоимость монтажа (кэш на запрос);
 *  - [topas_trust_marquee]        — бегущая строка доверия;
 *  - [topas_estimate]             — «что входит в монтаж / оплачивается отдельно»;
 *  - [topas_compare_inline]       — инлайн-таблица сравнения моделей;
 *  - render_topas_calculator()    — одноэкранный калькулятор (переопределён здесь).
 *
 * Палитра и шрифты темы не меняются — только структура и вёрстка блоков.
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

/**
 * Базовая стоимость монтажа из настроек калькулятора.
 * Кэшируем: carbon_get_theme_option() на каждую карточку — лишние запросы.
 *
 * @return int
 */
function izex_pro_install_base()
{
    static $base = null;
    if ($base === null) {
        $settings = izex_get_calculator_settings();
        $base = (int) $settings['installBase'];
    }
    return $base;
}

/**
 * Данные станции для карточки каталога и карточки в hero.
 *
 * Цену показываем по линейке ТОПАС-С (она ниже), с фолбэком на ТОПАС.
 * «Под ключ» — оборудование + базовый монтаж, тот же расчёт, что на странице цен.
 *
 * @param int $post_id ID записи станции.
 * @return array<string,mixed>
 */
function izex_pro_station_data($post_id)
{
    $post_id = (int) $post_id;

    // Одну и ту же станцию за запрос спрашивают карточка каталога, карточка
    // в hero и данные калькулятора — считаем один раз (задача #25).
    static $cache = array();
    if (isset($cache[$post_id])) {
        return $cache[$post_id];
    }

    $to_int = function ($raw) {
        return (int) preg_replace('/[^\d]/', '', (string) $raw);
    };

    $price_s = $to_int(carbon_get_post_meta($post_id, 'crb_price_topas_s'));
    $price   = $to_int(carbon_get_post_meta($post_id, 'crb_price'));
    $display = $price_s ?: $price;
    $old     = $to_int(carbon_get_post_meta($post_id, 'crb_old_price'));

    $people_text = (string) carbon_get_post_meta($post_id, 'crb_people_count_text');
    $disposal    = (string) carbon_get_post_meta($post_id, 'crb_water_disposal');

    // Число проживающих: диапазон из текста («3–5 человек» → 3..5), иначе из названия.
    // Диапазон нужен фильтру: «до 5 человек» должно совпадать так же, как на сервере.
    preg_match_all('/\d+/', $people_text, $m);
    $nums = $m[0];
    if (empty($nums)) {
        preg_match_all('/\d+/', get_the_title($post_id), $mt);
        $nums = $mt[0];
    }
    $nums = array_map('intval', $nums);
    $people_min = $nums ? min($nums) : 0;
    $people_max = $nums ? max($nums) : 0;

    $specs = array();
    $daily = carbon_get_post_meta($post_id, 'crb_daily_volume');
    $peak  = carbon_get_post_meta($post_id, 'crb_peak_discharge');
    $power = carbon_get_post_meta($post_id, 'crb_power_consumption');
    if ($daily) {
        $specs[] = array('label' => 'Производительность', 'value' => izex_format_station_spec($daily, 'м³/сут'));
    }
    if ($peak) {
        $specs[] = array('label' => 'Залповый сброс', 'value' => izex_format_station_spec($peak, 'л'));
    }
    if ($power) {
        $specs[] = array('label' => 'Потребление', 'value' => izex_format_station_spec($power, 'кВт·ч/сут'));
    }
    if ($disposal) {
        $specs[] = array('label' => 'Водоотведение', 'value' => $disposal);
    }

    $data = array(
        'id'          => $post_id,
        'title'       => get_the_title($post_id),
        'url'         => get_permalink($post_id),
        'img'         => get_the_post_thumbnail_url($post_id, 'medium_large') ?: '',
        'price'       => $display,
        'old_price'   => ($old > $display) ? $old : 0,
        // «Под ключ» — главная цифра в карточке и hero (задача #10),
        // поэтому отдаём и слагаемые: станция + базовый монтаж.
        'install'     => izex_pro_install_base(),
        'turnkey'     => $display > 0 ? $display + izex_pro_install_base() : 0,
        'people_text' => $people_text,
        'people_min'  => $people_min,
        'people_max'  => $people_max,
        'disposal'    => $disposal,
        'is_hit'      => (bool) carbon_get_post_meta($post_id, 'crb_is_hit'),
        'specs'       => $specs,
    );

    $cache[$post_id] = $data;

    return $data;
}

/**
 * Станция для карточки в hero: сначала помеченная как «хит», иначе модель на
 * 5 человек (самая ходовая), иначе первая опубликованная.
 *
 * @return array<string,mixed>|null
 */
function izex_pro_hero_station()
{
    // Считаем один раз за запрос: карточку в hero спрашивает и главная,
    // и (при откате) прежние шаблоны. false — «ещё не считали», null —
    // «станций нет» (задача #25).
    static $hero = false;
    if ($hero !== false) {
        return $hero;
    }
    $hero = izex_pro_find_hero_station();

    return $hero;
}

function izex_pro_find_hero_station()
{
    $query = new WP_Query(array(
        'post_type'      => 'stations',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'no_found_rows'  => true,
    ));

    if (empty($query->posts)) {
        return null;
    }

    $fallback = null;
    $five = null;
    foreach ($query->posts as $post) {
        if (carbon_get_post_meta($post->ID, 'crb_is_hit')) {
            return izex_pro_station_data($post->ID);
        }
        if ($five === null && (int) carbon_get_post_meta($post->ID, 'crb_model_number') === 5) {
            $five = $post->ID;
        }
        if ($fallback === null) {
            $fallback = $post->ID;
        }
    }

    return izex_pro_station_data($five ?: $fallback);
}

/**
 * Форматирование суммы в рублях.
 *
 * @param int $value Сумма.
 * @return string
 */
function izex_pro_money($value)
{
    $value = (int) $value;
    return $value > 0 ? number_format($value, 0, '.', ' ') . ' ₽' : 'По запросу';
}

/**
 * ================= БЕГУЩАЯ СТРОКА ДОВЕРИЯ =================
 */
add_shortcode('topas_trust_marquee', 'render_topas_trust_marquee');
function render_topas_trust_marquee($atts)
{
    $items = array(
        'Официальный дилер ТОПАС',
        'Бесплатная доставка по Чувашии',
        'Прозрачная смета без скрытых платежей',
        'Монтаж за 1 день',
        'Сервис и обслуживание',
        'Договор и гарантия на работы',
    );

    // Дублируем список: трек уезжает на -50%, поэтому нужны две одинаковые половины.
    ob_start(); ?>
    <div class="pro-marquee" aria-hidden="true">
        <div class="pro-marquee__track">
            <?php for ($copy = 0; $copy < 2; $copy++) : ?>
                <div class="pro-marquee__item">
                    <?php foreach ($items as $item) : ?>
                        <span><b>★</b> <?php echo esc_html(mb_strtoupper($item, 'UTF-8')); ?></span>
                        <span class="pro-marquee__sep">/</span>
                    <?php endforeach; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * ================= ЧИП-ФИЛЬТР КАТАЛОГА (общий для главной и архива) =================
 *
 * Раньше фильтр существовал в двух версиях: чипы на главной и форма из <select>
 * в архиве станций. Теперь разметка одна — отличается только базовый адрес
 * ссылок и подпись счётчика.
 *
 * Чипы — ссылки: без JS работает серверная фильтрация с перезагрузкой, с JS
 * catalog-instant.js фильтрует уже отрендеренные карточки мгновенно.
 *
 * @param array{base_url:string,capacity:int|string,drainage:string,sort:string,count:int,anchor:string} $args
 * @return void
 */
function izex_render_catalog_filter($args = array())
{
    $args = wp_parse_args($args, array(
        'base_url' => home_url('/'),
        'capacity' => 0,
        'drainage' => '',
        'sort'     => '',
        'count'    => 0,
        'anchor'   => '#catalog',
    ));

    $capacity_chips = array(3, 4, 5, 6, 8, 10);

    $current = array(
        'capacity' => $args['capacity'] ?: null,
        'drainage' => $args['drainage'] ?: null,
        'sort'     => $args['sort'] ?: null,
    );

    // Ссылка чипа: меняет одно условие, остальные оставляет как есть.
    // Повторный клик по активному чипу снимает условие.
    $chip_url = function ($key, $value) use ($current, $args) {
        $q = $current;
        $q[$key] = ((string) $current[$key] === (string) $value) ? null : $value;
        $q = array_filter($q, function ($v) {
            return $v !== null && $v !== '';
        });
        return add_query_arg($q, $args['base_url']) . $args['anchor'];
    };

    $has_filter = ($args['capacity'] || $args['drainage'] || $args['sort'] !== '');
    ?>
    <div class="pro-filter">
        <div class="pro-filter__group">
            <span class="pro-eyebrow">Пользователей</span>
            <div class="pro-filter__chips">
                <?php foreach ($capacity_chips as $capacity) : ?>
                    <a class="pro-chip<?php echo ((string) $args['capacity'] === (string) $capacity) ? ' is-active' : ''; ?>"
                       href="<?php echo esc_url($chip_url('capacity', $capacity)); ?>"
                       data-filter="people"
                       data-value="<?php echo esc_attr($capacity); ?>">до <?php echo esc_html($capacity); ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="pro-filter__group">
            <span class="pro-eyebrow">Отведение</span>
            <div class="pro-filter__chips">
                <?php foreach (array('Самотёк', 'Принудительное') as $drainage) : ?>
                    <a class="pro-chip<?php echo ($args['drainage'] === $drainage) ? ' is-active' : ''; ?>"
                       href="<?php echo esc_url($chip_url('drainage', $drainage)); ?>"
                       data-filter="disposal"
                       data-value="<?php echo esc_attr($drainage); ?>"><?php echo esc_html($drainage); ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="pro-filter__group">
            <span class="pro-eyebrow">Сортировка</span>
            <div class="pro-filter__chips">
                <a class="pro-chip<?php echo ($args['sort'] !== 'price_desc') ? ' is-active' : ''; ?>"
                   href="<?php echo esc_url($chip_url('sort', 'price_asc')); ?>"
                   data-filter="sort" data-value="">Сначала дешёвые</a>
                <a class="pro-chip<?php echo ($args['sort'] === 'price_desc') ? ' is-active' : ''; ?>"
                   href="<?php echo esc_url($chip_url('sort', 'price_desc')); ?>"
                   data-filter="sort" data-value="price_desc">Сначала дорогие</a>
            </div>
        </div>

        <div class="pro-filter__meta">
            <span class="pro-filter__count" data-catalog-count>Найдено: <?php echo (int) $args['count']; ?></span>
            <a class="pro-filter__reset"
               href="<?php echo esc_url($args['base_url'] . $args['anchor']); ?>"
               data-catalog-reset
               <?php echo $has_filter ? '' : 'hidden'; ?>>Сбросить</a>
        </div>
    </div>
    <?php
}

/**
 * ================= КАНАЛЫ СВЯЗИ (общий источник) =================
 *
 * Один список на два места вывода: боковой док на десктопе и нижняя панель на
 * мобильных. Мессенджеры берём из контактов темы, остальные соцсети — из
 * getSocialLinks() (настройки «Соцсети»), поэтому добавленное заказчиком в
 * админке появляется в доке само, без правки шаблонов.
 *
 * @return array<int,array<string,string>> элементы: key,label,url,icon(html),mod
 */
function izex_pro_contact_items()
{
    $company = getCompanyContacts();
    $items = array();

    // WhatsApp сознательно не выводим — заказчик им не пользуется.
    // (Поле whatsapp_number в настройках при этом остаётся, оно по умолчанию
    // подставляет основной номер, так что автоматически кнопка не вернётся.)

    if (!empty($company['telegram'])) {
        $items[] = array(
            'key'   => 'tg',
            'label' => 'Telegram',
            'url'   => 'https://t.me/' . rawurlencode($company['telegram']),
            'mod'   => 'tg',
            'icon'  => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M21.9 4.3l-3.3 15.6c-.2 1.1-.9 1.3-1.8.8l-5-3.7-2.4 2.3c-.3.3-.5.5-1 .5l.3-5 9.2-8.3c.4-.4-.1-.6-.6-.2L5.9 13 1 11.5c-1-.3-1-1 .2-1.5l19.3-7.4c.9-.3 1.7.2 1.4 1.7z"/></svg>',
        );
    }

    // Соцсети из настроек темы (фолбэк внутри getSocialLinks() — ссылка MAX).
    if (function_exists('getSocialLinks')) {
        foreach (getSocialLinks() as $link) {
            if (empty($link['url'])) {
                continue;
            }
            $caption = $link['caption'] !== '' ? $link['caption'] : 'Соцсеть';
            $icon = !empty($link['icon'])
                ? '<img src="' . esc_url($link['icon']) . '" alt="" loading="lazy">'
                : '<span class="pro-dock__letter">' . esc_html(mb_substr($caption, 0, 1)) . '</span>';

            $items[] = array(
                'key'   => 'social-' . sanitize_title($caption),
                'label' => $caption,
                'url'   => $link['url'],
                'mod'   => 'social',
                'icon'  => $icon,
            );
        }
    }

    return $items;
}

/**
 * ================= БОКОВОЙ ДОК СВЯЗИ =================
 *
 * Столбик иконок справа снизу (как в прототипе): мессенджеры, соцсети и звонок.
 * Заменяет прежний .social-float (он висел по центру справа и дублировал эти же
 * ссылки). На мобильных док скрыт — там работает нижняя .mobile-action-bar.
 */
function izex_pro_render_dock()
{
    $company = getCompanyContacts();
    ?>
    <div class="pro-dock" aria-label="Быстрая связь">
        <?php foreach (izex_pro_contact_items() as $item) : ?>
            <a class="pro-dock__btn pro-dock__btn--<?php echo esc_attr($item['mod']); ?>"
               href="<?php echo esc_url($item['url']); ?>"
               target="_blank" rel="noopener nofollow"
               title="<?php echo esc_attr($item['label']); ?>"
               aria-label="<?php echo esc_attr($item['label']); ?>">
                <?php echo $item['icon']; // разметка иконки собрана и экранирована в izex_pro_contact_items() ?>
            </a>
        <?php endforeach; ?>

        <a class="pro-dock__btn pro-dock__btn--call"
           href="tel:<?php echo esc_attr($company['phone_clean']); ?>"
           title="Позвонить" aria-label="Позвонить">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.1-8.7A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/>
            </svg>
        </a>
    </div>
    <?php
}

/**
 * ================= НИЖНЯЯ ПАНЕЛЬ НА МОБИЛЬНЫХ =================
 *
 * Было: «Позвонить» с подписью, MAX картинкой 32px без подписи и «Заявка» —
 * разнокалиберно. Стало: одинаковые иконки 22px и подпись у каждой кнопки,
 * мессенджеры из тех же настроек, что и док. Каналов на панели максимум два,
 * иначе кнопки становятся уже 70px и подписи начинают переноситься.
 */
function izex_pro_render_mobile_bar()
{
    $company = getCompanyContacts();
    $channels = array_slice(izex_pro_contact_items(), 0, 2);
    ?>
    <nav class="mobile-action-bar" aria-label="Быстрые действия">
        <a class="mobile-action-bar__btn mobile-action-bar__btn--call"
           href="tel:<?php echo esc_attr($company['phone_clean']); ?>">
            <span class="mobile-action-bar__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
            </span>
            <span>Позвонить</span>
        </a>

        <?php foreach ($channels as $item) : ?>
            <a class="mobile-action-bar__btn mobile-action-bar__btn--<?php echo esc_attr($item['mod']); ?>"
               href="<?php echo esc_url($item['url']); ?>"
               target="_blank" rel="noopener nofollow">
                <span class="mobile-action-bar__icon"><?php echo $item['icon']; ?></span>
                <span><?php echo esc_html($item['label']); ?></span>
            </a>
        <?php endforeach; ?>

        <button type="button" class="mobile-action-bar__btn mobile-action-bar__btn--order open-modal" data-modal="order">
            <span class="mobile-action-bar__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 15h6M9 11h2"/>
                </svg>
            </span>
            <span>Заявка</span>
        </button>
    </nav>
    <?php
}

/**
 * ================= СМЕТА: ВХОДИТ / ОПЛАЧИВАЕТСЯ ОТДЕЛЬНО =================
 *
 * Данные — те же, что на странице цен (izex_get_prices_lists), чтобы списки
 * не расходились между главной и /prices.
 */
add_shortcode('topas_estimate', 'render_topas_estimate');
function render_topas_estimate($atts)
{
    $lists = izex_get_prices_lists();
    $included = isset($lists['included']) ? $lists['included'] : array();
    $extra = isset($lists['extra']) ? $lists['extra'] : array();

    if (empty($included) && empty($extra)) {
        return '';
    }

    $prices_url = get_page_by_path('prices') ? get_permalink(get_page_by_path('prices')) : '';

    ob_start(); ?>
    <section class="pro-estimate" id="estimate">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Цены и смета</span>
                <h2 class="section-title">Что входит в монтаж под ключ</h2>
                <p class="section-subtitle">
                    Прозрачная смета без скрытых платежей: ниже — что уже включено в стоимость монтажа,
                    и что может оплачиваться отдельно в зависимости от условий участка.
                </p>
            </div>

            <div class="pro-estimate__cols">
                <?php if (!empty($included)) : ?>
                    <div class="pro-estimate__col pro-estimate__col--in">
                        <h3 class="pro-estimate__title">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" style="color:#16a34a">
                                <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                            </svg>
                            Включено в монтаж под ключ
                        </h3>
                        <ul class="pro-estimate__list">
                            <?php foreach ($included as $item) : ?>
                                <li><span class="pro-estimate__mark">✓</span><span><?php echo esc_html($item); ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($extra)) : ?>
                    <div class="pro-estimate__col pro-estimate__col--extra">
                        <h3 class="pro-estimate__title">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" style="color:#d97706">
                                <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                            </svg>
                            Оплачивается отдельно
                        </h3>
                        <ul class="pro-estimate__list">
                            <?php foreach ($extra as $row) : ?>
                                <li>
                                    <span class="pro-estimate__mark">+</span>
                                    <span>
                                        <?php echo esc_html($row['item']); ?>
                                        <?php if (!empty($row['price'])) : ?>
                                            <b class="pro-estimate__price"><?php echo esc_html($row['price']); ?></b>
                                        <?php endif; ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>

            <div class="pro-estimate__note">
                <div>
                    <div class="pro-estimate__note-title">Смету фиксируем в договоре до начала работ</div>
                    <div class="pro-estimate__note-text">Стоимость не меняется в процессе. Выезд инженера и расчёт сметы — бесплатно.</div>
                </div>
                <div style="display:flex;gap:12px;flex-wrap:wrap">
                    <button type="button" class="pro-btn pro-btn--solid open-modal" data-modal="engineer">Получить смету бесплатно</button>
                    <?php if ($prices_url) : ?>
                        <a class="pro-btn pro-btn--ghost" style="border-color:rgba(255,255,255,.35);color:#fff" href="<?php echo esc_url($prices_url); ?>">Все цены</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

/**
 * ================= ИНЛАЙН-СРАВНЕНИЕ МОДЕЛЕЙ =================
 *
 * Таблицу рисует compare.js: он уже держит выбор в localStorage и знает
 * характеристики (topasCompare.stations). Здесь только контейнер и подсказка;
 * атрибут data-compare-default задаёт, сколько моделей показать, пока
 * пользователь ничего не отметил.
 */
add_shortcode('topas_compare_inline', 'render_topas_compare_inline');
function render_topas_compare_inline($atts)
{
    // bare="1" — вывод без <section> и .container: так блок вставляется прямо
    // в контейнер каталога, сразу под карточками, без двойных отступов
    // (задача #22 — раньше до таблицы нужно было проскроллить CTA и вернуться
    // обратно к карточкам, чтобы отметить модели).
    $atts = shortcode_atts(array('default' => 3, 'bare' => 0), $atts, 'topas_compare_inline');
    $bare = !empty($atts['bare']);

    // Без данных таблица не построится — не выводим пустую секцию.
    $stations = izex_get_compare_stations();
    if (count($stations) < 2) {
        return '';
    }

    ob_start(); ?>
    <?php if (!$bare) : ?>
    <section class="pro-compare" id="compare">
        <div class="container">
    <?php else : ?>
        <div class="pro-compare pro-compare--bare" id="compare">
    <?php endif; ?>
            <div class="section-header">
                <span class="section-label">Сравнение</span>
                <h2 class="section-title">Сравнение моделей</h2>
            </div>

            <div class="pro-compare__head">
                <p class="pro-compare__hint" data-compare-hint>
                    Отметьте «Сравнить» на карточках выше — до 4 моделей. Пока показаны популярные.
                </p>
                <button type="button" class="pro-compare__clear" data-compare-clear hidden>Очистить выбор</button>
            </div>

            <div class="pro-compare__scroll" data-compare-inline data-compare-default="<?php echo esc_attr((int) $atts['default']); ?>">
                <div class="pro-compare__empty">Загружаем характеристики…</div>
            </div>
    <?php if (!$bare) : ?>
        </div>
    </section>
    <?php else : ?>
        </div>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}

/**
 * ================= ОДНОЭКРАННЫЙ КАЛЬКУЛЯТОР =================
 *
 * Было: визард из 4 шагов, результат в самом конце.
 * Стало: три вопроса слева, карточка рекомендации справа — пересчёт на каждый
 * клик, цена видна не доходя до конца. Логика подбора и надбавок не изменилась
 * (см. calculator.js), поменялись разметка и порядок вопросов.
 */
function render_topas_calculator_live($atts)
{
    $people_opts = array();
    for ($i = 1; $i <= 6; $i++) {
        $people_opts[] = array('value' => (string) $i, 'label' => (string) $i);
    }
    $people_opts[] = array('value' => '7', 'label' => '7+');

    ob_start(); ?>
    <div class="calc-live" id="calc" data-calc-root aria-labelledby="calc-heading">
        <div class="calc-live__head">
            <span class="pro-eyebrow">Калькулятор подбора</span>
            <h2 class="calc-live__title" id="calc-heading">Узнайте модель и цену за 30 секунд</h2>
            <p class="calc-live__sub">
                Ответьте на три вопроса — подберём станцию под ваш дом и покажем ориентир по цене «под ключ».
                Точную смету инженер назовёт после бесплатного выезда.
            </p>
        </div>

        <form class="calc-live__grid" data-calc-form novalidate>
            <div class="calc-live__questions">

                <div class="calc-live__q">
                    <div class="calc-live__q-head">
                        <span class="calc-live__q-num">1</span>
                        <p class="calc-live__q-title">Сколько человек живёт постоянно?</p>
                    </div>
                    <div class="calc-live__opts" role="group" aria-label="Количество проживающих">
                        <?php foreach ($people_opts as $opt) : ?>
                            <button type="button" class="calc-live__opt" data-calc-opt="1" data-name="people" data-value="<?php echo esc_attr($opt['value']); ?>">
                                <?php echo esc_html($opt['label']); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="calc-live__q">
                    <div class="calc-live__q-head">
                        <span class="calc-live__q-num">2</span>
                        <p class="calc-live__q-title">Тип водоотведения</p>
                    </div>
                    <div class="calc-live__opts" role="group" aria-label="Тип водоотведения">
                        <button type="button" class="calc-live__opt calc-live__opt--wide is-active" data-calc-opt="1" data-name="disposal" data-value="gravity">
                            <span>Самотёк</span>
                            <span class="calc-live__opt-d">Отвод очищенной воды в канаву или дренаж</span>
                        </button>
                        <button type="button" class="calc-live__opt calc-live__opt--wide" data-calc-opt="1" data-name="disposal" data-value="forced">
                            <span>Принудительное</span>
                            <span class="calc-live__opt-d">С дренажным насосом — если самотёк невозможен</span>
                        </button>
                    </div>
                </div>

                <div class="calc-live__q">
                    <div class="calc-live__q-head">
                        <span class="calc-live__q-num">3</span>
                        <p class="calc-live__q-title">Глубина подводящей трубы</p>
                    </div>
                    <div class="calc-live__opts" role="group" aria-label="Глубина подводящей трубы">
                        <button type="button" class="calc-live__opt calc-live__opt--wide is-active" data-calc-opt="1" data-name="depth" data-value="standard">
                            <span>Стандарт</span>
                            <span class="calc-live__opt-d">Труба входит на глубине до ~0,6 м</span>
                        </button>
                        <button type="button" class="calc-live__opt calc-live__opt--wide" data-calc-opt="1" data-name="depth" data-value="long">
                            <span>Лонг</span>
                            <span class="calc-live__opt-d">Удлинённая горловина для глубокого входа</span>
                        </button>
                        <button type="button" class="calc-live__opt calc-live__opt--wide" data-calc-opt="1" data-name="depth" data-value="longus">
                            <span>Лонг Ус</span>
                            <span class="calc-live__opt-d">Максимально глубокий вход трубы</span>
                        </button>
                    </div>
                </div>

                <div class="calc-live__q">
                    <div class="calc-live__q-head">
                        <span class="calc-live__q-num">4</span>
                        <p class="calc-live__q-title">Высокий уровень грунтовых вод?</p>
                    </div>
                    <div class="calc-live__opts" role="group" aria-label="Уровень грунтовых вод">
                        <button type="button" class="calc-live__opt is-active" data-calc-opt="1" data-name="ugv" data-value="no">Нет</button>
                        <button type="button" class="calc-live__opt" data-calc-opt="1" data-name="ugv" data-value="yes">Да / не знаю</button>
                    </div>
                </div>

                <p class="calc-live__tip">
                    <span aria-hidden="true">💡</span>
                    <span>Не уверены в параметрах? Оставьте значения по умолчанию — инженер уточнит всё на бесплатном выезде.</span>
                </p>
            </div>

            <div class="calc-live__result" data-calc-result aria-live="polite"></div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
