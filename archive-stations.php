<?php
/**
 * Шаблон архива станций ТОПАС — премиум (единая схема с хедером)
 */
get_header();
?>

    <!-- 🎨 СТИЛИ (цвета из хедера) -->
    <style>
        :root {
            /* ===== ЦВЕТОВАЯ СХЕМА ИЗ ХЕДЕРА ===== */
            --green: #21b224;                    /* ✅ Основной акцент */
            --green-hover: #f4d03f;              /* ✅ Ховер-эффект (жёлтый) */
            --blue: #2563eb;                     /* Вторичный акцент */
            --blue-hover: #1d4ed8;
            --text: #1e293b;                     /* Основной текст */
            --text-muted: #64748b;               /* Второстепенный текст */
            --bg: rgba(255, 255, 255, 0.95);     /* Фон с прозрачностью */
            --card-bg: #ffffff;
            --border: rgba(0, 0, 0, 0.08);
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stations-archive {
            padding: 2rem 0;
            background: var(--bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }

        /* ===== HERO БЛОК ===== */
        .stations-hero {
            background: var(--card-bg); border-radius: var(--radius);
            padding: 2rem; margin-bottom: 2.5rem; box-shadow: var(--shadow);
            border-top: 4px solid var(--green);  /* ✅ Было: var(--gold) */
            border: 1px solid var(--border);
        }

        .stations-hero__title {
            font-size: 1.8rem; font-weight: 700; color: var(--text);
            margin: 0 0 0.75rem; line-height: 1.3;
        }

        .stations-hero__promo {
            background: linear-gradient(135deg, rgba(33,178,36,0.12) 0%, rgba(37,99,235,0.08) 100%);
            border-left: 4px solid var(--green);  /* ✅ Было: var(--gold) */
            padding: 1rem 1.25rem; border-radius: 0 8px 8px 0;
            margin: 1rem 0; font-weight: 500; color: var(--text);
        }

        .stations-hero__phone {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.75rem 1.5rem; background: var(--green);  /* ✅ Было: var(--gold) */
            color: white; border-radius: 8px; text-decoration: none;
            font-weight: 700; font-size: 1.1rem; transition: var(--transition);
            margin: 0.5rem 0 1.5rem; border: 1px solid var(--green);
        }
        .stations-hero__phone:hover {
            background: #fff; color: #0f172a;  /* ✅ Логика хедера: зелёный → белый с чёрным текстом */
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(33, 178, 36, 0.35);
        }
        .stations-hero__phone .icon { width: 20px; height: 20px; }

        .stations-hero__desc {
            color: var(--text-muted); line-height: 1.7; font-size: 0.98rem;
            margin: 0;
        }
        .stations-hero__desc p { margin: 0 0 1rem; }
        .stations-hero__desc p:last-child { margin-bottom: 0; }
        .stations-hero__desc strong { color: var(--text); font-weight: 600; }

        /* ===== СЕТКА СТАНЦИЙ ===== */
        .stations-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem; margin: 2rem 0;
        }

        /* Карточка */
        .station-card {
            background: var(--card-bg); border-radius: var(--radius);
            overflow: hidden; box-shadow: var(--shadow);
            transition: var(--transition); display: flex; flex-direction: column;
            position: relative; border: 1px solid var(--border);
        }
        .station-card:hover {
            transform: translateY(-4px);
            border-color: var(--green);  /* ✅ Было: var(--gold) */
            box-shadow: 0 10px 30px rgba(33, 178, 36, 0.15);  /* ✅ Было: rgba(212, 175, 55, 0.15) */
        }

        /* Бейдж */
        .station-badge {
            position: absolute; top: 1rem; left: 1rem; z-index: 2;
            background: var(--green); color: white;  /* ✅ Было: color: #0f172a */
            padding: 0.35rem 0.85rem; border-radius: 20px;
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
            display: inline-flex; align-items: center; gap: 0.3rem;
        }

        /* Изображение */
        .station-card__image {
            aspect-ratio: 4/3; overflow: hidden; background: white;
        }
        .station-card__image a { display: block; height: 100%;padding: 20px }
        .station-card__image img {
            width: 100%; height: 100%; object-fit: contain;
            transition: transform 0.3s ease;
        }
        .station-card:hover .station-card__image img { transform: scale(1.05); }
        .station-placeholder {
            display: flex; align-items: center; justify-content: center;
            height: 100%; color: var(--text-muted);
        }
        .station-placeholder .icon { width: 48px; height: 48px; opacity: 0.6; }

        /* Контент карточки */
        .station-card__content { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }

        .station-card__title {
            margin: 0 0 0.5rem; font-size: 1.1rem; font-weight: 600; color: var(--text);
            line-height: 1.4;
        }
        .station-card__title a {
            color: inherit; text-decoration: none; transition: color 0.2s;
        }
        .station-card__title a:hover { color: var(--green); }  /* ✅ Было: var(--blue) */

        .station-card__people {
            margin: 0 0 1rem; color: var(--text-muted); font-size: 0.9rem;
            display: flex; align-items: center; gap: 0.4rem;
        }
        .station-card__people .icon { width: 16px; height: 16px; color: var(--green); }  /* ✅ Было: var(--blue) */

        /* Цена */
        .station-card__price { margin-top: auto; margin-bottom: 1rem; }
        .price-old {
            display: block; color: var(--text-muted); text-decoration: line-through;
            font-size: 0.9rem; margin-bottom: 0.25rem;
        }
        .price-current {
            font-size: 1.35rem; font-weight: 700; color: var(--text);
        }

        /* Кнопки */
        .station-card__actions {
            display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem;
        }
        .btn-card {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.65rem 0.85rem; border-radius: 8px;
            font-weight: 600; font-size: 0.9rem; text-decoration: none;
            transition: var(--transition); text-align: center;
        }
        .btn-card.btn-outline {
            background: transparent; border: 2px solid var(--green); color: var(--green);  /* ✅ Было: var(--blue) */
        }
        .btn-card.btn-outline:hover {
            background: var(--green); color: #fff;  /* ✅ Было: background: var(--blue) */
        }
        .btn-card.btn-gold {
            background: var(--green);  /* ✅ Было: линейный градиент с gold */
            color: white; border: 1px solid var(--green);
        }
        .btn-card.btn-gold:hover {
            background: #fff; color: #0f172a;  /* ✅ Логика хедера: зелёный → белый с чёрным текстом */
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(33, 178, 36, 0.35);
        }

        /* Пагинация */
        .pagination { margin: 2.5rem 0 1rem; }
        .pagination ul {
            display: flex; justify-content: center; gap: 0.25rem; list-style: none;
            padding: 0; margin: 0; flex-wrap: wrap;
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
            background: var(--card-bg); border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        /* Адаптив */
        @media (max-width: 768px) {
            .stations-hero { padding: 1.5rem; }
            .stations-hero__title { font-size: 1.4rem; }
            .stations-hero__phone { width: 100%; justify-content: center; }
            .stations-grid { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); }
            .station-card__actions { grid-template-columns: 1fr; }
        }

        /* SVG */
        .icon { display: inline-block; vertical-align: middle; }
    </style>

    <!-- 🔷 SVG СПРАЙТ -->
    <svg style="display:none">
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

                    <ul style="margin: 0; padding-left: 1.5rem; color: var(--text-muted);">
                        <li style="margin-bottom: 0.5rem;">
                            <strong>Количество компрессоров.</strong> В стандартной модификации установлено два компрессора, которые работают попеременно. Модификации «ТОПАС-С» (версии от 4 до 12) оснащены одним компрессором — производительность и качество очистки не изменяются.
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <strong>Способ водоотведения.</strong> Стандартная модификация — самотёком. Станции с дренажным насосом для принудительного отвода обозначаются суффиксом <strong>«Пр»</strong>.
                        </li>
                        <li>
                            <strong>Глубина входящей трубы.</strong> Базовое ограничение — до 80 см. Версии <strong>«Лонг»</strong> (80-140 см) и <strong>«Лонг Ус»</strong> (до 240 см) доступны для моделей от 5 пользователей.
                        </li>
                    </ul>
                </div>
            </section>

            <!-- ===== СЕТКА СТАНЦИЙ ===== -->
            <?php
            $args = array(
                'post_type' => 'stations',
                'posts_per_page' => 12,
                'paged' => get_query_var('paged') ?: 1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'post_status' => 'publish',
            );
            $catalog_query = new WP_Query($args);
            ?>

            <?php if ($catalog_query->have_posts()) : ?>
                <div class="stations-grid">
                    <?php while ($catalog_query->have_posts()) : $catalog_query->the_post();

                        $price = carbon_get_post_meta(get_the_ID(), 'crb_price');
                        $old_price = carbon_get_post_meta(get_the_ID(), 'crb_old_price');
                        $people = carbon_get_post_meta(get_the_ID(), 'crb_people_count_text');
                        $is_hit = carbon_get_post_meta(get_the_ID(), 'crb_is_hit');
                        ?>

                        <article class="station-card">

                            <?php if ($is_hit) : ?>
                                <span class="station-badge">
                                <svg class="icon" width="12" height="12"><use href="#icon-check"/></svg>
                                Хит
                            </span>
                            <?php endif; ?>

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
                                    <?php if ($old_price && $old_price > $price) : ?>
                                        <span class="price-old"><?php echo number_format($old_price, 0, '.', ' '); ?> ₽</span>
                                    <?php endif; ?>
                                    <span class="price-current">
                                    <?php echo $price ? number_format($price, 0, '.', ' ') : 'По запросу'; ?> ₽
                                </span>
                                </div>

                                <!-- Кнопки -->
                                <div class="station-card__actions">
                                    <a href="<?php the_permalink(); ?>" class="btn-card btn-outline">Подробнее</a>
                                    <a href="<?php the_permalink(); ?>#order" class="btn-card btn-gold">Заказать</a>
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