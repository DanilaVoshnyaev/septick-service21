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
?><!-- 🔷 SVG СПРАЙТ -->
    <svg class="svg-sprite" aria-hidden="true">
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
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" decoding="async">
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

                    <!-- Кнопка -->
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
