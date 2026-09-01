<?php
/**
 * ================= БЛОКИ ДОВЕРИЯ (спринт 2) =================
 *
 * Гарантия в цифрах (#11), скидка или фиксация сметы (#16), шесть возражений
 * (#19), объединённые преимущества (#23) и строка доверия в первом экране.
 *
 * Все факты — сроки гарантии, скидка, тариф, имя инженера — берутся из настроек
 * темы («Доверие и гарантия», см. inc/carbon-fields.php). Правило одно: пока
 * факта нет, соответствующий пункт или блок не выводится. Пустая плашка
 * «гарантия — лет» вредит сильнее, чем её отсутствие, поэтому здесь нет ни
 * одного значения по умолчанию, которое можно принять за обещание компании.
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

/**
 * Факты о компании из настроек темы.
 *
 * Значения нормализованы: числа приведены к int, пустые строки — к ''.
 * Считается один раз за запрос.
 *
 * @return array<string,mixed>
 */
function izex_trust()
{
    static $trust = null;
    if ($trust !== null) {
        return $trust;
    }

    $text = function ($key) {
        return trim((string) carbon_get_theme_option($key));
    };
    $int = function ($key) use ($text) {
        $val = (int) preg_replace('/[^\d]/', '', $text($key));
        return $val > 0 ? $val : 0;
    };
    $file = function ($key) {
        $id = carbon_get_theme_option($key);
        if (!$id) {
            return '';
        }
        // Carbon Fields может хранить id вложения или прямой URL.
        return is_numeric($id) ? (string) wp_get_attachment_url((int) $id) : (string) $id;
    };

    $trust = array(
        'since_year'      => $int('crb_since_year'),
        'installs'        => $text('crb_installs_count'),
        'crews'           => $text('crb_crews_count'),

        'warranty_body'       => $int('crb_warranty_body_years'),
        'warranty_works'      => $int('crb_warranty_works_years'),
        'warranty_compressor' => $int('crb_warranty_compressor_years'),
        'warranty_visit'      => $int('crb_warranty_visit_hours'),
        'contract_pdf'        => $file('crb_contract_sample'),
        'dealer_cert'         => $file('crb_dealer_cert'),

        'discount_mode'   => $text('crb_discount_mode') ?: 'none',
        'discount_amount' => $int('crb_discount_amount'),
        'discount_until'  => $text('crb_discount_until'),

        'tariff'          => (float) str_replace(',', '.', $text('crb_electricity_tariff')),
        'sewage_price'    => $int('crb_sewage_price'),
        'sewage_times'    => $text('crb_sewage_times'),

        'topas_vs_s'      => $text('crb_topas_vs_topas_s'),
        'winter_soil'     => $text('crb_winter_frozen_soil'),
        'winter_conserv'  => $text('crb_winter_conservation'),
        'ugv_fixation'    => $text('crb_ugv_fixation'),

        'engineer_name'   => $text('crb_engineer_name'),
        'engineer_years'  => $int('crb_engineer_years'),

        'office_address'  => $text('crb_office_address'),
        'office_none'     => (bool) carbon_get_theme_option('crb_office_no_reception'),

        'reviews_rating'  => $text('crb_reviews_rating'),
        'reviews_count'   => $int('crb_reviews_count'),
        'reviews_yandex'  => $text('crb_reviews_url_yandex'),
        'reviews_2gis'    => $text('crb_reviews_url_2gis'),
        'reviews_avito'   => $text('crb_reviews_url_avito'),
    );

    return $trust;
}

/**
 * Склонение слова после числа: 5 лет, 3 года, 1 год.
 */
function izex_plural($number, array $forms)
{
    $n = abs((int) $number) % 100;
    $n1 = $n % 10;
    if ($n > 10 && $n < 20) {
        return $forms[2];
    }
    if ($n1 > 1 && $n1 < 5) {
        return $forms[1];
    }
    if ($n1 === 1) {
        return $forms[0];
    }
    return $forms[2];
}

/**
 * Первая буква заглавной. ucfirst() кириллицу не берёт: он однобайтовый.
 */
function izex_ucfirst($text)
{
    return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
}

function izex_years($n)
{
    return $n . ' ' . izex_plural($n, array('год', 'года', 'лет'));
}

function izex_hours($n)
{
    return $n . ' ' . izex_plural($n, array('час', 'часа', 'часов'));
}

