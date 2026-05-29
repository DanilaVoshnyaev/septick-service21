<?php
/**
 * Шаблон архива отзывов — рабочий вариант
 */
get_header();

$paged = get_query_var('paged') ? get_query_var('paged') : 1;

// ✅ Простой запрос без сложной сортировки
$args = array(
    'post_type'      => 'reviews',
    'posts_per_page' => 10,
    'paged'          => $paged,
    'post_status'    => 'publish',
    'orderby'        => 'date',  // сортируем по дате
    'order'          => 'DESC',  // новые сверху
);

$reviews_query = new WP_Query($args);
?>

    <!-- 🎨 СТИЛИ -->
    <?php /* Стили вынесены в archive-reviews.css (подключается в functions.php) */ ?>

    <!-- 🔷 SVG -->
    <svg style="display:none">
        <symbol id="icon-star" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></symbol>
        <symbol id="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></symbol>
        <symbol id="icon-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></symbol>
    </svg>

    <main class="reviews-page">
        <div class="container">

            <!-- Заголовок -->
            <header class="reviews-header">
                <h1 class="reviews-title">Отзывы клиентов</h1>
                <p class="reviews-subtitle">Реальные истории наших клиентов о работе с нами</p>
            </header>

            <!-- Список -->
            <?php if ($reviews_query->have_posts()) : ?>
                <ul class="reviews-list">
                    <?php while ($reviews_query->have_posts()) : $reviews_query->the_post();

                        $author = carbon_get_post_meta(get_the_ID(), 'crb_review_author');
                        $position = carbon_get_post_meta(get_the_ID(), 'crb_review_position');
                        $rating_data = carbon_get_post_meta(get_the_ID(), 'crb_review_rating');
                        $rating = !empty($rating_data) ? intval($rating_data[0]['rating_value']) : 5;
                        $date = carbon_get_post_meta(get_the_ID(), 'crb_review_date');
                        $avatar = carbon_get_post_meta(get_the_ID(), 'crb_review_avatar');
                        $verified = carbon_get_post_meta(get_the_ID(), 'crb_review_verified');
                        $service = carbon_get_post_meta(get_the_ID(), 'crb_review_service');
                        $content = get_the_content();
                        ?>

                        <li class="review-item">
                            <div class="review-header">
                                <!-- Аватар -->
                                <div class="review-avatar">
                                    <?php if ($avatar): ?>
                                        <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($author); ?>">
                                    <?php else: ?>
                                        <svg class="placeholder" width="32" height="32"><use href="#icon-user"/></svg>
                                    <?php endif; ?>
                                </div>

                                <!-- Инфо -->
                                <div class="review-info">
                                    <h3 class="review-author">
                                        <?php echo esc_html($author ?: 'Аноним'); ?>
                                        <?php if ($verified): ?>
                                            <svg class="icon review-verified" width="16" height="16"><use href="#icon-check"/></svg>
                                        <?php endif; ?>
                                    </h3>
                                    <?php if ($position): ?>
                                        <p class="review-position"><?php echo esc_html($position); ?></p>
                                    <?php endif; ?>
                                    <div class="review-meta">
                                        <?php if ($date): ?>
                                            <span>📅 <?php echo esc_html($date); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Звёзды -->
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg class="star <?php echo $i > $rating ? 'empty' : ''; ?>" width="18" height="18">
                                            <use href="#icon-star"/>
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <!-- Текст -->
                            <?php if ($content): ?>
                                <div class="review-text">
                                    <?php the_content(); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Услуга -->
                            <?php if ($service): ?>
                                <span class="review-service">🔧 <?php echo esc_html($service); ?></span>
                            <?php endif; ?>
                        </li>

                    <?php endwhile; ?>
                </ul>

                <!-- Пагинация -->
                <?php if ($reviews_query->max_num_pages > 1) : ?>
                    <nav class="pagination" aria-label="Навигация">
                        <?php
                        echo paginate_links(array(
                            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                            'format' => '?paged=%#%',
                            'current' => $paged,
                            'total' => $reviews_query->max_num_pages,
                            'prev_text' => '←',
                            'next_text' => '→',
                            'type' => 'list',
                            'mid_size' => 2
                        ));
                        ?>
                    </nav>
                <?php endif; ?>

            <?php else : ?>
                <div class="no-reviews">
                    <p>Отзывов пока нет. Будьте первым!</p>
                </div>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>
        </div>
    </main>

<?php get_footer(); ?>