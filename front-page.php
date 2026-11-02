<?php
/**
 * Template Name: Front Page Premium
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

get_header();
$company = getCompanyContacts();
$trust = izex_trust();
$brands = ss_brand_summary();
$catalog_url = get_post_type_archive_link('stations');
$install_base = izex_pro_install_base();
$prices = izex_get_prices_lists();

$featured = array();
foreach (array('topas-4', 'topas-s-5', 'topas-s-6', 'topas-s-8', 'eko-grand-5', 'yunilos-astra-5') as $slug) {
    $post = get_page_by_path($slug, OBJECT, 'stations');
    if ($post && $post->post_status === 'publish') {
        $featured[] = $post->ID;
    }
}

$check = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>';
?>

<main class="ss-home">

    <section class="ss-hero">
        <div class="ss-container ss-hero__grid">
            <div class="ss-hero__text">
                <h1 class="ss-h1">Автономная канализация под&nbsp;ключ <span class="ss-hero__place">в Чебоксарах и Чувашии</span></h1>
                <p class="ss-lead">Подберём станцию под ваш дом, условия участка и бюджет. Рассчитаем оборудование, монтаж и доставку.</p>
                <div class="ss-actions">
                    <a class="ss-btn ss-btn--primary ss-btn--lg" href="#podbor">Подобрать септик</a>
                    <a class="ss-btn ss-btn--secondary ss-btn--lg" href="<?php echo esc_url($catalog_url); ?>">Смотреть каталог</a>
                </div>
                <ul class="ss-checks">
                    <li><?php echo $check; ?>Выезд инженера и смета — бесплатно</li>
                    <li><?php echo $check; ?>Стоимость фиксируем в договоре до начала работ</li>
                    <li><?php echo $check; ?>8 марок: ТОПАС, Юнилос Астра, Евролос и другие</li>
                    <?php if ($trust['since_year']) : ?>
                        <li><?php echo $check; ?>Работаем с <?php echo (int) $trust['since_year']; ?> года</li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php
            $hero = apply_filters('ss_hero_photo', array(
                'url'    => assets('/images/hero/hero-bg-1448.webp'),
                'srcset' => assets('/images/hero/hero-bg-768.webp') . ' 768w, ' . assets('/images/hero/hero-bg-1448.webp') . ' 1448w',
                'alt'    => 'Схема: частный дом, станция очистки в грунте и колодец для очищенной воды',
                'own'    => false,
            ));
            ?>
            <figure class="ss-hero__media<?php echo $hero['own'] ? '' : ' is-sample'; ?>">
                <img src="<?php echo esc_url($hero['url']); ?>"<?php if (!empty($hero['srcset'])) : ?> srcset="<?php echo esc_attr($hero['srcset']); ?>" sizes="(max-width: 1023px) 100vw, 520px"<?php endif; ?> width="1448" height="1086"
                     alt="<?php echo esc_attr($hero['alt']); ?>" fetchpriority="high" decoding="async">
                <figcaption><?php echo $hero['own'] ? 'Наш монтаж' : 'Схема: станция очистки и отвод очищенной воды'; ?></figcaption>
            </figure>
        </div>
    </section>

    <section class="ss-section ss-section--tint" id="podbor">
        <div class="ss-container ss-split">
            <div class="ss-split__intro">
                <p class="ss-eyebrow">Подбор оборудования</p>
                <h2 class="ss-h2">Подберём станцию за пару минут</h2>
                <p>Ответьте на несколько вопросов. Если что-то неизвестно — выбирайте «Не знаю», инженер уточнит на выезде.</p>
                <p class="ss-muted">Точную цену без данных об участке не называем: покажем подходящие модели, а расчёт пришлём после звонка.</p>
            </div>
            <div class="ss-panel">
                <?php echo ss_render_podbor(); ?>
            </div>
        </div>
    </section>

    <section class="ss-section" id="brands">
        <div class="ss-container">
            <div class="ss-head">
                <p class="ss-eyebrow">Производители</p>
                <h2 class="ss-h2">Работаем с восемью марками</h2>
                <p>Подбираем станцию по условиям участка, а не под одну марку. ТОПАС — основное направление: вся линейка в каталоге с ценами.</p>
            </div>
            <ul class="ss-brands">
                <?php foreach ($brands as $key => $b) :
                    $url = $b['page'] ?: add_query_arg('brand', $key, $catalog_url); ?>
                    <li class="ss-brand<?php echo $key === 'topas' ? ' ss-brand--main' : ''; ?>">
                        <a href="<?php echo esc_url($url); ?>">
                            <span class="ss-brand__name"><?php echo esc_html($b['name']); ?></span>
                            <span class="ss-brand__count">
                                <?php if ($b['count']) : ?>
                                    <?php echo (int) $b['count'] . ' ' . ss_models_word($b['count']); ?> в каталоге
                                <?php else : ?>
                                    Модели — по запросу
                                <?php endif; ?>
                            </span>
                            <span class="ss-brand__stock<?php echo $b['in_stock'] ? ' is-ok' : ''; ?>">
                                <?php echo $b['in_stock'] ? 'Есть в наличии' : 'Наличие и срок — по запросу'; ?>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <?php if ($featured) : ?>
        <section class="ss-section ss-section--tint" id="models">
            <div class="ss-container">
                <div class="ss-head ss-head--row">
                    <div>
                        <p class="ss-eyebrow">Модели</p>
                        <h2 class="ss-h2">Популярные станции для частного дома</h2>
                    </div>
                    <a class="ss-btn ss-btn--secondary" href="<?php echo esc_url($catalog_url); ?>">Весь каталог</a>
                </div>
                <div class="ss-grid ss-grid--cards">
                    <?php foreach ($featured as $id) {
                        get_template_part('template-parts/station-card-ss', null, array('id' => $id));
                    } ?>
                </div>
                <p class="ss-note">Цена оборудования — по каталогу. Монтаж «от» — стандартные работы на подготовленном участке; итог зависит от грунта, длины трасс и отвода воды.</p>
            </div>
        </section>
    <?php endif; ?>

    <section class="ss-section" id="cost">
        <div class="ss-container">
            <div class="ss-head">
                <p class="ss-eyebrow">Стоимость под ключ</p>
                <h2 class="ss-h2">Из чего складывается цена</h2>
                <p>Каждая часть — отдельной строкой в смете. Смету согласуем до начала работ.</p>
            </div>
            <ol class="ss-formula">
                <li>
                    <span class="ss-formula__label">Оборудование</span>
                    <strong><?php echo $brands['topas']['min_price'] ? 'от ' . esc_html(ss_money($brands['topas']['min_price'])) : 'по каталогу'; ?></strong>
                    <span>Станция по цене каталога. Другие марки — по запросу.</span>
                </li>
                <li>
                    <span class="ss-formula__label">Монтаж</span>
                    <strong>от <?php echo esc_html(ss_money($install_base)); ?></strong>
                    <span>Стандартный монтаж — что входит, смотрите ниже.</span>
                </li>
                <li>
                    <span class="ss-formula__label">Доставка</span>
                    <strong>по адресу</strong>
                    <span>Зависит от расстояния, указываем в смете.</span>
                </li>
                <li>
                    <span class="ss-formula__label">Дополнительные работы</span>
                    <strong>если нужны</strong>
                    <span>Определяются условиями участка.</span>
                </li>
            </ol>
            <div class="ss-cols">
                <div class="ss-box">
                    <h3 class="ss-h3">Входит в стандартный монтаж</h3>
                    <ul class="ss-list ss-list--check">
                        <?php foreach ($prices['included'] as $item) : ?>
                            <li><?php echo $check . esc_html($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="ss-box">
                    <h3 class="ss-h3">Что может изменить смету</h3>
                    <ul class="ss-list">
                        <?php foreach ($prices['extra'] as $row) : ?>
                            <li><span><?php echo esc_html($row['item']); ?></span><?php if ($row['price'] !== '') : ?> <span class="ss-muted">— <?php echo esc_html($row['price']); ?></span><?php endif; ?></li>
                        <?php endforeach; ?>
                        <li>Глубина выхода трубы из дома: для глубокой трубы нужна станция исполнения «Лонг»</li>
                        <li>Отвод воды насосом, если самотёком отвести некуда</li>
                    </ul>
                </div>
            </div>
            <div class="ss-actions ss-actions--center">
                <button type="button" class="ss-btn ss-btn--primary ss-btn--lg open-modal" data-modal="order">Получить расчёт</button>
            </div>
        </div>
    </section>

    <?php $works = ss_recent_works(3); ?>
    <?php if ($works) : ?>
        <section class="ss-section ss-section--tint" id="works">
            <div class="ss-container">
                <div class="ss-head ss-head--row">
                    <div>
                        <p class="ss-eyebrow">Выполненные работы</p>
                        <h2 class="ss-h2">Наши объекты</h2>
                    </div>
                    <a class="ss-btn ss-btn--secondary" href="<?php echo esc_url(get_post_type_archive_link('works')); ?>">Все работы</a>
                </div>
                <div class="ss-grid ss-grid--3">
                    <?php foreach ($works as $work) :
                        $model = carbon_get_post_meta($work->ID, 'crb_work_model');
                        $place = carbon_get_post_meta($work->ID, 'crb_work_location');
                        $disposal = carbon_get_post_meta($work->ID, 'crb_work_disposal');
                        $duration = carbon_get_post_meta($work->ID, 'crb_work_duration'); ?>
                        <article class="ss-work">
                            <a href="<?php echo esc_url(get_permalink($work)); ?>" class="ss-work__media">
                                <?php echo get_the_post_thumbnail($work, 'medium_large', array('loading' => 'lazy', 'alt' => '')) ?: '<span class="ss-card__noimg">Фото объекта</span>'; ?>
                            </a>
                            <div class="ss-work__body">
                                <?php if ($place) : ?><p class="ss-work__place"><?php echo esc_html($place); ?></p><?php endif; ?>
                                <h3 class="ss-h3"><a href="<?php echo esc_url(get_permalink($work)); ?>"><?php echo esc_html(get_the_title($work)); ?></a></h3>
                                <dl class="ss-specs ss-specs--compact">
                                    <?php if ($model) : ?><div><dt>Станция</dt><dd><?php echo esc_html($model); ?></dd></div><?php endif; ?>
                                    <?php if ($disposal) : ?><div><dt>Отвод воды</dt><dd><?php echo esc_html($disposal); ?></dd></div><?php endif; ?>
                                    <?php if ($duration) : ?><div><dt>Срок монтажа</dt><dd><?php echo esc_html($duration); ?></dd></div><?php endif; ?>
                                </dl>
                                <?php if (has_excerpt($work)) : ?><p class="ss-muted"><strong>Участок и работы:</strong> <?php echo esc_html(get_the_excerpt($work)); ?></p><?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="ss-section" id="process">
        <div class="ss-container">
            <div class="ss-head">
                <p class="ss-eyebrow">Как мы работаем</p>
                <h2 class="ss-h2">От заявки до обслуживания</h2>
            </div>
            <ol class="ss-steps">
                <li><span class="ss-steps__num">1</span><h3 class="ss-h3">Подбор</h3><p>По телефону или через форму: уточняем дом, число жильцов и участок, предлагаем 2–3 станции.</p></li>
                <li><span class="ss-steps__num">2</span><h3 class="ss-h3">Выезд инженера</h3><p>Бесплатно: смотрим грунт, глубину трубы, место для станции и куда отводить воду.</p></li>
                <li><span class="ss-steps__num">3</span><h3 class="ss-h3">Согласование сметы</h3><p>Оборудование, монтаж, доставка и дополнительные работы — отдельными строками. Цену фиксируем в договоре.</p></li>
                <li><span class="ss-steps__num">4</span><h3 class="ss-h3">Монтаж</h3><p>Котлован, установка, подключение трубы и электричества, пусконаладка и инструктаж.</p></li>
                <li><span class="ss-steps__num">5</span><h3 class="ss-h3">Обслуживание</h3><p>Плановое обслуживание и ремонт, в том числе станций, которые ставили не мы.</p></li>
            </ol>
        </div>
    </section>

    <section class="ss-section ss-section--tint" id="warranty">
        <div class="ss-container">
            <div class="ss-head">
                <p class="ss-eyebrow">Гарантии и обслуживание</p>
                <h2 class="ss-h2">Что гарантируем и как обратиться</h2>
            </div>
            <div class="ss-grid ss-grid--3">
                <div class="ss-box">
                    <h3 class="ss-h3">Корпус станции</h3>
                    <p class="ss-big"><?php echo $trust['warranty_body'] ? esc_html(izex_years($trust['warranty_body'])) : 'По паспорту'; ?></p>
                    <p class="ss-muted">Гарантия производителя на корпус. Срок для вашей модели указываем в договоре.</p>
                </div>
                <div class="ss-box">
                    <h3 class="ss-h3">Электрооборудование</h3>
                    <p class="ss-big"><?php echo $trust['warranty_compressor'] ? esc_html(izex_years($trust['warranty_compressor'])) : 'По паспорту'; ?></p>
                    <p class="ss-muted">Компрессоры, насосы и блок управления — по условиям производителя станции.</p>
                </div>
                <div class="ss-box">
                    <h3 class="ss-h3">Монтажные работы</h3>
                    <p class="ss-big"><?php echo $trust['warranty_works'] ? esc_html(izex_years($trust['warranty_works'])) : 'По договору'; ?></p>
                    <p class="ss-muted">Наша гарантия на монтаж закреплена в договоре, который подписываем до начала работ.</p>
                </div>
            </div>
            <div class="ss-service">
                <div>
                    <h3 class="ss-h3">Нужно обслуживание или ремонт?</h3>
                    <p>Позвоните или напишите в MAX: какая станция, что случилось. Обслуживаем станции разных марок, в том числе установленные другими компаниями.</p>
                </div>
                <div class="ss-actions">
                    <a class="ss-btn ss-btn--primary" href="tel:<?php echo esc_attr($company['phone_clean']); ?>">Позвонить</a>
                    <a class="ss-btn ss-btn--secondary" href="/obsluzhivanie-septikov-lyubyh-marok/">Об обслуживании</a>
                </div>
            </div>
        </div>
    </section>

    <?php
    $reviews = get_posts(array('post_type' => 'reviews', 'post_status' => 'publish', 'posts_per_page' => 3));
    $sources = array('yandex' => 'Яндекс Карты', '2gis' => '2ГИС', 'avito' => 'Авито');
    ?>
    <?php if ($reviews) : ?>
        <section class="ss-section" id="reviews">
            <div class="ss-container">
                <div class="ss-head ss-head--row">
                    <div>
                        <p class="ss-eyebrow">Отзывы</p>
                        <h2 class="ss-h2">Что говорят клиенты</h2>
                    </div>
                    <a class="ss-btn ss-btn--secondary" href="<?php echo esc_url(get_post_type_archive_link('reviews') ?: '/reviews/'); ?>">Все отзывы</a>
                </div>
                <?php echo do_shortcode('[topas_reviews_rating]'); ?>
                <div class="ss-grid ss-grid--3">
                    <?php foreach ($reviews as $review) :
                        $author = carbon_get_post_meta($review->ID, 'crb_review_author') ?: get_the_title($review);
                        $meta = array_filter(array(
                            carbon_get_post_meta($review->ID, 'crb_review_position'),
                            carbon_get_post_meta($review->ID, 'crb_review_service'),
                            carbon_get_post_meta($review->ID, 'crb_review_date'),
                        ));
                        $source = (string) carbon_get_post_meta($review->ID, 'crb_review_source');
                        $source_url = (string) carbon_get_post_meta($review->ID, 'crb_review_source_url');
                        $text = trim(wp_strip_all_tags($review->post_content)); ?>
                        <figure class="ss-review">
                            <blockquote>
                                <?php if (mb_strlen($text) > 260) : ?>
                                    <p><?php echo esc_html(mb_substr($text, 0, 240)); ?><span class="ss-review__tail" hidden><?php echo esc_html(mb_substr($text, 240)); ?></span>…</p>
                                    <button type="button" class="ss-link ss-review__more" data-ss-review-more>Читать полностью</button>
                                <?php else : ?>
                                    <p><?php echo esc_html($text); ?></p>
                                <?php endif; ?>
                            </blockquote>
                            <figcaption>
                                <strong><?php echo esc_html($author); ?></strong>
                                <span class="ss-muted"><?php echo esc_html(implode(' · ', $meta)); ?></span>
                                <span class="ss-review__src">
                                    <?php if ($source && $source_url) : ?>
                                        <a href="<?php echo esc_url($source_url); ?>" target="_blank" rel="noopener nofollow">Отзыв на <?php echo esc_html($sources[$source] ?? $source); ?></a>
                                    <?php else : ?>
                                        Отзыв оставлен на сайте
                                    <?php endif; ?>
                                </span>
                            </figcaption>
                        </figure>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    $faq_items = array(
        array(
            'q' => 'Как выбрать станцию для дома?',
            'a' => 'Главное — число людей, которые живут постоянно, и залповый сброс: сколько воды уходит за раз, например из ванны. Ещё важны глубина, на которой труба выходит из дома, и куда можно отвести очищенную воду. По этим данным предлагаем 2–3 станции, а инженер подтверждает выбор на участке.',
        ),
        array(
            'q' => 'Чем ТОПАС отличается от ТОПАС-С?',
            'a' => 'В станциях ТОПАС стоят два компрессора, которые работают попеременно, в ТОПАС-С — один. Производительность у моделей с одной цифрой одинаковая. Это разные модели с разной ценой: обе цены указаны в карточке станции.',
        ),
        array(
            'q' => 'Что значат «Пр», «Лонг» и «Лонг Ус» в названии?',
            'a' => '«Пр» — принудительный отвод: очищенная вода откачивается встроенным насосом, если самотёком её отвести некуда. «Лонг» — для трубы, которая входит на глубине 80–140 см, «Лонг Ус» — до 240 см. Обычное исполнение рассчитано на трубу до 80 см.',
        ),
        array(
            'q' => 'Чем различаются марки станций?',
            'a' => 'Все станции в каталоге — аэрационные, с биологической очисткой. Различаются устройством корпуса, числом и расположением компрессоров, допустимым залповым сбросом, способом отвода воды, обслуживанием и ценой. Под ваш участок сравним 2–3 варианта разных марок.',
        ),
        array(
            'q' => 'Сколько длится монтаж?',
            'a' => 'Стандартный монтаж обычно занимает один рабочий день. Если нужны длинные трассы, замена грунта или отвод воды на большое расстояние, срок указываем в смете.',
        ),
        array(
            'q' => 'Что делать, если на участке глина или близко грунтовые воды?',
            'a' => 'Это решается монтажом: песчаная подушка, засыпка, фиксация корпуса от всплытия и, при необходимости, отвод воды насосом. Что понадобится именно у вас, инженер определит на выезде и внесёт в смету.',
        ),
        array(
            'q' => 'Как обслуживать станцию?',
            'a' => 'Несколько раз в год из станции удаляют избыточный ил и проверяют оборудование — порядок указан в паспорте модели. Это можно делать самостоятельно по инструкции или заказать у нас. Обслуживаем и ремонтируем станции разных марок.',
        ),
        array(
            'q' => 'Можно ли пользоваться станцией зимой и при сезонном проживании?',
            'a' => 'Да. Станция работает круглый год. Если дом пустует зимой, станцию консервируют — расскажем, как это сделать, или выполним сами.',
        ),
        array(
            'q' => 'Какая гарантия?',
            'a' => izex_warranty_faq_answer(),
        ),
        array(
            'q' => 'Где вы работаете?',
            'a' => 'В Чебоксарах, Новочебоксарске и по всей Чувашии. Стоимость доставки зависит от расстояния и указывается в смете.',
        ),
    );
    ?>
    <section class="ss-section ss-section--tint" id="faq">
        <div class="ss-container ss-split">
            <div class="ss-split__intro">
                <p class="ss-eyebrow">Вопросы и ответы</p>
                <h2 class="ss-h2">Частые вопросы</h2>
                <p>О выборе, монтаже, обслуживании и различиях моделей. Не нашли ответ — позвоните, объясним.</p>
                <a class="ss-link" href="tel:<?php echo esc_attr($company['phone_clean']); ?>"><?php echo esc_html($company['phone']); ?></a>
            </div>
            <div class="ss-faq">
                <?php foreach ($faq_items as $item) : ?>
                    <details class="ss-faq__item">
                        <summary><?php echo esc_html($item['q']); ?></summary>
                        <p><?php echo esc_html($item['a']); ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    izex_print_jsonld(array(
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => array_map(function ($item) {
            return array(
                '@type'          => 'Question',
                'name'           => $item['q'],
                'acceptedAnswer' => array('@type' => 'Answer', 'text' => $item['a']),
            );
        }, $faq_items),
    ));
    ?>

    <section class="ss-section" id="contacts">
        <div class="ss-container ss-split">
            <div class="ss-split__intro">
                <p class="ss-eyebrow">Контакты</p>
                <h2 class="ss-h2">Получите расчёт для вашего участка</h2>
                <p>Оставьте телефон — перезвоним в рабочее время, зададим несколько вопросов и пришлём расчёт оборудования, монтажа и доставки.</p>
                <dl class="ss-contacts">
                    <div><dt>Телефон</dt><dd><a href="tel:<?php echo esc_attr($company['phone_clean']); ?>"><?php echo esc_html($company['phone']); ?></a></dd></div>
                    <div><dt>Режим работы</dt><dd><?php echo esc_html($company['work_time']); ?></dd></div>
                    <div><dt>Почта</dt><dd><a href="mailto:<?php echo antispambot($company['email']); ?>"><?php echo antispambot($company['email']); ?></a></dd></div>
                    <div><dt>Мессенджер</dt><dd><a href="https://max.ru/u/f9LHodD0cOI_AGyWf9AKcrl72RIFsKRL7vOApMiqwT37En8F81IprazW1ro">Написать в MAX</a></dd></div>
                    <div><dt>Где работаем</dt><dd>Чебоксары, Новочебоксарск и вся Чувашия<?php echo $trust['office_none'] ? ' · работаем выездом, офиса для приёма нет' : ''; ?></dd></div>
                </dl>
            </div>
            <div class="ss-panel">
                <form class="premium-contact-form ss-form" data-form-type="consultation" novalidate>
                    <input type="text" name="hp_email" class="form-hp" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <div class="form-group">
                        <label class="ss-field__label" for="contact-phone">Телефон</label>
                        <input class="ss-input" id="contact-phone" type="tel" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel" inputmode="tel" required>
                        <span class="form-error" role="alert"></span>
                    </div>
                    <div class="form-group">
                        <label class="ss-field__label" for="contact-name">Имя <span class="ss-field__opt">— необязательно</span></label>
                        <input class="ss-input" id="contact-name" type="text" name="name" autocomplete="given-name">
                        <span class="form-error" role="alert"></span>
                    </div>
                    <div class="form-group">
                        <label class="ss-field__label" for="contact-comment">Вопрос или адрес участка <span class="ss-field__opt">— необязательно</span></label>
                        <textarea class="ss-input" id="contact-comment" name="comment" rows="3"></textarea>
                    </div>
                    <?php echo ss_consent_field(); ?>
                    <button type="submit" class="ss-btn ss-btn--primary ss-btn--block ss-btn--lg">Получить расчёт</button>
                </form>
            </div>
        </div>
    </section>

    <section class="ss-section ss-seo">
        <div class="ss-container"><div class="ss-prose">
            <h2 class="ss-h3">Септики и станции очистки в Чебоксарах и Чувашии</h2>
            <p>«Сервис Септик21» подбирает, доставляет, монтирует и обслуживает автономную канализацию для частных домов, дач и коттеджей в Чебоксарах, Новочебоксарске и по всей Чувашской Республике.</p>
            <p>В <a href="<?php echo esc_url($catalog_url); ?>">каталоге</a> — вся линейка ТОПАС-С от 4 до 10 пользователей в обычном исполнении, «Лонг» и с принудительным отводом, а также станции Юнилос Астра, Эко-Гранд, Евролос, Итал Био, КИТ, Евробион и Биодевайс. Модель подбираем по числу жильцов, залповому сбросу, глубине трубы и условиям участка — <a href="/podbor-septika/">подбор септика</a> занимает пару минут.</p>
            <p>Смету на <a href="/montazh-septika-lyuboj-marki/">монтаж</a> составляем после бесплатного выезда инженера, цены станций — на странице <a href="<?php echo esc_url(izex_prices_page_url()); ?>">цен</a>. Выполняем <a href="/obsluzhivanie-septikov-lyubyh-marok/">обслуживание и ремонт</a> станций разных марок<?php if (izex_has_published_works()) : ?>, а <a href="<?php echo esc_url(get_post_type_archive_link('works')); ?>">наши работы</a> можно посмотреть на сайте<?php endif; ?>.</p>
        </div></div>
    </section>

</main>

<?php get_footer(); ?>
