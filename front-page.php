<?php
/**
 * Template Name: Front Page Premium
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

get_header();
// Контакты компании (из Carbon Fields, с запасными значениями)
$company = getCompanyContacts();

?>

    <!-- ===== CSS ПЕРЕМЕННЫЕ (runtime, не дублировать в SCSS) ===== -->
    <?php /* Стили вынесены в front-page.css (подключается в functions.php) */ ?>

    <main class="main-content">

        <section class="hero-premium">
            <?php // Фон hero лежит в теме (assets/images/hero) — раньше он тянулся
                  // с sun9-56.userapi.com по ссылке со служебными параметрами и мог
                  // отвалиться в любой момент. Сам файл подключён в front-page.css,
                  // preload — в izex_resource_hints() (задача #9). ?>
            <div class="hero-bg-image"></div>
            <div class="hero-bg-overlay"></div>

            <div class="container hero-layout">
                <div class="hero-content">

                    <?php // Строка «телефон + Заказать звонок» из hero убрана: телефон есть
                          // в шапке и в нижней панели на мобильных, а «Заказать звонок» был
                          // третьим конкурирующим действием в первом экране (задачи #7, #17). ?>

                    <!-- Заголовок -->
                    <h1 class="hero-title animate-fade-up delay-1">
                        Септики ТОПАС в Чебоксарах и Чувашии<br>
                        <span class="gradient-text">для дома и дачи без переплат!</span>
                    </h1>

                    <!-- Подзаголовок -->
                    <p class="hero-subtitle animate-fade-up delay-2">
                        Более 1000 выполненных монтажей. Бесплатная доставка по всей <?php echo $company['region']; ?>,
                        выезд инженера и официальная гарантия на оборудование и работы.
                    </p>

                    <!-- Кнопки -->
                    <div class="hero-buttons animate-fade-up delay-4">
                        <a href="#calc" class="btn-premium btn-primary">
                            <span class="btn-text">Подобрать станцию за 30 секунд</span>
                            <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <?php // Второе действие — тихой ссылкой: рядом с главной кнопкой две
                              // равнозначные кнопки спорили за внимание (см. components.css, п.5). ?>
                        <button class="btn-quiet open-modal" data-modal="engineer">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                            </svg>
                            <span>Вызвать инженера бесплатно</span>
                        </button>
                    </div>
                    <!-- Статистика -->
                    <div class="hero-stats animate-fade-up delay-3">
                        <div class="stat-item">
                            <span class="stat-number" data-count="8">8</span><span class="stat-suffix">+</span>
                            <span class="stat-label">лет опыта</span>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="1000">1000</span><span class="stat-suffix">+</span>
                            <span class="stat-label">установок</span>
                        </div>
                    </div>

                </div>

                <?php
                // Карточка ходовой станции в hero (по прототипу): даёт конкретику —
                // модель, цену и наличие — до того, как пользователь доскроллит до каталога.
                $hero_station = function_exists('izex_pro_hero_station') ? izex_pro_hero_station() : null;
                ?>
                <?php if ($hero_station) : ?>
                    <aside class="pro-herocard animate-fade-up delay-3">
                        <div class="pro-herocard__media">
                            <?php if ($hero_station['img']) : ?>
                                <img src="<?php echo esc_url($hero_station['img']); ?>"
                                     alt="<?php echo esc_attr($hero_station['title']); ?>"
                                     loading="lazy" decoding="async">
                            <?php endif; ?>
                            <span class="pro-herocard__stock">В наличии</span>
                        </div>

                        <span class="pro-herocard__eyebrow">
                            <?php echo esc_html($hero_station['people_text'] ?: 'Станция биологической очистки'); ?>
                        </span>
                        <div class="pro-herocard__name">
                            <a href="<?php echo esc_url($hero_station['url']); ?>"><?php echo esc_html($hero_station['title']); ?></a>
                        </div>

                        <?php // Крупно — цена «под ключ», мелко — из чего она складывается
                              // (задача #10): раньше в hero крупно стояла цена станции без
                              // монтажа, и её сравнивали с ценами конкурентов «под ключ». ?>
                        <?php if ($hero_station['turnkey']) : ?>
                            <div class="pro-herocard__price">
                                <span class="pro-herocard__price-label">под ключ от</span>
                                <span class="pro-herocard__price-value"><?php echo esc_html(izex_pro_money($hero_station['turnkey'])); ?></span>
                                <span class="pro-herocard__price-parts">
                                    станция <?php echo esc_html(izex_pro_money($hero_station['price'])); ?>
                                    + монтаж <?php echo esc_html(izex_pro_money($hero_station['install'])); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="pro-herocard__notes">
                            <div class="pro-herocard__note">
                                <span aria-hidden="true">✓</span>
                                <span><b>Монтаж за 1 день</b> в любой грунт и погоду</span>
                            </div>
                            <div class="pro-herocard__note">
                                <span aria-hidden="true">🛡️</span>
                                <span><b>Договор</b> и гарантия на оборудование и работы</span>
                            </div>
                        </div>
                    </aside>
                <?php endif; ?>
            </div>
        </section>

        <?php // Бегущая строка доверия убрана (задача #27): она повторяла те же шесть
              // тезисов, что и блок «Почему мы», бесконечно анимировалась и имела
              // контраст 2,25–3,62:1. Шорткод [topas_trust_marquee] остался в теме. ?>
        <?php
        // Здесь был второй SEO-блок с display:none — полная копия текста и того же
        // <h2>, что в видимом блоке внизу страницы. Скрытый текст с дублем заголовка
        // поисковикам не помогает, а выглядит как попытка накрутки, поэтому убран.
        ?>
        <?php
        // Параметры фильтра (фильтруем прямо на главной — так же, как в каталоге:
        // берём все станции и фильтруем/сортируем в PHP через carbon_get_post_meta,
        // это надёжнее meta_query и не зависит от формата хранения Carbon Fields).
        $f_capacity = isset($_GET['capacity']) ? absint($_GET['capacity']) : 0;
        $f_drainage = isset($_GET['drainage']) ? sanitize_text_field(wp_unslash($_GET['drainage'])) : '';
        $f_sort     = isset($_GET['sort']) ? sanitize_text_field(wp_unslash($_GET['sort'])) : '';
        $has_filter = ($f_capacity || $f_drainage || $f_sort !== '');

        $stations_query = new WP_Query(array(
            'post_type'      => 'stations',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ));
        $all_stations = $stations_query->posts;

        // Фильтр по пользователям: число из текста (или из названия), учитываем диапазон.
        // Раньше несовпавшие станции просто выбрасывались из выборки, и смена фильтра
        // требовала перезагрузки страницы. Теперь сервер отдаёт весь каталог, а
        // совпадение помечает у карточки — фильтрует и сортирует клиент (задача #14).
        $matches_filter = function ($p) use ($f_capacity, $f_drainage) {
            if ($f_capacity) {
                $text = (string) carbon_get_post_meta($p->ID, 'crb_people_count_text');
                preg_match_all('/\d+/', $text, $m);
                $nums = $m[0];
                if (empty($nums)) {
                    preg_match_all('/\d+/', $p->post_title, $mt);
                    $nums = $mt[0];
                }
                if (empty($nums)) {
                    return false;
                }
                $nums = array_map('intval', $nums);
                if ($f_capacity < min($nums) || $f_capacity > max($nums)) {
                    return false;
                }
            }

            // Водоотведение: основа слова «самот» / «принуд».
            if ($f_drainage) {
                $stem = (stripos($f_drainage, 'принуд') !== false) ? 'принуд' : 'самот';
                $haystack = mb_strtolower(
                    (string) carbon_get_post_meta($p->ID, 'crb_water_disposal') . ' ' .
                    (string) carbon_get_post_meta($p->ID, 'crb_mounting_dimensions')
                );
                if (mb_strpos($haystack, $stem) === false) {
                    return false;
                }
            }

            return true;
        };

        $matched_ids = array();
        foreach ($all_stations as $p) {
            if ($matches_filter($p)) {
                $matched_ids[] = (int) $p->ID;
            }
        }

        // Сортировка по цене: по умолчанию от дешёвых к дорогим, «По запросу» — в конец.
        // Цены считаем один раз в массив: внутри usort() каждое сравнение дёргало
        // carbon_get_post_meta() ещё дважды — на 22 моделях это ~180 лишних
        // обращений к метаданным на запрос (задача #25).
        $price_map = array();
        foreach ($all_stations as $p) {
            $price_map[$p->ID] = (float) (carbon_get_post_meta($p->ID, 'crb_price_topas_s') ?: carbon_get_post_meta($p->ID, 'crb_price'));
        }

        $sort_desc = ($f_sort === 'price_desc');
        usort($all_stations, function ($a, $b) use ($sort_desc, $price_map) {
            $pa = $price_map[$a->ID];
            $pb = $price_map[$b->ID];
            if ($pa <= 0 && $pb <= 0) {
                return 0;
            }
            if ($pa <= 0) {
                return 1;
            }
            if ($pb <= 0) {
                return -1;
            }
            return $sort_desc ? ($pb <=> $pa) : ($pa <=> $pb);
        });

        // Показываем весь каталог: видимую часть до «Показать все» и фильтрацию
        // берёт на себя catalog-instant.js, без JS работает атрибут ниже.
        $page_stations = $all_stations;
        ?>
        <?php // Нижний отступ вернули: дальше идёт секция сравнения, а не блок работ,
              // и CTA-картинка каталога упиралась в её край. ?>
        <section id="catalog" class="catalog-premium" data-catalog style="background: var(--bg-secondary); padding: clamp(80px, 12vw, 80px) 0 clamp(60px, 8vw, 80px);">
            <div class="container">
                <?php echo do_shortcode('[topas_calculator]'); ?>

                <!-- Заголовок -->
                <div class="section-header">
                    <span class="section-label">Каталог</span>
                    <h2 class="section-title">Выберите идеальную станцию</h2>
                    <p class="section-subtitle">Индивидуальный подбор под количество проживающих и особенности участка</p>
                </div>

                <!-- 🔷 SVG-иконки для карточек -->
                <svg class="svg-sprite" aria-hidden="true" style="display:none">
                    <symbol id="icon-people" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
                    <symbol id="icon-tool" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
                    <symbol id="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></symbol>
                </svg>

                <?php
                // Чип-фильтр — общий рендерер для главной и архива каталога
                // (см. izex_render_catalog_filter в inc/pro-blocks.php).
                izex_render_catalog_filter(array(
                    'base_url' => home_url('/'),
                    'capacity' => $f_capacity,
                    'drainage' => $f_drainage,
                    'sort'     => $f_sort,
                    'count'    => count($matched_ids),
                ));
                ?>

                <?php // Без JS фильтр всё равно работает: сервер помечает несовпавшие
                      // карточки атрибутом, а этот стиль их скрывает. С JS атрибут
                      // игнорируется — видимость считает catalog-instant.js. ?>
                <noscript>
                    <style>.pro-grid .pro-card[data-server-hidden]{display:none}</style>
                </noscript>

                <!-- Сетка карточек (вёрстка карточки — template-parts/station-card-pro.php) -->
                <div class="pro-grid" data-catalog-grid>
                    <?php foreach ($page_stations as $station_post) : ?>
                        <?php get_template_part('template-parts/station-card-pro', null, array(
                            'id'            => $station_post->ID,
                            'server_hidden' => $has_filter && !in_array((int) $station_post->ID, $matched_ids, true),
                        )); ?>
                    <?php endforeach; ?>

                    <div class="pro-empty" data-catalog-empty <?php echo !empty($matched_ids) ? 'hidden' : ''; ?>>
                        <?php echo !empty($page_stations)
                            ? 'По заданным фильтрам станции не найдены — попробуйте изменить параметры.'
                            : 'Станции пока не добавлены в каталог.'; ?>
                    </div>
                </div>

                <?php // Превью каталога — 6 карточек (было 8): выше вероятность
                      // дойти до кнопки, а не утонуть в сетке (задача #14). ?>
                <?php if (count($page_stations) > 6) : ?>
                    <div style="text-align:center;margin-top:24px">
                        <a class="pro-btn pro-btn--ghost"
                           href="<?php echo esc_url(get_post_type_archive_link('stations')); ?>"
                           data-catalog-more>Показать все модели (<?php echo count($page_stations); ?>)</a>
                    </div>
                <?php endif; ?>

                <?php // Сравнение моделей — сразу под карточками: отсюда видно, какие
                      // модели отмечены, и не нужно скроллить назад к каталогу (задача #22). ?>
                <?php echo do_shortcode('[topas_compare_inline default="3" bare="1"]'); ?>

                <?php // Фон вынесен в front-page.css: там WebP через image-set (89 КБ
                      // вместо 179 КБ jpg) с jpg-фолбэком — задача #25. ?>
                <div class="catalog-cta-premium" style="margin-top: 20px;">
                    <div class="catalog-cta-premium--block">
                        <p>Не нашли подходящую модель? <strong>Мы поставляем всю линейку ТОПАС</strong></p>
                        <a href="tel:<?php echo $company['phone_clean']; ?>" class="phone-link-premium"><?php echo $company['phone']; ?></a>
                        <span>— подберем индивидуально за 5 минут</span>
                    </div>
                </div>

            </div>
        </section>

        <?php // Смета — данные общие со /prices. Сравнение переехало в секцию
              // каталога, сразу под карточки. ?>
        <?php echo do_shortcode('[topas_estimate]'); ?>

        <?php // Преимущества оборудования: перенесены из-под hero — там они
             // отодвигали калькулятор и каталог, главное действие страницы. ?>
        <section class="luxury-features">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Преимущества септиков Топас</h2>
                    <p class="section-subtitle">Не просто оборудование, а комплексное решение для комфортной жизни</p>
                </div>
                <div class="features-grid">
                    <div class="feature-luxury">
                        <div class="feature-icon-wrapper">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                            </svg>
                        </div>
                        <h3>Простота монтажа</h3>
                        <p>Независимость от типа грунта и уровня грунтовых вод — установка в любых условиях</p>
                    </div>
                    <div class="feature-luxury">
                        <div class="feature-icon-wrapper">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="M8 11h.01M12 11h.01M16 11h.01"/>
                            </svg>
                        </div>
                        <h3>Комфорт без запахов</h3>
                        <p>Полная герметичность и биологическая очистка — никаких неприятных запахов на участке</p>
                    </div>
                    <div class="feature-luxury">
                        <div class="feature-icon-wrapper">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                            </svg>
                        </div>
                        <h3>Срок службы 50+ лет</h3>
                        <p>Прочный полипропиленовый корпус не подвержен коррозии и разрушению</p>
                    </div>
                    <div class="feature-luxury">
                        <div class="feature-icon-wrapper">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </div>
                        <h3>Экономия на обслуживании</h3>
                        <p>Не требуется вызов ассенизаторской машины — чистка 2-4 раза в год самостоятельно</p>
                    </div>
                </div>
            </div>
        </section>

        <?php // «Как мы работаем»: поднято перед блоком работ — сначала объясняем
             // процесс, потом показываем результат и отзывы. ?>
        <section class="process-premium">
            <div class="container">
                <div class="section-header">
                    <span class="section-label">Как мы работаем</span>
                    <h2 class="section-title">4 простых шага до комфортной жизни за городом без неприятных запахов и лишних хлопот</h2>
                </div>
                <div class="process-steps">
                    <div class="step-item">
                        <div class="step-number">01</div>
                        <div class="step-content"><h4>Заявка</h4><p>Оставьте заявку на сайте или позвоните нам</p></div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item">
                        <div class="step-number">02</div>
                        <div class="step-content"><h4>Выезд инженера</h4><p>Бесплатный замер участка и подбор оптимальной модели</p></div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item">
                        <div class="step-number">03</div>
                        <div class="step-content"><h4>Монтаж</h4><p>Установка и подключение за 1 день с гарантией качества</p></div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item">
                        <div class="step-number">04</div>
                        <div class="step-content"><h4>Запуск и сервис</h4><p>Пусконаладка, инструктаж и поддержка на весь срок эксплуатации</p></div>
                    </div>
                </div>
            </div>
        </section>


        <?php echo do_shortcode('[topas_works count="8"]'); ?>
        <section class="why-choose-premium">
            <div class="container">
                <div class="section-header">
                    <span class="section-label">Почему мы</span>
                    <h2 class="section-title">Преимущества работы с нами</h2>
                    <p class="section-subtitle">Доверьте ТОПАС нам: профессионализм исполнения, опыт мастеров и гарантия надёжности!</p>
                </div>

                <div class="advantages-list-premium">
                            <div class="advantage-item">
                                <div class="advantage-check">✓</div>
                                <div class="advantage-content">
                                    <h4>Бесплатный выезд инженера</h4>
                                    <p>После оформления заявки мы свяжемся с вами, чтобы договориться о бесплатной встрече на объекте — она ни к чему вас не обязывает</p>
                                </div>
                            </div>
                            <div class="advantage-item">
                                <div class="advantage-check">✓</div>
                                <div class="advantage-content">
                                    <h4>Доступные цены</h4>
                                    <p>Благодаря прямым поставкам и отлаженным процессам у нас максимально сокращены лишние расходы и прочие траты</p>
                                </div>
                            </div>
                            <div class="advantage-item">
                                <div class="advantage-check">✓</div>
                                <div class="advantage-content">
                                    <h4>Гарантия и договор</h4>
                                    <p>Мы работаем официально: перед началом работ подписываем договор, в котором закреплены все гарантии и ответственность сторон</p>
                                </div>
                            </div>
                            <div class="advantage-item">
                                <div class="advantage-check">✓</div>
                                <div class="advantage-content">
                                    <h4>Прозрачная смета</h4>
                                    <p>Работаем открыто и заранее согласовываем стоимость всех работ — никаких скрытых платежей</p>
                                </div>
                            </div>
                            <div class="advantage-item">
                                <div class="advantage-check">✓</div>
                                <div class="advantage-content">
                                    <h4>Монтаж за 1 день</h4>
                                    <p>Производим монтаж в любых типах грунта и в любую погоду — профессиональная бригада с опытом</p>
                                </div>
                            </div>
                            <div class="advantage-item">
                                <div class="advantage-check">✓</div>
                                <div class="advantage-content">
                                    <h4>Сервисное обслуживание</h4>
                                    <p>Производим гарантийное и постгарантийное обслуживание — всегда на связи и готовы помочь</p>
                                </div>
                            </div>
                        </div>

                <div class="why-choose-cta">
                    <button class="btn-premium btn-primary open-modal" data-modal="engineer">
                        <span>Вызвать инженера</span>
                        <svg class="btn-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </section>
        <section class="testimonials-premium">
            <div class="container">
                <div class="section-header">
                    <span class="section-label" style="background-color: white">Отзывы</span>
                    <h2 class="section-title">Что говорят наши клиенты</h2>
                </div>

                <?php
                // 🔷 ПРОСТОЙ ЗАПРОС БЕЗ META_QUERY
                $testimonials_args = array(
                    'post_type'      => 'reviews',
                    'posts_per_page' => 6,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );

                $testimonials_query = new WP_Query($testimonials_args);

                // 🔍 ОТЛАДКА (раскомментируйте для проверки)
                // echo '<!-- Запросов найдено: ' . $testimonials_query->found_posts . ' -->';
                // echo '<!-- SQL: ' . $testimonials_query->request . ' -->';

                if ($testimonials_query->have_posts()) :
                    ?>

                    <div class="testimonials-slider-premium">
                        <?php while ($testimonials_query->have_posts()) : $testimonials_query->the_post();

                            // 🔷 ПОЛУЧЕНИЕ ПОЛЕЙ через Carbon Fields (хранит ключи с префиксом «_»,
                            //    поэтому обычный get_post_meta возвращал пусто → «Анонимный»)
                            $cf = function ($key, $default = '') {
                                if (function_exists('carbon_get_post_meta')) {
                                    $v = carbon_get_post_meta(get_the_ID(), $key);
                                    if ($v !== null && $v !== '' && $v !== []) {
                                        return $v;
                                    }
                                }
                                return get_post_meta(get_the_ID(), $key, true) ?: $default;
                            };

                            $author = $cf('crb_review_author');
                            $position = $cf('crb_review_position');
                            $rating = $cf('crb_review_rating');
                            $avatar = $cf('crb_review_avatar');
                            // Carbon Fields хранит ID вложения — превращаем в URL
                            if (is_numeric($avatar)) {
                                $avatar = wp_get_attachment_image_url($avatar, 'thumbnail');
                            }
                            $service = $cf('crb_review_service');
                            $content = wp_trim_words(get_the_content(), 40, '...');

                            // Если Carbon Fields возвращает массив
                            if (is_array($rating) && !empty($rating[0]['rating_value'])) {
                                $rating = intval($rating[0]['rating_value']);
                            } else {
                                $rating = intval($rating) ?: 5;
                            }

                            // Инициалы для аватара
                            $initials = 'К';
                            if ($author) {
                                $name_parts = explode(' ', trim($author));
                                $first_initial = mb_strtoupper(mb_substr($name_parts[0], 0, 1));
                                $second_initial = isset($name_parts[1]) ? mb_strtoupper(mb_substr($name_parts[1], 0, 1)) : '';
                                $initials = $first_initial . $second_initial;
                            }

                            // 🔍 ОТЛАДКА КОНКРЕТНОГО ОТЗЫВА
                            // echo '<!-- Отзыв ID: ' . get_the_ID() . ', Автор: ' . $author . ' -->';
                            ?>

                            <div class="testimonial-card-premium">
                                <div class="testimonial-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star <?php echo $i > $rating ? 'empty' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </div>

                                <p class="testimonial-text">
                                    "<?php echo esc_html($content); ?>"
                                </p>

                                <div class="testimonial-author">
                                    <div class="author-avatar">
                                        <?php if ($avatar): ?>
                                            <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($author); ?>">
                                        <?php else: ?>
                                            <?php echo esc_html($initials); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="author-info">
                                        <h5><?php echo esc_html($author ?: 'Анонимный клиент'); ?></h5>
                                        <span><?php echo esc_html($service ?: $position ?: ''); ?></span>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    </div>

                    <div class="testimonials-footer">
                        <a href="<?php echo esc_url(get_post_type_archive_link('reviews')); ?>" class="btn btn--outline">
                            Все отзывы →
                        </a>
                    </div>

                <?php
                else :
                    ?>

                    <div class="testimonials-empty">
                        <p>Отзывов пока нет. Будьте первым!</p>
                        <?php
                        // 🔍 ОТЛАДКА - покажем сколько всего отзывов
                        $total_reviews = wp_count_posts('reviews');
                        echo '<!-- Всего отзывов: ' . $total_reviews->publish . ' -->';
                        ?>
                    </div>

                <?php endif; wp_reset_postdata(); ?>

            </div>
        </section>

        <?php
        // ===== FAQ (частые вопросы) =====
        // Вопросы/ответы держим в одном массиве: из него рендерим видимый
        // аккордеон И микроразметку FAQPage (schema.org) — чтобы они не расходились.
        $faq_items = array(
            array(
                'q' => 'Сколько стоит монтаж септика ТОПАС под ключ?',
                'a' => 'Стоимость зависит от модели станции, типа грунта и удалённости участка. Точную цену инженер называет после бесплатного выезда и замера. Мы заранее согласовываем смету и не добавляем скрытых платежей.',
            ),
            array(
                'q' => 'За какое время устанавливается станция?',
                'a' => 'В большинстве случаев монтаж и подключение автономной канализации ТОПАС занимают один день. Бригада выполняет земляные работы, установку, обвязку и пусконаладку, после чего проводит инструктаж по эксплуатации.',
            ),
            array(
                'q' => 'Можно ли установить ТОПАС при высоком уровне грунтовых вод?',
                'a' => 'Да. Септики ТОПАС полностью герметичны и устанавливаются в любых типах грунта, в том числе при высоком уровне грунтовых вод. Для сложных условий подбирается подходящая модификация станции и способ водоотведения (самотёк или принудительный).',
            ),
            array(
                'q' => 'Как часто нужно обслуживать септик ТОПАС?',
                'a' => 'Регламентное обслуживание проводится 2–4 раза в год: удаление избыточного ила и осмотр оборудования. Вызов ассенизаторской машины не требуется — обслуживание можно выполнять самостоятельно или доверить нашему сервису.',
            ),
            array(
                'q' => 'Какую модель ТОПАС выбрать для дома или дачи?',
                'a' => 'Модель подбирается по количеству постоянно проживающих: ТОПАС-С 4 и 5 — для дачи и небольшого дома, ТОПАС-С 6, 8 и 10 — для большого дома и коттеджа. Инженер поможет с выбором бесплатно с учётом залпового сброса и особенностей участка.',
            ),
            array(
                'q' => 'Вы работаете только в Чебоксарах?',
                'a' => 'Мы работаем в Чебоксарах, Новочебоксарске и по всей Чувашской Республике, а также в соседних регионах Поволжья. Доставка станций по Чувашии — бесплатная.',
            ),
            array(
                'q' => 'Какая гарантия на станцию и монтажные работы?',
                'a' => 'Мы работаем официально и подписываем договор, в котором закреплены гарантии. На оборудование действует заводская гарантия производителя, на выполненные монтажные работы — гарантия нашей компании. Также выполняем гарантийное и постгарантийное обслуживание.',
            ),
            array(
                'q' => 'Можно ли пользоваться септиком зимой и при сезонном проживании?',
                'a' => 'Да. Станция рассчитана на круглогодичную эксплуатацию и не боится морозов при правильном монтаже. Для дач с сезонным проживанием предусмотрен режим консервации — расскажем, как правильно подготовить станцию к зиме.',
            ),
        );
        ?>

        <section class="faq-premium">
            <div class="container">
                <div class="section-header">
                    <span class="section-label">Вопросы и ответы</span>
                    <h2 class="section-title">Частые вопросы о септиках ТОПАС</h2>
                    <p class="section-subtitle">Собрали ответы на вопросы, которые чаще всего задают при выборе, монтаже и обслуживании автономной канализации</p>
                </div>

                <div class="faq-list">
                    <?php foreach ($faq_items as $item) : ?>
                        <details class="faq-item">
                            <summary class="faq-question">
                                <span><?php echo esc_html($item['q']); ?></span>
                                <svg class="faq-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            </summary>
                            <div class="faq-answer">
                                <p><?php echo esc_html($item['a']); ?></p>
                            </div>
                        </details>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php
        $faq_schema = array(
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => array_map(function ($item) {
                return array(
                    '@type'          => 'Question',
                    'name'           => $item['q'],
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text'  => $item['a'],
                    ),
                );
            }, $faq_items),
        );
        echo '<script type="application/ld+json">' .
            wp_json_encode($faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) .
            '</script>' . "\n";
        ?>

        <section class="cta-premium" id="contacts" style="background-image: url('<?=assets('/images/cta-opt.jpg')?>');">
            <div class="cta-bg-pattern"></div>
            <div class="container">
                <div class="cta-content">
                    <h2 class="cta-title">Готовы сделать первый шаг?</h2>
                    <p class="cta-subtitle">Получите бесплатную консультацию инженера и скидку на монтаж при заказе до конца месяца</p>
                    <form class="cta-form-premium premium-contact-form" id="consultationForm" data-form-type="consultation">
                        <input type="text" name="hp_email" class="form-hp" tabindex="-1" autocomplete="off" aria-hidden="true">
                        <input type="hidden" name="action" value="premium_form_submit">
                        <input type="hidden" name="page_url" value="<?php echo esc_url($_SERVER['REQUEST_URI'] ?? ''); ?>">
                        <div class="form-group"><input type="text" name="name" placeholder="Ваше имя" required><span class="form-error"></span></div>
                        <div class="form-group"><input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required><span class="form-error"></span></div>
                        <label class="form-consent form-consent--light"><input type="checkbox" name="consent" required checked> Согласен на обработку персональных данных</label>
                        <button type="submit" class="btn-premium btn-primary btn-large">
                            <span>Получить консультацию</span>
                            <svg class="btn-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                        <p class="form-note" style="color: white">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
                    </form>
                    <div class="cta-contacts-premium">
                        <a href="tel:<?php echo $company['phone_clean']; ?>" class="contact-link">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span><?php echo $company['phone']; ?></span>
                        </a>
                        <a href="mailto:<?php echo $company['email']; ?>" class="contact-link">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22,6 -10,7L2,6"/></svg>
                            <span><?php echo $company['email']; ?></span>
                        </a>

                    </div>
                </div>
            </div>
        </section>

        <?php
        // Внутренние ссылки в SEO-тексте: раньше здесь было три абзаца без единой
        // ссылки. Осмысленные анкоры передают вес на каталог, цены и работы и дают
        // поисковику понять, о чём эти страницы.
        $link_catalog = get_post_type_archive_link('stations');
        // Ссылку на архив работ даём только когда там что-то есть (задача #5).
        $link_works   = izex_has_published_works() ? get_post_type_archive_link('works') : '';
        $prices_page  = get_page_by_path('prices');
        $link_prices  = $prices_page ? get_permalink($prices_page) : '';
        ?>
        <section class="seo-home-block">
            <div class="container">
                <h2>Продажа и монтаж септиков ТОПАС в Чебоксарах и Чувашии</h2>

                <p>
                    Компания «Сервис Септик21» занимается продажей, доставкой, монтажом
                    и обслуживанием септиков ТОПАС в Чебоксарах, Новочебоксарске
                    и по всей Чувашской Республике. Выполняем установку автономной
                    канализации под ключ для частных домов, дач, коттеджей
                    и коммерческих объектов.
                </p>

                <p>
                    В наличии популярные модели —
                    <?php if ($link_catalog) : ?>
                        <a href="<?php echo esc_url($link_catalog); ?>">ТОПАС-С 4, ТОПАС-С 5, ТОПАС-С 6, ТОПАС-С 8 и ТОПАС-С 10</a>.
                    <?php else : ?>
                        ТОПАС-С 4, ТОПАС-С 5, ТОПАС-С 6, ТОПАС-С 8 и ТОПАС-С 10.
                    <?php endif; ?>
                    Подбираем станцию под количество проживающих, тип грунта и уровень
                    грунтовых вод, монтируем в любых условиях и в любую погоду, как
                    правило, за один день. Рассчитать модель и ориентировочную стоимость
                    можно в <a href="#calc">калькуляторе подбора</a><?php if ($link_prices) : ?>,
                    а полный прайс — на странице <a href="<?php echo esc_url($link_prices); ?>">цен на септики ТОПАС</a><?php endif; ?>.
                </p>

                <p>
                    Работаем по Чувашии и регионам Поволжья. Бесплатно выезжаем
                    на участок, подбираем оборудование, рассчитываем стоимость монтажа
                    и предоставляем гарантию на все выполненные работы. Выполняем
                    сервисное обслуживание, чистку и ремонт септиков ТОПАС, а также
                    станций других производителей<?php if ($link_works) : ?> —
                    посмотрите <a href="<?php echo esc_url($link_works); ?>">примеры выполненных монтажей</a><?php endif; ?>.
                </p>
            </div>
        </section>

    </main>

    <!-- Модалки выводятся глобально в footer.php (с рабочим крестиком .modal-close-btn);
         здесь дубликаты убраны во избежание конфликта одинаковых id -->

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            try {
                var params = new URLSearchParams(window.location.search);
                if (params.has('capacity') || params.has('drainage') || params.has('stock') || params.has('sort')) {
                    var catalogSection = document.getElementById('catalog');
                    if (catalogSection) {
                        // отменяем авто-восстановление позиции и учитываем фикс-шапку
                        if ('scrollRestoration' in history) { history.scrollRestoration = 'manual'; }
                        var doScroll = function () {
                            var header = document.querySelector('.site-header-premium');
                            var offset = (header ? header.offsetHeight : 0) + 12;
                            var top = catalogSection.getBoundingClientRect().top + window.pageYOffset - offset;
                            window.scrollTo({ top: top, behavior: 'smooth' });
                        };
                        setTimeout(doScroll, 250);
                    }
                }
            } catch (e) {}

            const scrollObs = new IntersectionObserver(entries => {
                entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('animate-in'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.product-card-premium, .advantage-item, .step-item, .testimonial-card-premium').forEach(el => {
                el.style.opacity = '0'; el.style.transform = 'translateY(25px)'; el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                scrollObs.observe(el);
            });
        });
    </script>

<?php get_footer(); ?>