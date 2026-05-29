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

        <!-- Hero Section Premium -->
        <section class="hero-premium">
            <div class="hero-bg-image" style="background-image: url('https://sun9-56.userapi.com/s/v1/ig2/kbikJlm6FecDGc7rQ2y4nLt1d_CnM77y-rFvuDia8OG9XFQWu9PPrVRs-TWwpSV223IrJLI-IwP-QDFyB20lbQCZ.jpg?quality=95&as=32x24,48x36,72x54,108x81,160x120,240x180,360x270,480x360,540x405,640x480,720x540,1080x810,1280x960,1440x1080,1448x1086&from=bu&u=dSfrTf5fBdqBF5EfR4h6Xq0IypKt2NkMsitYpkqpHv8&cs=1448x0');"></div>
            <div class="hero-bg-overlay"></div>

            <div class="container">
                <div class="hero-content">

                    <!-- Телефон + кнопка -->
                    <div class="hero-contacts-top animate-fade-up">
                        <a href="tel:<?php echo $company['phone_clean']; ?>" class="hero-phone-link">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            <span><?php echo $company['phone']; ?></span>
                        </a>
                        <button class="btn-premium btn-sm btn-outline open-modal" data-modal="callback">
                            Заказать звонок
                        </button>
                    </div>

                    <!-- Заголовок -->
                    <h1 class="hero-title animate-fade-up delay-1">
                        Автономная канализация ТОПАС<br>
                        <span class="gradient-text">для дома и дачи без переплат!</span>
                    </h1>

                    <!-- Подзаголовок -->
                    <p class="hero-subtitle animate-fade-up delay-2">
                        Станции в наличии! Бесплатная доставка по всей <?php echo $company['region']; ?>
                        и выгодная стоимость монтажа
                    </p>

                    <!-- Статистика -->
                    <div class="hero-stats animate-fade-up delay-3">
                        <div class="stat-item">
                            <span class="stat-number" data-count="8">0</span><span class="stat-suffix">+</span>
                            <span class="stat-label">лет опыта</span>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="1000">0</span><span class="stat-suffix">+</span>
                            <span class="stat-label">установок</span>
                        </div>
                    </div>

                    <!-- Кнопки -->
                    <div class="hero-buttons animate-fade-up delay-4">
                        <a href="#catalog" class="btn-premium btn-primary">
                            <span class="btn-text">Подобрать станцию</span>
                            <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <button class="btn-premium btn-outline open-modal" data-modal="engineer">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                            </svg>
                            <span>Бесплатный выезд инженера</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Luxury Features -->
        <section class="luxury-features">
            <div class="container">
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

        <?php
        // Параметры запроса
        $args = array(
            'post_type' => 'stations',
            'posts_per_page' => 8,
            'orderby' => 'menu_order',
            'order' => 'ASC',

        );
        $stations_query = new WP_Query($args);
        ?>

        <section id="catalog" class="catalog-premium" style="background: var(--bg-secondary); padding: clamp(80px, 12vw, 120px) 0;">
            <div class="container">

                <!-- Заголовок -->
                <div class="section-header">
                    <span class="section-label">Каталог</span>
                    <h2 class="section-title">Выберите идеальную станцию</h2>
                    <p class="section-subtitle">Индивидуальный подбор под количество проживающих и особенности участка</p>
                </div>

                <!-- Табы -->
                <div class="catalog-tabs">
                    <button class="tab-btn active" data-tab="all">Все модели</button>
                    <button class="tab-btn" data-tab="small">До 5 человек</button>
                    <button class="tab-btn" data-tab="medium">5-10 человек</button>
                    <button class="tab-btn" data-tab="large">10+ человек</button>
                </div>

                <!-- Сетка карточек -->
                <div class="catalog-grid-premium">
                    <?php if ($stations_query->have_posts()) : ?>
                        <?php while ($stations_query->have_posts()) : $stations_query->the_post();
                            $price = carbon_get_post_meta(get_the_ID(), 'crb_price');
                            $old_price = carbon_get_post_meta(get_the_ID(), 'crb_old_price');
                            $people = carbon_get_post_meta(get_the_ID(), 'crb_people_count_text');
                            $is_hit = carbon_get_post_meta(get_the_ID(), 'crb_is_hit');

                            $people_num = (int) preg_replace('/[^0-9]/', '', $people);
                            $category = $people_num <= 5 ? 'small' : ($people_num <= 10 ? 'medium' : 'large');
                            ?>
                            <article class="station-card-home" data-category="<?php echo esc_attr($category); ?>">
                                <?php if ($is_hit) : ?>
                                    <span class="station-badge-home">✓ Хит</span>
                                <?php endif; ?>

                                <div class="station-card-home__image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('medium_large', array('loading' => 'lazy', 'style' => 'width:100%;height:100%;object-fit:contain;padding:1rem;')); ?>
                                        <?php else : ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/no-image.jpg" loading="lazy" style="width:100%;height:100%;object-fit:contain;padding:1rem;">
                                        <?php endif; ?>
                                    </a>
                                </div>

                                <div class="station-card-home__content">
                                    <h3 class="station-card-home__title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>

                                    <?php if ($people) : ?>
                                        <p class="station-card-home__people">
                                            <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                            </svg>
                                            <?php echo esc_html($people); ?>
                                        </p>
                                    <?php endif; ?>

                                    <div class="station-card-home__price">
                                        <?php if ($old_price && $old_price > $price) : ?>
                                            <span class="price-old"><?php echo number_format($old_price, 0, '.', ' '); ?> ₽</span>
                                        <?php endif; ?>
                                        <span class="price-current">
                                    <?php echo $price ? number_format($price, 0, '.', ' ') . ' ₽' : 'По запросу'; ?>
                                </span>
                                    </div>

                                    <div class="station-card-home__actions">
                                        <a href="<?php the_permalink(); ?>" class="btn-card-home btn-outline">Подробнее</a>
                                        <button type="button" class="btn-card-home btn-green open-modal" data-modal="order" data-product="<?php the_title_attribute(); ?>">Заказать</button>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: var(--bg-primary); border-radius: var(--radius-lg);">
                            <p style="color: var(--text-secondary);">Станции пока не добавлены в каталог</p>
                        </div>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>

                <div class="catalog-cta-premium" style="background-image: url(<?=assets('/images/cta.jpg')?>;">
                    <div class="catalog-cta-premium--block">
                        <p>Не нашли подходящую модель? <strong>Мы поставляем всю линейку ТОПАС</strong></p>
                        <a href="tel:<?php echo $company['phone_clean']; ?>" class="phone-link-premium"><?php echo $company['phone']; ?></a>
                        <span>— подберем индивидуально за 5 минут</span>
                    </div>
                </div>

            </div>
        </section>

        <!-- Why Choose Us Premium -->
        <section class="why-choose-premium">
            <div class="container">
                <div class="split-layout">
                    <div class="split-image">
                        <div class="image-wrapper">
                            <img src=<?=assets('/images/topas-montazh.jpg')?>" alt="Монтаж ТОПАС">
                            <div class="image-badge">
                                <span class="badge-number">1 день</span>
                                <span class="badge-text">монтаж под ключ</span>
                            </div>
                        </div>
                    </div>
                    <div class="split-content">
                        <span class="section-label">Почему мы</span>
                        <h2 class="section-title text-left">Преимущества работы с нами</h2>
                        <p class="section-subtitle text-left">Мы предлагаем не просто оборудование, а комплексное решение для комфортной жизни</p>

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

                        <button class="btn-premium btn-primary open-modal" data-modal="engineer">
                            <span>Вызвать инженера</span>
                            <svg class="btn-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process Premium -->
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

        <!-- Testimonials Premium -->
        <section class="testimonials-premium">
            <div class="container">
                <div class="section-header">
                    <span class="section-label">Отзывы</span>
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

                            // 🔷 ПОЛУЧЕНИЕ ПОЛЕЙ - ПРОБУЕМ НЕСКОЛЬКО ВАРИАНТОВ
                            $author = get_post_meta(get_the_ID(), 'crb_review_author', true);
                            $position = get_post_meta(get_the_ID(), 'crb_review_position', true);
                            $rating = get_post_meta(get_the_ID(), 'crb_review_rating', true);
                            $avatar = get_post_meta(get_the_ID(), 'crb_review_avatar', true);
                            $service = get_post_meta(get_the_ID(), 'crb_review_service', true);
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

        <!-- CTA Premium -->
        <section class="cta-premium" id="contacts" style="background-image: url(<?=assets('/images/cta.jpg')?>;">
            <div class="cta-bg-pattern"></div>
            <div class="container">
                <div class="cta-content">
                    <h2 class="cta-title">Готовы сделать первый шаг?</h2>
                    <p class="cta-subtitle">Получите бесплатную консультацию инженера и скидку на монтаж при заказе до конца месяца</p>
                    <form class="cta-form-premium" id="consultationForm">
                        <div class="form-group"><input type="text" name="name" placeholder="Ваше имя" required></div>
                        <div class="form-group"><input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required></div>
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

    </main>

    <!-- Modals -->
    <div class="modal-premium" id="modal-callback">
        <div class="modal-backdrop"></div>
        <div class="modal-content-premium">
            <button class="modal-close" aria-label="Закрыть">&times;</button>
            <h3 class="modal-title">Заказать звонок</h3>
            <p class="modal-text" id="callbackModalText">Оставьте номер телефона, и мы перезвоним вам в течение 15 минут</p>
            <form class="modal-form" id="callbackForm">
                <input type="text" name="name" placeholder="Ваше имя" required>
                <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                <button type="submit" class="btn-premium btn-primary btn-full">Жду звонка</button>
                <p class="form-note">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
            </form>
        </div>
    </div>

    <div class="modal-premium" id="modal-engineer">
        <div class="modal-backdrop"></div>
        <div class="modal-content-premium">
            <button class="modal-close" aria-label="Закрыть">&times;</button>
            <h3 class="modal-title">Вызвать инженера</h3>
            <p class="modal-text">Бесплатный выезд специалиста для замера и консультации</p>
            <form class="modal-form" id="engineerForm">
                <input type="text" name="name" placeholder="Ваше имя" required>
                <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                <input type="text" name="address" placeholder="Адрес участка">
                <button type="submit" class="btn-premium btn-primary btn-full">Вызвать инженера</button>
                <p class="form-note">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
            </form>
        </div>
    </div>

    <div class="modal-premium" id="modal-order">
        <div class="modal-backdrop"></div>
        <div class="modal-content-premium">
            <button class="modal-close" aria-label="Закрыть">&times;</button>
            <h3 class="modal-title">Заказать станцию</h3>
            <p class="modal-text" id="orderProductModalName"></p>
            <form class="modal-form" id="orderForm">
                <input type="text" name="name" placeholder="Ваше имя" required>
                <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                <textarea name="comment" placeholder="Комментарий" rows="3"></textarea>
                <button type="submit" class="btn-premium btn-primary btn-full">Отправить заявку</button>
                <p class="form-note">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Counters
            const counters = document.querySelectorAll('.stat-number');
            const animateCounters = () => {
                counters.forEach(counter => {
                    const target = +counter.getAttribute('data-count');
                    const count = +counter.innerText;
                    if (count < target) {
                        counter.innerText = Math.ceil(count + target / 150);
                        requestAnimationFrame(animateCounters);
                    } else counter.innerText = target;
                });
            };
            const statsObserver = new IntersectionObserver(entries => {
                entries.forEach(e => { if (e.isIntersecting) { animateCounters(); statsObserver.unobserve(e.target); } });
            }, { threshold: 0.5 });
            const statsSection = document.querySelector('.hero-stats');
            if (statsSection) statsObserver.observe(statsSection);

            // Catalog tabs
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const filter = this.dataset.tab;
                    document.querySelectorAll('.station-card-home').forEach(card => {
                        const cat = card.dataset.category;
                        const show = filter === 'all' || (cat && cat.includes(filter));
                        card.style.display = show ? 'flex' : 'none';
                        if (show) setTimeout(() => { card.style.opacity='1'; card.style.transform='translateY(0)'; }, 10);
                        else { card.style.opacity='0'; card.style.transform='translateY(20px)'; }
                    });
                });
            });

            // Smooth scroll
            document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(a => {
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const headerH = document.querySelector('.site-header-premium')?.offsetHeight || 0;
                        window.scrollTo({ top: target.getBoundingClientRect().top + window.pageYOffset - headerH - 20, behavior: 'smooth' });
                    }
                });
            });

            // Modals
            const modals = document.querySelectorAll('.modal-premium');
            const openBtns = document.querySelectorAll('.open-modal');
            const closeBtns = document.querySelectorAll('.modal-close, .modal-backdrop');

            function openModal(id) {
                const modal = document.getElementById(`modal-${id}`);
                if (modal) { modal.classList.add('active'); document.body.style.overflow = 'hidden'; }
            }
            function closeModal(modal) { modal?.classList.remove('active'); document.body.style.overflow = ''; }

            openBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const modalId = this.dataset.modal, product = this.dataset.product;
                    if (modalId === 'order' && product) {
                        const el = document.getElementById('orderProductModalName');
                        if (el) el.textContent = `Вы выбрали: ${product}`;
                    }
                    if (modalId === 'callback' && product) {
                        const el = document.getElementById('callbackModalText');
                        if (el) el.innerHTML = `Оставьте заявку на <strong>${product}</strong>, и мы перезвоним в течение 15 минут`;
                    }
                    openModal(modalId);
                });
            });
            closeBtns.forEach(btn => btn.addEventListener('click', e => closeModal(e.target.closest('.modal-premium'))));
            document.addEventListener('keydown', e => { if (e.key === 'Escape') modals.forEach(m => closeModal(m)); });

            // Forms
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const btn = this.querySelector('button[type="submit"]');
                    const original = btn.innerHTML;
                    btn.disabled = true; btn.innerHTML = '<span>Отправка...</span>';
                    try {
                        // Здесь ваша логика отправки
                        console.log('Form data:', Object.fromEntries(new FormData(this)));
                        alert('Спасибо! Мы свяжемся с вами в ближайшее время.');
                        this.reset();
                        closeModal(this.closest('.modal-premium'));
                    } catch(err) { alert('Ошибка отправки. Попробуйте ещё раз.'); }
                    finally { btn.disabled = false; btn.innerHTML = original; }
                });
            });

            // Phone mask
            document.querySelectorAll('input[type="tel"]').forEach(input => {
                input.addEventListener('input', function(e) {
                    let v = e.target.value.replace(/\D/g,'');
                    if (!v) { e.target.value = ''; return; }
                    if (v[0]==='7'||v[0]==='8') v = v.slice(1);
                    let f = '+7';
                    if (v.length>0) f += ' (' + v.slice(0,3);
                    if (v.length>=3) f += ') ' + v.slice(3,6);
                    if (v.length>=6) f += '-' + v.slice(6,8);
                    if (v.length>=8) f += '-' + v.slice(8,10);
                    e.target.value = f;
                });
            });

            // Scroll animations
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