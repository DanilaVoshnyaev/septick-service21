<?php
/**
 * Шаблон каталога услуг — Список (единая схема с хедером)
 * Обновлено: описание из контента + большая картинка
 */
get_header();

$paged = get_query_var('paged') ? get_query_var('paged') : 1;

$args = array(
    'post_type'      => 'services',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
);

$services_query = new WP_Query($args);
?>

    <!-- 🎨 СТИЛИ (цвета из хедера) -->
    <style>
        :root {
            /* ===== ЦВЕТОВАЯ СХЕМА ИЗ ХЕДЕРА ===== */
            --green: #21b224;                    /* ✅ Основной акцент */
            --green-hover: #f4d03f;              /* ✅ Ховер-эффект */
            --blue: #2563eb;
            --blue-hover: #1d4ed8;
            --text: #1e293b;
            --text-muted: #64748b;
            --bg: rgba(255, 255, 255, 0.95);     /* ✅ Фон с прозрачностью */
            --card-bg: #ffffff;
            --border: rgba(0, 0, 0, 0.08);       /* ✅ Границы как в хедере */
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);  /* ✅ Анимации как в хедере */
        }

        .services-list-page {
            padding: 2rem 0;
            background: var(--bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            min-height: 60vh;
        }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }

        /* Заголовок */
        .archive-header { padding: 1.5rem 0 1rem; text-align: center; }
        .archive-title {
            font-size: 1.75rem; font-weight: 700; color: var(--text);
            margin: 0; position: relative; display: inline-block;
        }
        .archive-title::after {
            content: ''; display: block; width: 60px; height: 3px;
            background: var(--green); margin: 0.5rem auto 0; border-radius: 2px;  /* ✅ Было: var(--gold) */
        }

        /* Список */
        .services-list { list-style: none; padding: 0; margin: 2rem 0 0; display: flex; flex-direction: column; gap: 1rem; }

        /* Карточка */
        .service-list-item {
            display: grid; grid-template-columns: 200px 1fr auto; gap: 1.25rem;
            background: var(--card-bg);
            border-radius: var(--radius); padding: 1rem;
            box-shadow: var(--shadow); transition: var(--transition);
            border: 1px solid var(--border);  /* ✅ Добавлена граница */
            align-items: center;
        }
        .service-list-item:hover {
            transform: translateX(4px);
            border-color: var(--green);  /* ✅ Было: var(--gold) */
            box-shadow: 0 6px 24px rgba(33, 178, 36, 0.15);  /* ✅ Было: rgba(212, 175, 55, 0.15) */
        }

        /* Изображение — УВЕЛИЧЕНО */
        .service-list-image {
            width: 180px; height: 135px; border-radius: 8px;
            overflow: hidden; background: linear-gradient(135deg, var(--green) 0%, var(--blue) 100%);  /* ✅ Обновлено */
            flex-shrink: 0; display: flex; align-items: center; justify-content: center;
            border: 1px solid var(--border);
        }
        .service-list-image img {
            width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;
        }
        .service-list-item:hover .service-list-image img { transform: scale(1.05); }
        .service-list-placeholder { color: #fff; opacity: 0.9; }
        .service-list-placeholder .icon { width: 40px; height: 40px; }

        /* Контент */
        .service-list-content { min-width: 0; }
        .service-list-title {
            margin: 0 0 0.4rem; font-size: 1.15rem; font-weight: 600; color: var(--text);
            line-height: 1.4;
        }
        .service-list-title a {
            color: inherit; text-decoration: none; transition: color 0.2s;
        }
        .service-list-title a:hover { color: var(--green); }  /* ✅ Было: var(--blue) */

        /* Описание — 4 строки через CSS */
        .service-list-excerpt {
            margin: 0; color: var(--text-muted); font-size: 0.95rem; line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
            max-height: 6em;
        }

        /* Бейджи */
        .service-list-badges { display: flex; gap: 0.4rem; margin-bottom: 0.5rem; flex-wrap: wrap; }
        .badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.25rem 0.6rem; border-radius: 12px;
            font-size: 0.7rem; font-weight: 600; color: #fff; text-transform: uppercase;
        }
        .badge--popular { background: var(--green); }  /* ✅ Было: var(--gold) */
        .badge--new { background: var(--blue); }

        /* Цена */
        .service-list-price {
            font-size: 1.1rem; font-weight: 700; color: var(--text);
            margin-top: 0.5rem;
        }
        .price-old {
            display: block; font-size: 0.9rem; color: var(--text-muted);
            text-decoration: line-through; font-weight: 400; margin-bottom: 0.1rem;
        }

        /* Кнопка — логика хедера */
        .service-list-action { display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; }
        .btn-list {
            display: inline-flex; align-items: center; justify-content: center;
            gap: 0.4rem; padding: 0.6rem 1.2rem; border-radius: 8px;
            font-weight: 600; font-size: 0.9rem; text-decoration: none;
            transition: var(--transition); border: none; white-space: nowrap;
        }
        /* ✅ Кнопка зелёная — как в хедере */
        .btn-list.btn-gold {
            background: var(--green); color: white; border: 1px solid var(--green);
        }
        .btn-list.btn-gold:hover {
            background: #fff; color: #0f172a;  /* ✅ Логика хедера: инверсия при ховере */
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(33, 178, 36, 0.35);
        }
        /* ✅ Кнопка с обводкой — зелёная вместо синей */
        .btn-list.btn-outline {
            background: transparent; border: 2px solid var(--green); color: var(--green);
        }
        .btn-list.btn-outline:hover {
            background: var(--green); color: #fff;
        }
        .btn-arrow { transition: transform 0.2s ease; }
        .btn-list:hover .btn-arrow { transform: translateX(3px); }

        /* Пагинация */
        .pagination { margin: 2.5rem 0 1rem; }
        .pagination ul {
            display: flex; justify-content: center; gap: 0.25rem; list-style: none; padding: 0; margin: 0; flex-wrap: wrap;
        }
        .pagination li span, .pagination li a {
            display: flex; align-items: center; justify-content: center;
            min-width: 40px; height: 40px; padding: 0 0.5rem;
            border-radius: 8px; background: var(--card-bg);
            border: 1px solid var(--border); color: var(--text);
            text-decoration: none; font-weight: 500; transition: var(--transition);
        }
        .pagination li a:hover, .pagination .current span {
            background: var(--green); border-color: var(--green); color: #fff;  /* ✅ Было: color: #0f172a */
        }

        /* Нет результатов */
        .no-results {
            text-align: center; padding: 4rem 2rem; color: var(--text-muted);
            background: var(--card-bg); border-radius: var(--radius); margin: 2rem 0;
            border: 1px solid var(--border);
        }

        /* Адаптив */
        @media (max-width: 900px) {
            .service-list-item {
                grid-template-columns: 150px 1fr;
                gap: 0.75rem 1rem; padding: 0.85rem;
            }
            .service-list-image { width: 150px; height: 112px; }
        }

        @media (max-width: 768px) {
            .service-list-item {
                grid-template-columns: 120px 1fr;
                gap: 0.75rem 1rem;
            }
            .service-list-image { width: 120px; height: 90px; }
            .archive-title { font-size: 1.4rem; }
        }

        @media (max-width: 480px) {
            .service-list-item {
                grid-template-columns: 1fr;
                grid-template-areas:
                "image"
                "content"
                "action";
                text-align: center;
            }
            .service-list-image { width: 100%; height: 180px; margin: 0 auto; }
            .service-list-content { grid-area: content; }
            .service-list-action { grid-area: action; justify-self: center; flex-direction: row; }
            .service-list-badges { justify-content: center; }
            .service-list-excerpt { text-align: left; }
        }

        /* SVG */
        .icon { display: inline-block; vertical-align: middle; }
    </style>

    <!-- 🔷 SVG СПРАЙТ -->
    <svg style="display:none">
        <symbol id="icon-tool" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
        <symbol id="icon-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></symbol>
    </svg>

    <main class="services-list-page">
        <div class="container">

            <!-- Заголовок -->
            <header class="archive-header">
                <h1 class="archive-title">Все услуги</h1>
            </header>

            <!-- Список -->
            <?php if ($services_query->have_posts()) : ?>
            <ul class="services-list">
                <?php while ($services_query->have_posts()) : $services_query->the_post();

                $price = carbon_get_post_meta(get_the_ID(), 'crb_service_price');
                $old_price = carbon_get_post_meta(get_the_ID(), 'crb_service_old_price');
                $is_popular = carbon_get_post_meta(get_the_ID(), 'crb_service_is_popular');
                $is_new = carbon_get_post_meta(get_the_ID(), 'crb_service_is_new');
                $icon = carbon_get_post_meta(get_the_ID(), 'crb_service_icon');

                // ✅ Описание из основного контента, обрезанное до ~60 слов (~4 строки)
                $excerpt = wp_trim_words(get_the_content(), 60, '…');

                $image_url = $icon ?: (has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : '');
                ?>

                <li class="service-list-item">

                    <!-- Картинка -->
                    <a href="<?php the_permalink(); ?>" class="service-list-image" tabindex="-1">
                        <?php if ($image_url): ?>
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" decoding="async">  /* ✅ Было: хардкод-ссылка Яндекс */
                        <?php else: ?>
                            <div class="service-list-placeholder">
                                <svg class="icon" width="40" height="40"><use href="#icon-tool"/></svg>
                            </div>
                        <?php endif; ?>
                    </a>

                    <!-- Контент -->
                    <div class="service-list-content">
                        <?php if ($is_popular || $is_new): ?>
                            <div class="service-list-badges">
                                <?php if ($is_popular): ?>
                                    <span class="badge badge--popular">Популярное</span>
                                <?php endif; ?>
                                <?php if ($is_new): ?>
                                    <span class="badge badge--new">Новинка</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <h2 class="service-list-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>

                        <!-- ✅ Описание из контента (4 строки) -->
                        <?php if ($excerpt): ?>
                            <p class="service-list-excerpt"><?php echo esc_html($excerpt); ?></p>
                        <?php endif; ?>

                        <!-- Цена (опционально) -->
                        <?php if ($price || $old_price): ?>
                            <div class="service-list-price">
                                <?php if ($old_price && $old_price > $price): ?>
                                    <span class="price-old"><?php echo number_format($old_price, 0, '.', ' '); ?> ₽</span>
                                <?php endif; ?>
                                <?php echo $price ? number_format($price, 0, '.', ' ') . ' ₽' : ''; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Кнопка */
                            <div class="service-list-action">
                                <a href="<?php the_permalink(); ?>" class="btn-list btn-gold">
                                    Подробнее
                                    <svg class="btn-arrow" width="16" height="16"><use href="#icon-arrow"/></svg>
                                </a>
                                <?php if (current_user_can('edit_posts')): ?>
                                    <a href="<?php echo get_edit_post_link(); ?>" class="btn-list btn-outline">
                                        Редактировать
                                    </a>
                                <?php endif; ?>
                            </div>

                        </li>

                    <?php endwhile; ?>
                </ul>

                <!-- Пагинация -->
                    <?php if ($services_query->max_num_pages > 1) : ?>
                        <nav class="pagination" aria-label="Навигация">
                            <?php
                            echo paginate_links(array(
                                'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                                'format' => '?paged=%#%',
                                'current' => $paged,
                                'total' => $services_query->max_num_pages,
                                'prev_text' => '←',
                                'next_text' => '→',
                                'type' => 'list',
                                'mid_size' => 2
                            ));
                            ?>
                        </nav>
                    <?php endif; ?>

                    <?php else : ?>
                        <div class="no-results">
                            <p>Услуги пока не добавлены.</p>
                            <?php if (current_user_can('edit_posts')) : ?>
                                <a href="<?php echo admin_url('post-new.php?post_type=services'); ?>" class="btn-list btn-outline">
                                    + Добавить услугу
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php wp_reset_postdata(); ?>
        </div>
    </main>

<?php get_footer(); ?>