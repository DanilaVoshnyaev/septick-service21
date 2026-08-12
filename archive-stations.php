<?php
/**
 * Шаблон архива станций ТОПАС — премиум (единая схема с хедером)
 */
get_header();
$selected_capacity = isset($_GET['capacity']) ? absint($_GET['capacity']) : 0;
$selected_drainage = isset($_GET['drainage']) ? sanitize_text_field(wp_unslash($_GET['drainage'])) : '';
$selected_stock = isset($_GET['stock']) ? sanitize_text_field(wp_unslash($_GET['stock'])) : '';
$selected_sort = isset($_GET['sort']) ? sanitize_text_field(wp_unslash($_GET['sort'])) : '';
$company = getCompanyContacts();
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

                <a href="tel:<?php echo esc_attr($company['phone_clean']); ?>" class="stations-hero__phone">
                    <svg class="icon"><use href="#icon-phone"/></svg>
                    Получите персональное предложение: <?php echo esc_html($company['phone']); ?>
                </a>

                <?php // Описание линейки перенесено под каталог (см. .stations-desc ниже):
                      // выше остаются только заголовок, промо и телефон, чтобы карточки
                      // и калькулятор были видны сразу, без длинной простыни текста. ?>
            </section>

            <!-- ===== Калькулятор подбора и расчёта (4.1) ===== -->
            <?php echo do_shortcode('[topas_calculator]'); ?>

            <?php $catalog_anchor = esc_url(get_post_type_archive_link('stations')) . '#catalog'; ?>
            <form id="catalog" class="catalog-filter" method="get" action="<?php echo $catalog_anchor; ?>">
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
<!--                <div class="catalog-filter__field">
                    <label for="station-stock">Наличие</label>
                    <select id="station-stock" name="stock">
                        <option value="">Все</option>
                        <option value="1" <?php /*selected($selected_stock, '1'); */?>>В наличии</option>
                    </select>
                </div>-->
                <div class="catalog-filter__field">
                    <label for="station-sort">Сортировка</label>
                    <select id="station-sort" name="sort">
                        <option value="">Сначала дешёвые</option>
                        <option value="price_desc" <?php selected($selected_sort, 'price_desc'); ?>>Сначала дорогие</option>
                    </select>
                </div>
                <div class="catalog-filter__actions">
                    <button class="btn-card btn-gold" type="submit">Показать</button>
                    <a class="btn-card btn-outline" href="<?php echo esc_url(get_post_type_archive_link('stations')); ?>">Сбросить</a>
                </div>
            </form>

            <!-- ===== СЕТКА СТАНЦИЙ ===== -->
            <?php
            // Берём все станции одним запросом, дальше всё фильтруем и сортируем
            // в PHP через carbon_get_post_meta() — это надёжнее, чем meta_query,
            // т.к. не зависит от того, в каком формате Carbon Fields хранит значения.
            $catalog_query = new WP_Query(array(
                'post_type'      => 'stations',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'post_status'    => 'publish',
            ));
            $all_stations = $catalog_query->posts;

            // Фильтр по пользователям: вытаскиваем числа из текста (или из заголовка),
            // понимаем диапазон min-max и одиночное значение.
            if ($selected_capacity) {
                $all_stations = array_values(array_filter($all_stations, function ($p) use ($selected_capacity) {
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
                    return $selected_capacity >= min($nums) && $selected_capacity <= max($nums);
                }));
            }

            // Фильтр по водоотведению: ищем основу слова «самот» / «принуд»
            // в полях водоотведения и габаритов монтажа.
            if ($selected_drainage) {
                $drainage_stem = (stripos($selected_drainage, 'принуд') !== false) ? 'принуд' : 'самот';
                $all_stations = array_values(array_filter($all_stations, function ($p) use ($drainage_stem) {
                    $haystack = mb_strtolower(
                        (string) carbon_get_post_meta($p->ID, 'crb_water_disposal') . ' ' .
                        (string) carbon_get_post_meta($p->ID, 'crb_mounting_dimensions')
                    );
                    return mb_strpos($haystack, $drainage_stem) !== false;
                }));
            }

            // Фильтр по наличию (по умолчанию все товары в наличии).
            if ($selected_stock === '1') {
                $all_stations = array_values(array_filter($all_stations, function ($p) {
                    return station_is_in_stock($p->ID);
                }));
            }

            // Сортировка по цене (берём цену ТОПАС-С, иначе обычную ТОПАС).
            // По умолчанию — от дешёвых к дорогим. Товары без цены («По запросу») — в конце.
            $sort_desc = ($selected_sort === 'price_desc');
            usort($all_stations, function ($a, $b) use ($sort_desc) {
                $pa = (float) (carbon_get_post_meta($a->ID, 'crb_price_topas_s') ?: carbon_get_post_meta($a->ID, 'crb_price'));
                $pb = (float) (carbon_get_post_meta($b->ID, 'crb_price_topas_s') ?: carbon_get_post_meta($b->ID, 'crb_price'));
                if ($pa <= 0 && $pb <= 0) {
                    return 0;
                }
                if ($pa <= 0) {
                    return 1; // $a без цены — в конец
                }
                if ($pb <= 0) {
                    return -1; // $b без цены — в конец
                }
                return $sort_desc ? ($pb <=> $pa) : ($pa <=> $pb);
            });

            // Ручная пагинация
            $per_page = 12;
            $paged = max(1, (int) (get_query_var('paged') ?: 1));
            $total_stations = count($all_stations);
            $max_pages = max(1, (int) ceil($total_stations / $per_page));
            $paged = min($paged, $max_pages);
            $page_stations = array_slice($all_stations, ($paged - 1) * $per_page, $per_page);
            ?>

            <?php if (!empty($page_stations)) : ?>
                <?php // Карточка та же, что на главной — template-parts/station-card-pro.php. ?>
                <div class="stations-grid pro-grid">
                    <?php foreach ($page_stations as $station_post) : ?>
                        <?php get_template_part('template-parts/station-card-pro', null, array('id' => $station_post->ID)); ?>
                    <?php endforeach; ?>
                </div>

                <!-- Пагинация -->
                <?php if ($max_pages > 1) : ?>
                    <nav class="pagination" aria-label="Навигация">
                        <?php
                        echo paginate_links(array(
                            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                            'format' => '?paged=%#%',
                            'current' => $paged,
                            'total' => $max_pages,
                            'prev_text' => '←',
                            'next_text' => '→',
                            'type' => 'list',
                            'mid_size' => 2,
                            'add_fragment' => '#catalog',
                        ));
                        ?>
                    </nav>
                <?php endif; ?>

            <?php else : ?>
                <div class="no-results">
                    <p><?php echo $selected_capacity || $selected_drainage || $selected_stock
                        ? 'По заданным фильтрам станции не найдены. Попробуйте изменить параметры.'
                        : 'Станции пока не добавлены в каталог.'; ?></p>
                </div>
            <?php endif; ?>

            <!-- ===== ОПИСАНИЕ ЛИНЕЙКИ (было над каталогом) ===== -->
            <section class="stations-desc">
                <h2 class="stations-desc__title">О станциях ТОПАС и модельных опциях</h2>

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

            <?php wp_reset_postdata(); ?>
        </div>
    </main>

<?php get_footer(); ?>