/**
 * Дата скидки по-русски: 2026-09-30 → «30 сентября».
 */
function izex_discount_date($raw)
{
    $ts = strtotime($raw);
    if (!$ts) {
        return '';
    }
    $months = array(
        1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
        'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря',
    );
    return (int) date('j', $ts) . ' ' . $months[(int) date('n', $ts)];
}

/**
 * ================= СКИДКА / ФИКСАЦИЯ СМЕТЫ (#16) =================
 *
 * Три состояния из настроек:
 *   sum  — «−7 000 ₽ на монтаж при подписании договора до 30 сентября»;
 *   soft — сначала бесплатный выезд, скидка следом;
 *   none — обещание, которое в этой нише сильнее скидки: смета фиксируется
 *          в договоре и не меняется в процессе.
 *
 * Просроченную дату не показываем: скидка «до 30 июня» в сентябре читается как
 * заброшенный сайт. В этом случае откатываемся на вариант none.
 *
 * @return array{title:string,text:string}|null
 */
function izex_discount_offer()
{
    $t = izex_trust();
    $mode = $t['discount_mode'];
    $date = izex_discount_date($t['discount_until']);
    $expired = $t['discount_until'] && strtotime($t['discount_until']) < strtotime('today');
    $money = $t['discount_amount'] ? number_format($t['discount_amount'], 0, '.', ' ') . ' ₽' : '';

    if ($mode !== 'none' && (!$money || !$date || $expired)) {
        $mode = 'none';
    }

    if ($mode === 'sum') {
        return array(
            'title' => '−' . $money . ' на монтаж',
            'text'  => 'при подписании договора до ' . $date . '.',
        );
    }

    if ($mode === 'soft') {
        return array(
            'title' => 'Выезд инженера и смета — бесплатно',
            'text'  => 'и ни к чему не обязывают. При договоре до ' . $date .
                ' монтаж дешевле на ' . $money . '.',
        );
    }

    return array(
        'title' => 'Смета фиксируется в договоре',
        'text'  => 'Цена не изменится в процессе — даже если грунт окажется тяжелее, '
            . 'чем мы рассчитывали.',
    );
}

