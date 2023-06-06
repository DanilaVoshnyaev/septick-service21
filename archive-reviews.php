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
    <style>
        :root {
            --gold: #d4af37;
            --gold-hover: #f4d03f;
            --blue: #2563eb;
            --text: #1e293b;
            --text-muted: #64748b;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --border: rgba(0,0,0,0.08);
            --shadow: 0 4px 20px rgba(0,0,0,0.08);
            --radius: 12px;
        }

        .reviews-page { padding: 2rem 0; background: var(--bg); min-height: 60vh; }
        .container { max-width: 900px; margin: 0 auto; padding: 0 1.5rem; }

        /* Заголовок */
        .reviews-header { text-align: center; margin-bottom: 2.5rem; }
        .reviews-title {
            font-size: 1.8rem; font-weight: 700; color: var(--text);
            margin: 0 0 0.5rem; position: relative; display: inline-block;
        }
        .reviews-title::after {
            content: ''; display: block; width: 60px; height: 3px;
            background: var(--gold); margin: 0.5rem auto 0; border-radius: 2px;
        }
        .reviews-subtitle { color: var(--text-muted); font-size: 1rem; margin: 0; }

        /* Список отзывов */
        .reviews-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1.25rem; }

        /* Карточка отзыва */
        .review-item {
            background: var(--card-bg); border-radius: var(--radius);
            padding: 1.5rem; box-shadow: var(--shadow);
            border-left: 4px solid var(--gold);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .review-item:hover {
            transform: translateX(4px);
            box-shadow: 0 6px 24px rgba(212, 175, 55, 0.15);
        }

        /* Шапка отзыва */
        .review-header {
            display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        /* Аватар */
        .review-avatar {
            width: 56px; height: 56px; border-radius: 50%;
            overflow: hidden; background: #f1f5f9; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .review-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .review-avatar .placeholder {
            width: 32px; height: 32px; color: var(--text-muted);
        }

        /* Инфо */
        .review-info { flex: 1; min-width: 0; }
        .review-author {
            font-size: 1.1rem; font-weight: 600; color: var(--text);
            margin: 0 0 0.2rem; display: flex; align-items: center; gap: 0.4rem;
        }
        .review-verified {
            color: #10b981; font-size: 0.9rem;
        }
        .review-position {
            color: var(--text-muted); font-size: 0.9rem; margin: 0;
        }
        .review-meta {
            display: flex; gap: 1rem; margin-top: 0.3rem; font-size: 0.85rem;
            color: var(--text-muted); flex-wrap: wrap;
        }

        /* Звёзды */
        .review-rating {
            display: flex; gap: 0.15rem; margin-left: auto;
        }
        .star {
            width: 18px; height: 18px; color: var(--gold);
        }
        .star.empty { color: #e2e8f0; }

        /* Текст отзыва */
        .review-text {
            color: var(--text); line-height: 1.7; font-size: 0.98rem;
            margin: 0; padding-top: 0.75rem;
            border-top: 1px dashed var(--border);
        }
        .review-text p { margin: 0 0 0.75rem; }
        .review-text p:last-child { margin-bottom: 0; }

        /* Услуга */
        .review-service {
            display: inline-block; background: rgba(212,175,55,0.12);
            color: var(--text); padding: 0.25rem 0.75rem;
            border-radius: 20px; font-size: 0.85rem; font-weight: 500;
            margin-top: 0.75rem;
        }

        /* Пагинация */
        .pagination { margin: 2.5rem 0 1rem; }
        .pagination ul {
            display: flex; justify-content: center; gap: 0.25rem;
            list-style: none; padding: 0; margin: 0; flex-wrap: wrap;
        }
        .pagination li span, .pagination li a {
            display: flex; align-items: center; justify-content: center;
            min-width: 40px; height: 40px; padding: 0 0.5rem;
            border-radius: 8px; background: var(--card-bg);
            border: 1px solid var(--border); color: var(--text);
            text-decoration: none; font-weight: 500;
            transition: all 0.2s;
        }
        .pagination li a:hover, .pagination .current span {
            background: var(--gold); border-color: var(--gold); color: #0f172a;
        }

        /* Нет отзывов */
        .no-reviews {
            text-align: center; padding: 4rem 2rem;
            background: var(--card-bg); border-radius: var(--radius);
            color: var(--text-muted);
        }

        /* Адаптив */
        @media (max-width: 600px) {
            .review-header { flex-direction: column; }
            .review-rating { margin-left: 0; margin-top: 0.5rem; }
            .review-item { padding: 1.25rem; }
            .reviews-title { font-size: 1.4rem; }
        }

        .icon { display: inline-block; vertical-align: middle; }
    </style>

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