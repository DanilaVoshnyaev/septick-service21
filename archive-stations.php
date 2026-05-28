<?php
/**
 * Шаблон архива станций ТОПАС — премиум (единая схема с хедером)
 */
get_header();
$selected_capacity = isset($_GET['capacity']) ? absint($_GET['capacity']) : 0;
$selected_drainage = isset($_GET['drainage']) ? sanitize_text_field(wp_unslash($_GET['drainage'])) : '';
$selected_stock = isset($_GET['stock']) ? sanitize_text_field(wp_unslash($_GET['stock'])) : '';
$selected_sort = isset($_GET['sort']) ? sanitize_text_field(wp_unslash($_GET['sort'])) : '';
?><!-- 🔷 SVG СПРАЙТ -->
    <svg class="svg-sprite" aria-hidden="true">
        <symbol id="icon-phone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></symbol>
        <symbol id="icon-people" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
        <symbol id="icon-tool" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
        <symbol id="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></symbol>
    </svg>

    <main class="stations-archive">
        <div class="container">

            <!-- ===== HERO: Заголовок + Описание ===== -->
            <section class="stations-hero">
                <h1 class="stations-hero__title">Каталог станций ТОПАС</h1>

                <div class="stations-hero__promo">
                    ⚡ Ставим сейчас или храним до даты монтажа с заморозкой текущей цены!
                </div>

                <a href="tel:+79083033282" class="stations-hero__phone">
                    <svg class="icon"><use href="#icon-phone"/></svg>
                    Получите персональное предложение: 8908 303 32 82
                </a>

                <div class="stations-hero__desc">
                    <p>Аэрационная станция глубокой очистки <strong>«ТОПАС»</strong> — проверенное временем решение премиум-класса для устройства автономной канализации. В системе очистки используются специальные микроорганизмы — анаэробные бактерии, которые питаются поступающими органическими соединениями. В результате их работы степень очистки стоков достигает <strong>98%</strong>, обеспечивая полную экологическую безопасность и отсутствие неприятных запахов. В отличие от обычных септиков, ТОПАС не требует откачки ассенизаторами.</p>

                    <p>Ассортимент станций «ТОПАС» включает множество моделей, которые различаются по мощности переработки. Цифра около названия означает максимальное количество постоянно проживающих людей, которые могут пользоваться данной системой канализации.</p>

                    <p><strong>Кроме различий в объёме перерабатываемых стоков, существует ещё три модельные опции:</strong></p>

                    <ul class="stations-hero__options">
                        <li>
                            <strong>Количество компрессоров.</strong> В стандартной модификации установлено два компрессора, которые работают попеременно. Модификации «ТОПАС-С» (версии от 4 до 12) оснащены одним компрессором — производительность и качество очистки не изменяются.
                        </li>
                        <li>
                            <strong>Способ водоотведения.</strong> Стандартная модификация — самотёком. Станции с дренажным насосом для принудительного отвода обозначаются суффиксом <strong>«Пр»</strong>.
                        </li>
                        <li>
                            <strong>Глубина входящей трубы.</strong> Базовое ограничение — до 80 см. Версии <strong>«Лонг»</strong> (80-140 см) и <strong>«Лонг Ус»</strong> (до 240 см) доступны для моделей от 5 пользователей.
                        </li>
                    </ul>
                </div>
            </section>

            <form class="catalog-filter" method="get" action="<?php echo esc_url(get_post_type_archive_link('stations')); ?>">
                <div class="catalog-filter__field">
                    <label for="station-capacity">Пользователей</label>
                    <select id="station-capacity" name="capacity">
                        <option value="">Любое количество</option>
                        <?php foreach ([4, 5, 6, 8, 10, 12] as $capacity) : ?>
                            <option value="<?php echo esc_attr($capacity); ?>" <?php selected($selected_capacity, $capacity); ?>>до <?php echo esc_html($capacity); ?> человек</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="catalog-filter__field">
                    <label for="station-drainage">Водоотведение</label>
                    <select id="station-drainage" name="drainage">
                        <option value="">Любое</option>
                        <option value="Самотёк" <?php selected($selected_drainage, 'Самотёк'); ?>>Самотёк</option>
                        <option value="Принудительное" <?php selected($selected_drainage, 'Принудительное'); ?>>Принудительное</option>
                    </select>
                </div>
                <div class="catalog-filter__field">
                    <label for="station-stock">Наличие</label>
                    <select id="station-stock" name="stock">
                        <option value="">Все</option>
                        <option value="1" <?php selected($selected_stock, '1'); ?>>В наличии</option>
                    </select>
                </div>
                <div class="catalog-filter__field">
                    <label for="station-sort">Сортировка</label>
                    <select id="station-sort" name="sort">
                        <option value="">По умолчанию</option>
                        <option value="price_asc" <?php selected($selected_sort, 'price_asc'); ?>>ТОПАС дешевле</option>
                        <option value="price_desc" <?php selected($selected_sort, 'price_desc'); ?>>ТОПАС дороже</option>
                    </select>
                </div>
                <div class="catalog-filter__actions">
                    <button class="btn-card btn-gold" type="submit">Показать</button>
                    <a class="btn-card btn-outline" href="<?php echo esc_url(get_post_type_archive_link('stations')); ?>">Сбросить</a>
                </div>
            </form>

            <!-- ===== СЕТКА СТАНЦИЙ ===== -->
            <?php
            $meta_query = [];
            if ($selected_capacity) {
                $meta_query[] = [
                    'key' => 'crb_people_count_text',
                    'value' => '(^|[^0-9])' . $selected_capacity . '([^0-9]|$)',
                    'compare' => 'REGEXP',
                ];
            }
            if ($selected_drainage) {
                $meta_query[] = [
                    'relation' => 'OR',
                    [
                        'key' => 'crb_water_disposal',
                        'value' => $selected_drainage,
                        'compare' => 'LIKE',
                    ],
                    [
                        'key' => 'crb_dimensions',
                        'value' => $selected_drainage,
                        'compare' => 'LIKE',
                    ],
                ];
            }
            if ($selected_stock === '1') {
                $meta_query[] = [
                    'key' => 'crb_in_stock',
                    'value' => ['yes', '1'],
                    'compare' => 'IN',
                ];
            }
            $args = array(
                'post_type' => 'stations',
                'posts_per_page' => 12,
                'paged' => get_query_var('paged') ?: 1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'post_status' => 'publish',
            );
            if ($meta_query) {
                $args['meta_query'] = $meta_query;
            }
            if ($selected_sort === 'price_asc' || $selected_sort === 'price_desc') {
                $args['meta_key'] = 'crb_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = $selected_sort === 'price_asc' ? 'ASC' : 'DESC';
            }
            $catalog_query = new WP_Query($args);
            ?>

            <?php if ($catalog_query->have_posts()) : ?>
                <div class="stations-grid">
                    <?php while ($catalog_query->have_posts()) : $catalog_query->the_post();

                        $price = carbon_get_post_meta(get_the_ID(), 'crb_price');
                        $price_topas_s = carbon_get_post_meta(get_the_ID(), 'crb_price_topas_s');
                        $old_price = carbon_get_post_meta(get_the_ID(), 'crb_old_price');
                        $people = carbon_get_post_meta(get_the_ID(), 'crb_people_count_text');
                        $is_hit = carbon_get_post_meta(get_the_ID(), 'crb_is_hit');
                        $in_stock = carbon_get_post_meta(get_the_ID(), 'crb_in_stock');
                        ?>

                        <article class="station-card">



                            <!-- Изображение -->
                            <div class="station-card__image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium_large', array('loading' => 'lazy', 'decoding' => 'async')); ?>
                                    <?php else : ?>
                                        <div class="station-placeholder">
                                            <svg class="icon"><use href="#icon-tool"/></svg>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </div>

                            <!-- Контент -->
                            <div class="station-card__content">
                                <h3 class="station-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>

                                <?php if ($people) : ?>
                                    <p class="station-card__people">
                                        <svg class="icon"><use href="#icon-people"/></svg>
                                        <?php echo esc_html($people); ?>
                                    </p>
                                <?php endif; ?>

                                <!-- Цена -->
                                <div class="station-card__price">
                                    <div class="station-card-home__price">
                                        <?php if ($old_price && $old_price > $price) : ?>
                                            <span class="price-old"><?php echo number_format($old_price, 0, '.', ' '); ?> ₽</span>
                                        <?php endif; ?>
                                        <span class="price-current">
                                    <?php echo $price ? number_format($price, 0, '.', ' ') . ' ₽' : 'По запросу'; ?>
                                    </div>

                                <!-- Кнопки -->
                                <div class="station-card__actions">
                                    <a href="<?php the_permalink(); ?>" class="btn-card btn-outline">Подробнее</a>
                                    <button class="btn-card btn-gold  js-open-modal">Заказать</button>
                                </div>
                            </div>
                        </article>

                    <?php endwhile; ?>
                </div>

                <!-- Пагинация -->
                <?php if ($catalog_query->max_num_pages > 1) : ?>
                    <nav class="pagination" aria-label="Навигация">
                        <?php
                        echo paginate_links(array(
                            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                            'format' => '?paged=%#%',
                            'current' => max(1, get_query_var('paged')),
                            'total' => $catalog_query->max_num_pages,
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
                    <p>Станции пока не добавлены в каталог.</p>
                </div>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>
        </div>
    </main>

<?php get_footer(); ?>