add_shortcode('topas_offer', 'render_topas_offer');
function render_topas_offer($atts)
{
    $offer = izex_discount_offer();
    if (!$offer) {
        return '';
    }

    ob_start(); ?>
    <div class="trust-offer">
        <span class="trust-offer__title"><?php echo esc_html($offer['title']); ?></span>
        <span class="trust-offer__text"><?php echo esc_html($offer['text']); ?></span>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * ================= СТРОКА ДОВЕРИЯ В ПЕРВОМ ЭКРАНЕ (#11) =================
 *
 * Под кнопками hero: монтажи, год начала работы, смета в договоре, гарантия.
 * Пункты без данных выпадают, поэтому строка не бывает недописанной.
 */
add_shortcode('topas_trust_line', 'render_topas_trust_line');
function render_topas_trust_line($atts)
{
    $t = izex_trust();
    $parts = array();

    if ($t['installs']) {
        $parts[] = $t['installs'] . ' монтажей';
    }
    if ($t['since_year']) {
        $parts[] = 'официальный дилер с ' . $t['since_year'] . ' года';
    }
    $parts[] = 'смета в договоре';
    if ($t['warranty_works']) {
        $parts[] = 'гарантия ' . izex_years($t['warranty_works']) . ' на монтаж';
    }

    if (count($parts) < 2) {
        return '';
    }

    ob_start(); ?>
    <p class="hero-trust-line"><?php echo esc_html(implode(' · ', $parts)); ?></p>
    <?php
    return ob_get_clean();
}

/**
 * ================= ГАРАНТИЯ В ЦИФРАХ (#11) =================
 *
 * Четыре плашки: корпус, монтажные работы, компрессор, срок выезда по гарантии.
 * Каждая отвечает на невысказанное «а что если». Плашка про компрессор снимает
 * главный страх — «сломается, и придётся всё раскапывать».
 *
 * Блок целиком не выводится, пока не заполнен ни один срок: слово «гарантия»
 * без числа и есть та самая проблема, которую блок решает.
 */
add_shortcode('topas_warranty', 'render_topas_warranty');
function render_topas_warranty($atts)
{
    $t = izex_trust();

    $cards = array();
    if ($t['warranty_body']) {
        $cards[] = array(
            'title' => izex_years($t['warranty_body']) . ' на корпус станции',
            'text'  => 'Полипропилен толщиной 1,5 см: не ржавеет, не разрушается '
                . 'и не требует замены.',
        );
    }
    if ($t['warranty_works']) {
        $cards[] = array(
            'title' => izex_years($t['warranty_works']) . ' на монтажные работы',
            'text'  => 'Всё, что сделали мы: котлован, обвязка, врезка трубы, отвод, '
                . 'пусконаладка.',
        );
    }
    if ($t['warranty_compressor']) {
        $cards[] = array(
            'title' => izex_years($t['warranty_compressor']) . ' на компрессоры',
            'text'  => 'Расходная часть. Меняется сверху, без вскрытия котлована '
                . 'и земляных работ.',
        );
    }
    if ($t['warranty_visit']) {
        $cards[] = array(
            'title' => 'Выезд по гарантии — за ' . izex_hours($t['warranty_visit']),
            'text'  => 'Приезжаем и разбираемся сами, без переадресации на завод.',
        );
    }

    if (!$cards) {
        return '';
    }

    ob_start(); ?>
    <section class="trust-warranty" id="warranty">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Гарантия и договор</span>
                <h2 class="section-title">Что мы гарантируем — в цифрах и в договоре</h2>
                <p class="section-subtitle">
                    Не «официальная гарантия», а конкретные сроки, которые записаны
                    в договоре до начала работ.
                </p>
            </div>

            <div class="trust-warranty__grid">
                <?php foreach ($cards as $i => $card) : ?>
                    <div class="trust-warranty__card">
                        <span class="trust-warranty__num"><?php echo (int) ($i + 1); ?></span>
                        <h3 class="trust-warranty__title"><?php echo esc_html($card['title']); ?></h3>
                        <p class="trust-warranty__text"><?php echo esc_html($card['text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <p class="trust-warranty__foot">
                Гарантия, сроки и ответственность сторон закреплены в договоре, который
                подписываем до начала работ.
                <?php if ($t['contract_pdf']) : ?>
                    <a href="<?php echo esc_url($t['contract_pdf']); ?>" target="_blank" rel="noopener">
                        Посмотреть образец договора — PDF
                    </a>
                <?php endif; ?>
            </p>

            <?php echo do_shortcode('[topas_offer]'); ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

/**
 * ================= ШЕСТЬ ВОЗРАЖЕНИЙ (#19) =================
 *
 * Вопросы словами клиента, ответ в 2–3 строки. Стоит после отзывов и перед FAQ:
 * FAQ начинается после ~9 500 px прокрутки, а эти вопросы нужно снять раньше,
 * чем человек посмотрит на цену.
 *
 * Уточнения из настроек (мёрзлый грунт, консервация, фиксация при УГВ,
 * разница ТОПАС / ТОПАС-С) подставляются, если заполнены; без них остаётся
 * корректный текст без дырок.
 */
function izex_objection_cards()
{
    $t = izex_trust();
    $cards = array();

    // 1. Зима и сезонное проживание.
    $winter = 'Работает круглый год. Станция стоит ниже глубины промерзания, а внутри '
        . 'идёт биологический процесс, который сам себя подогревает — вода в ней не '
        . 'замерзает даже в морозы.';
    if ($t['winter_soil']) {
        $winter .= ' Монтируем и зимой: ' . $t['winter_soil'] . '.';
    }
    if ($t['winter_conserv']) {
        $winter .= ' Если живёте в доме сезонно — на зиму станцию консервируем: '
            . $t['winter_conserv'] . '. Инструктаж входит в пусконаладку.';
    }
    $cards[] = array('q' => 'А зимой это вообще работает?', 'a' => $winter);

    // 2. Глина и высокий УГВ.
    $ugv = 'Это самый частый случай в Чувашии, и он решается на монтаже, а не выбором '
        . 'модели. Корпус ставим на песчаную подушку, обсыпаем песком и фиксируем '
        . 'от всплытия';
    $ugv .= $t['ugv_fixation'] ? ' — ' . $t['ugv_fixation'] . '.' : '.';
    $ugv .= ' Что понадобится на вашем участке, инженер определит на бесплатном '
        . 'выезде: это видно по первым же полуметрам грунта.';
    $cards[] = array('q' => 'У меня глина и вода близко к поверхности', 'a' => $ugv);

    // 3. Стоимость владения. Арифметику показываем: 1–1,5 кВт·ч/сут — это
    //    30–45 кВт·ч в месяц, дальше умножаем на тариф из настроек.
    $cost = 'Электричество: 1–1,5 кВт·ч в сутки — это 30–45 кВт·ч в месяц';
    if ($t['tariff'] > 0) {
        $low = (int) round(30 * $t['tariff']);
        $high = (int) round(45 * $t['tariff']);
        $tariff_str = rtrim(rtrim(number_format($t['tariff'], 2, ',', ' '), '0'), ',');
        $cost .= ', при тарифе ' . $tariff_str . ' ₽ выходит ' . $low . '–' . $high . ' ₽ в месяц';
    }
    $cost .= '. Обслуживание: чистка 2–4 раза в год, своими руками, по инструкции — '
        . 'платить за это не нужно. Ассенизатор не нужен вообще';
    if ($t['sewage_price'] && $t['sewage_times']) {
        $cost .= ': для выгребной ямы это ' . number_format($t['sewage_price'], 0, '.', ' ')
            . ' ₽ примерно ' . $t['sewage_times'] . ' раз в год';
    }
    $cost .= '.';
    $cards[] = array('q' => 'Сколько это стоит в месяц?', 'a' => $cost);

    // 4. Что если сломается.
    $repair = 'Мы.';
    $terms = array();
    if ($t['warranty_works']) {
        $terms[] = 'гарантия ' . izex_years($t['warranty_works']) . ' на монтаж';
    }
    if ($t['warranty_body']) {
        $terms[] = izex_years($t['warranty_body']) . ' на корпус';
    }
    if ($t['warranty_visit']) {
        $terms[] = 'выезд по гарантии за ' . izex_hours($t['warranty_visit']);
    }
    if ($terms) {
        $repair .= ' ' . izex_ucfirst(implode(', ', $terms)) . '.';
    } else {
        // Без сроков в настройках «Мы.» звучит обрубленно — даём корректную
        // формулировку без выдуманных чисел.
        $repair .= ' Гарантия на монтаж и на корпус закреплена в договоре.';
    }
    $repair .= ' После гарантии обслуживаем и ремонтируем тоже сами — в том числе '
        . 'станции, которые ставили не мы, и других производителей. Компрессор — '
        . 'расходная часть, меняется сверху, без вскрытия котлована.';
    $cards[] = array('q' => 'А если сломается — кто будет чинить?', 'a' => $repair);

    // 5. Отказ после выезда. Фраза про прозвон снимает страх, который держит
    //    от заявки не меньше цены, и её почти никто не проговаривает.
    $cards[] = array(
        'q' => 'А что если я передумаю после приезда инженера?',
        'a' => 'Ничего. Выезд, замер, подбор модели и смета — бесплатно и ни к чему '
            . 'не обязывают. Никакого договора на выезде не подписывается: вы получаете '
            . 'расчёт и решаете спокойно. Мы не звоним потом каждую неделю.',
    );

    // 6. ТОПАС против ТОПАС-С. Без объяснения человек уходит гуглить прямо
    //    с таблицы сравнения, где стоят два ценовых ряда.
    $diff = $t['topas_vs_s'] ? rtrim($t['topas_vs_s'], '.') . '.' : '';
    $diff .= ($diff ? ' ' : '') . 'Разница в цене — от 9 000 до 14 000 ₽ в зависимости '
        . 'от модели. Если сомневаетесь, инженер на выезде скажет, есть ли смысл '
        . 'доплачивать именно на вашем участке.';
    $cards[] = array('q' => 'Чем ТОПАС отличается от ТОПАС-С?', 'a' => $diff);

    return $cards;
}

add_shortcode('topas_objections', 'render_topas_objections');
function render_topas_objections($atts)
{
    $cards = izex_objection_cards();

    ob_start(); ?>
    <section class="trust-faq-pre" id="objections">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Перед решением</span>
                <h2 class="section-title">Что обычно спрашивают перед тем, как решиться</h2>
            </div>

            <div class="trust-faq-pre__grid">
                <?php // Карточки текстовые: иллюстрации пробовали, но они перебивали
                      // сам ответ — вопрос и текст читаются лучше без картинки. ?>
                <?php foreach ($cards as $card) : ?>
                    <div class="trust-faq-pre__card">
                        <h3 class="trust-faq-pre__q">«<?php echo esc_html($card['q']); ?>»</h3>
                        <p class="trust-faq-pre__a"><?php echo esc_html($card['a']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

/**
 * ================= ШЕСТЬ ПРЕИМУЩЕСТВ ВМЕСТО ДЕСЯТИ (#23) =================
 *
 * Было два блока подряд — «Преимущества септиков Топас» (4 карточки) и
 * «Преимущества работы с нами» (6 карточек): десять галочек без единого числа.
 * Здесь шесть пунктов, каждый с цифрой или проверяемым фактом. Пункты, для
 * которых нет данных, выпадают — блок остаётся связным.
 */
add_shortcode('topas_advantages', 'render_topas_advantages');
function render_topas_advantages($atts)
{
    $t = izex_trust();
    $items = array();

    $crews = $t['crews']
        ? $t['crews'] . ' — на объекте работают те же люди, что приезжали на замер.'
        : 'На объекте работают те же люди, что приезжали на замер.';
    $items[] = array(
        'title' => 'Монтаж за один день',
        'text'  => 'В любой грунт и в любую погоду. ' . $crews,
    );

    if ($t['since_year']) {
        $dealer = 'Возим напрямую с завода, без наценки посредника.';
        $items[] = array(
            'title' => 'Официальный дилер ТОПАС с ' . $t['since_year'] . ' года',
            'text'  => $dealer,
            'link'  => $t['dealer_cert'] ? array('url' => $t['dealer_cert'], 'label' => 'Сертификат дилера') : null,
        );
    }

    $items[] = array(
        'title' => 'Смета в договоре до начала работ',
        'text'  => 'Цена не меняется в процессе. Что входит и что оплачивается отдельно — '
            . 'расписано до подписания, а не выясняется на объекте.',
        'link'  => $t['contract_pdf'] ? array('url' => $t['contract_pdf'], 'label' => 'Образец договора') : null,
    );

    if ($t['warranty_works'] || $t['warranty_body']) {
        $parts = array();
        if ($t['warranty_body']) {
            $parts[] = izex_years($t['warranty_body']) . ' на корпус станции';
        }
        if ($t['warranty_visit']) {
            $parts[] = 'выезд по гарантии — за ' . izex_hours($t['warranty_visit']);
        }
        $items[] = array(
            'title' => $t['warranty_works']
                ? 'Гарантия ' . izex_years($t['warranty_works']) . ' на монтаж'
                : 'Гарантия ' . izex_years($t['warranty_body']) . ' на корпус',
            'text'  => $parts ? izex_ucfirst(implode(', ', $parts)) . '.' : '',
        );
    }

    $engineer = $t['engineer_name']
        ? 'На замер приедет ' . $t['engineer_name']
            . ($t['engineer_years'] ? ', ' . izex_years($t['engineer_years']) . ' ставит ТОПАС' : '') . '. '
        : '';
    $items[] = array(
        'title' => 'Бесплатный выезд инженера',
        'text'  => $engineer . 'Замер, подбор модели и расчёт сметы — бесплатно '
            . 'и ни к чему не обязывают.',
    );

    $items[] = array(
        'title' => 'Сервис на весь срок эксплуатации',
        'text'  => 'Гарантийное и постгарантийное обслуживание, чистка и ремонт — '
            . 'в том числе станций других производителей.',
    );

    ob_start(); ?>
    <section class="why-choose-premium" id="why-us">
        <div class="container">
            <div class="section-header">
                <span class="section-label">Почему мы</span>
                <h2 class="section-title">Почему монтаж стоит доверить нам</h2>
                <p class="section-subtitle">
                    <?php echo count($items) === 6 ? 'Шесть причин' : 'Причины'; ?>, каждую можно проверить.
                </p>
            </div>

            <div class="advantages-list-premium">
                <?php foreach ($items as $item) : ?>
                    <div class="advantage-item">
                        <div class="advantage-check" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        </div>
                        <div class="advantage-content">
                            <h4><?php echo esc_html($item['title']); ?></h4>
                            <?php if (!empty($item['text'])) : ?>
                                <p>
                                    <?php echo esc_html($item['text']); ?>
                                    <?php if (!empty($item['link'])) : ?>
                                        <a href="<?php echo esc_url($item['link']['url']); ?>" target="_blank" rel="noopener">
                                            <?php echo esc_html($item['link']['label']); ?>
                                        </a>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="why-choose-cta">
                <button class="btn-premium btn-primary open-modal" data-modal="engineer">
                    <span>Вызвать инженера бесплатно</span>
                    <svg class="btn-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

/**
 * Ответ FAQ про гарантию (#11, #24).
 *
 * «Мы работаем официально и подписываем договор» — фраза, которую можно без
 * изменений поставить на сайт конкурента. Здесь она заменена сроками из
 * настроек; пока сроков нет, остаётся корректный текст без выдуманных чисел.
 *
 * @return string
 */
function izex_warranty_faq_answer()
{
    $t = izex_trust();
    $parts = array();

    if ($t['warranty_body']) {
        $parts[] = izex_years($t['warranty_body']) . ' на корпус станции';
    }
    if ($t['warranty_works']) {
        $parts[] = izex_years($t['warranty_works']) . ' на монтажные работы';
    }
    if ($t['warranty_compressor']) {
        $parts[] = izex_years($t['warranty_compressor']) . ' на компрессоры';
    }

    if (!$parts) {
        return 'Гарантия на оборудование и на монтажные работы закреплена в договоре, '
            . 'который подписываем до начала работ. Выполняем гарантийное '
            . 'и постгарантийное обслуживание, в том числе станций других производителей.';
    }

    $answer = 'Гарантия: ' . implode(', ', $parts) . '.';
    if ($t['warranty_visit']) {
        $answer .= ' Выезд по гарантии — за ' . izex_hours($t['warranty_visit'])
            . ', разбираемся сами, без переадресации на завод.';
    }
    $answer .= ' Все сроки закреплены в договоре, который подписываем до начала работ. '
        . 'После гарантии обслуживаем и ремонтируем тоже сами — в том числе станции '
        . 'других производителей.';

    return $answer;
}

/**
 * ================= РЕЙТИНГ ВО ВНЕШНИХ СПРАВОЧНИКАХ (#12) =================
 *
 * Строка над блоком отзывов: «4,9 на Яндекс.Картах · 37 отзывов» со ссылкой на
 * карточку компании. Без данных не выводится — придумывать рейтинг нельзя.
 */
add_shortcode('topas_reviews_rating', 'render_topas_reviews_rating');
function render_topas_reviews_rating($atts)
{
    $t = izex_trust();

    $sources = array_filter(array(
        'Яндекс.Картах' => $t['reviews_yandex'],
        '2ГИС'          => $t['reviews_2gis'],
        'Авито'         => $t['reviews_avito'],
    ));

    if (!$sources && !$t['reviews_rating']) {
        return '';
    }

    $primary_label = key($sources);
    $primary_url = $sources ? reset($sources) : '';

    ob_start(); ?>
    <p class="reviews-aggregate">
        <?php if ($t['reviews_rating'] && $primary_url) : ?>
            <a href="<?php echo esc_url($primary_url); ?>" target="_blank" rel="noopener nofollow">
                <b><?php echo esc_html($t['reviews_rating']); ?></b>
                на <?php echo esc_html($primary_label); ?>
                <?php if ($t['reviews_count']) : ?>
                    · <?php echo esc_html($t['reviews_count']); ?> отзывов
                <?php endif; ?>
            </a>
        <?php elseif ($t['reviews_rating']) : ?>
            <b><?php echo esc_html($t['reviews_rating']); ?></b> — средняя оценка клиентов
        <?php endif; ?>

        <?php
        // Остальные справочники — ссылками рядом: чем больше независимых
        // источников, тем меньше вопросов к отзывам на самом сайте.
        $rest = $sources;
        if ($primary_label !== null) {
            unset($rest[$primary_label]);
        }
        ?>
        <?php foreach ($rest as $label => $url) : ?>
            <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener nofollow">
                <?php echo esc_html($label === 'Яндекс.Картах' ? 'Яндекс.Карты' : $label); ?>
            </a>
        <?php endforeach; ?>
    </p>
    <?php
    return ob_get_clean();
}

/**
 * Текст подтверждения отправленной заявки (#34).
 *
 * @return string
 */
function izex_lead_success_message()
{
    $t = izex_trust();

    if ($t['engineer_name']) {
        return 'Спасибо! Свяжемся с вами в течение 15 минут. На замер приедет '
            . $t['engineer_name']
            . ($t['engineer_years'] ? ', ' . izex_years($t['engineer_years']) . ' ставит ТОПАС' : '')
            . '.';
    }

    return 'Спасибо! Мы свяжемся с вами в течение 15 минут.';
}
