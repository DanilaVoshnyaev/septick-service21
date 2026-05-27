<?php
/**
 * Шаблон отдельной услуги — две колонки (ПК) / одна (моб.)
 * Единая цветовая схема с хедером
 * Template Name: Service Two Column
 */

get_header();

while (have_posts()) : the_post();

    // Данные из Carbon Fields
    $price = carbon_get_post_meta(get_the_ID(), 'crb_service_price');
    $old_price = carbon_get_post_meta(get_the_ID(), 'crb_service_old_price');
    $duration = carbon_get_post_meta(get_the_ID(), 'crb_service_duration');
    $warranty = carbon_get_post_meta(get_the_ID(), 'crb_service_warranty');
    $short_desc = carbon_get_post_meta(get_the_ID(), 'crb_service_short_desc');
    $is_popular = carbon_get_post_meta(get_the_ID(), 'crb_service_is_popular');
    $is_new = carbon_get_post_meta(get_the_ID(), 'crb_service_is_new');
    $in_stock = carbon_get_post_meta(get_the_ID(), 'crb_service_in_stock');
    $icon = carbon_get_post_meta(get_the_ID(), 'crb_service_icon');
    $features = carbon_get_post_meta(get_the_ID(), 'crb_service_features');

    // Телефон из настроек темы
    $phone_raw = getCarbonFields('theme_phones')[0]['phone_numbers'][0]['phone_number'] ?? '+79083033282';
    $phone_clean = preg_replace('/[^0-9+]/', '', $phone_raw);

    // Изображение
    $service_image = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '';
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

        .service-page {
            padding: 2rem 0;
            background: var(--bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }

        /* Бейджи */
        .badges { display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap; }
        .badge {
            padding: 0.35rem 0.8rem; border-radius: 20px;
            font-size: 0.8rem; font-weight: 600; color: #fff;
        }
        .badge--popular { background: var(--green); }  /* ✅ Было: var(--gold) */
        .badge--new { background: var(--blue); }
        .badge--stock { background: var(--text-muted); }
        .badge--stock.in-stock { background: #10b981; }

        /* Заголовок */
        .service-title { font-size: 1.8rem; font-weight: 700; color: var(--text); margin: 0 0 20px; line-height: 1.3; }
        .service-subtitle { color: var(--text-muted); margin: 0 0 1.5rem; font-size: 1.05rem; }

        /* Две колонки */
        .service-layout {
            display: grid;
            grid-template-columns: 1fr 1.1fr;
            gap: 2rem;
            align-items: start;
            margin-bottom: 2rem;
        }
        @media (max-width: 900px) {
            .service-layout { grid-template-columns: 1fr; }
        }

        /* Левая колонка: картинка + цена */
        .service-image-col { position: sticky; top: 100px; }
        .service-image {
            width: 100%; aspect-ratio: 4/3; border-radius: var(--radius);
            overflow: hidden; background: #f1f5f9; margin-bottom: 1rem;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid var(--border);  /* ✅ Добавлена граница */
        }
        .service-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
        .service-image:hover img { transform: scale(1.03); }  /* ✅ Добавлен зум при ховере */
        .service-image .placeholder { color: var(--text-muted); }
        .service-image .placeholder .icon { width: 48px; height: 48px; opacity: 0.6; }

        /* Цена */
        .price-box {
            background: var(--card-bg); border-radius: var(--radius);
            padding: 1.25rem; box-shadow: var(--shadow);
            border-top: 3px solid var(--green); text-align: center;  /* ✅ Было: var(--gold) */
            border: 1px solid var(--border);
        }
        .price-old { display: block; color: var(--text-muted); text-decoration: line-through; font-size: 1rem; margin-bottom: 0.3rem; }
        .price-discount { display: inline-block; background: #ef4444; color: #fff; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.5rem; }
        .price-current { font-size: 1.6rem; font-weight: 700; color: var(--text); margin-bottom: 0.5rem; }
        .price-note { color: var(--text-muted); font-size: 0.9rem; }

        /* Правая колонка: контент */
        .service-content-col {
            background: var(--card-bg); border-radius: var(--radius);
            padding: 1.75rem; box-shadow: var(--shadow);
            border: 1px solid var(--border);  /* ✅ Добавлена граница */
        }

        /* Описание из редактора */
        .service-description { color: var(--text-muted); line-height: 1.7; margin-bottom: 1.5rem; }
        .service-description p { margin: 0 0 1rem; }
        .service-description p:last-child { margin-bottom: 0; }

        /* Параметры */
        .specs { margin-bottom: 1.25rem; }
        .specs__title { font-size: 1.05rem; font-weight: 600; color: var(--text); margin: 0 0 0.75rem; }
        .specs-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 0.5rem; }
        .spec-row { display: flex; justify-content: space-between; font-size: 0.95rem; padding: 0.4rem 0; border-bottom: 1px dashed var(--border); }
        .spec-row:last-child { border-bottom: none; }
        .spec-label { color: var(--text-muted); }
        .spec-value { font-weight: 500; color: var(--text); }

        /* Преимущества */
        .features { margin-bottom: 1.5rem; }
        .features__title { font-size: 1.05rem; font-weight: 600; color: var(--text); margin: 0 0 0.75rem; }
        .features-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 0.6rem; }
        .feature-item { display: flex; gap: 0.6rem; font-size: 0.95rem; color: var(--text); }
        .feature-icon { width: 20px; height: 20px; color: var(--green); flex-shrink: 0; margin-top: 2px; }  /* ✅ Было: var(--gold) */
        .feature-title { font-weight: 600; display: block; margin-bottom: 0.15rem; }
        .feature-desc { color: var(--text-muted); font-size: 0.9rem; }

        /* Кнопка заказать звонок — логика хедера */
        .btn-call {
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            width: 100%; padding: 0.95rem 1.5rem; border-radius: 8px;
            background: var(--green); color: white;  /* ✅ Было: линейный градиент с gold */
            font-weight: 700; font-size: 1rem;
            text-decoration: none; border: 1px solid var(--green); cursor: pointer;
            transition: var(--transition);
        }
        .btn-call:hover {
            background: #fff; color: #0f172a;  /* ✅ Логика хедера: инверсия при ховере */
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(33, 178, 36, 0.35);
        }
        .btn-call .icon { width: 18px; height: 18px; }

        /* CTA блок внизу — инвертированная схема */
        .cta-bottom {
            text-align: center; padding: 1.75rem;
            background: var(--green);  /* ✅ Было: линейный градиент с gold */
            border-radius: var(--radius);
            border: 1px solid var(--green);
        }
        .cta-bottom h2 { color: white; font-size: 1.3rem; margin: 0 0 0.5rem; }  /* ✅ Было: #0f172a */
        .cta-bottom p { color: rgba(255,255,255,0.9); margin: 0 0 1rem; font-size: 0.95rem; }  /* ✅ Было: rgba(15,23,42,0.85) */
        .cta-bottom .btn-call {
            background: #fff; color: #0f172a; border-color: #fff; max-width: 280px; margin: 0 auto;
        }  /* ✅ Инверсия: белая кнопка на зелёном фоне */
        .cta-bottom .btn-call:hover {
            background: var(--green); color: #fff; border-color: var(--green);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
        }
        .cta-note { color: rgba(255,255,255,0.8); font-size: 0.85rem; margin-top: 0.75rem; }  /* ✅ Было: rgba(15,23,42,0.7) */

        /* SVG */
        .icon { display: inline-block; vertical-align: middle; }

        /* Адаптив */
        @media (max-width: 900px) {
            .service-image-col { position: static; }
            .service-title { font-size: 1.4rem; }
            .service-subtitle { font-size: 1rem; }
            .price-current { font-size: 1.4rem; }
            .service-content-col { padding: 1.25rem; }
        }
        @media (max-width: 480px) {
            .badges { justify-content: center; }
            .service-layout { gap: 1.5rem; }
        }
    </style>

    <!-- 🔷 SVG СПРАЙТ -->
    <svg style="display:none">
        <symbol id="icon-phone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></symbol>
        <symbol id="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></symbol>
        <symbol id="icon-tool" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
        <symbol id="icon-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></symbol>
        <symbol id="icon-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></symbol>
        <symbol id="icon-star" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></symbol>
        <symbol id="icon-sparkle" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3l1.5 4.5L18 9l-4.5 1.5L12 15l-1.5-4.5L6 9l4.5-1.5L12 3z"/></symbol>
    </svg>

    <main class="service-page">
        <div class="container">

            <!-- Бейджи -->
            <div class="badges">
                <?php if ($is_popular): ?>
                    <span class="badge badge--popular">Популярное</span>
                <?php endif; ?>
                <?php if ($is_new): ?>
                    <span class="badge badge--new">Новинка</span>
                <?php endif; ?>
                <span class="badge badge--stock <?php echo $in_stock ? 'in-stock' : ''; ?>">
                    <?php echo $in_stock ? 'В наличии' : 'Под заказ'; ?>
                </span>
            </div>

            <h1 class="service-title"><?php the_title(); ?></h1>
            <?php if ($short_desc): ?><p class="service-subtitle"><?php echo esc_html($short_desc); ?></p><?php endif; ?>

            <!-- Две колонки: ПК / одна: моб. -->
            <div class="service-layout">

                <!-- Левая: картинка + цена -->
                <div class="service-image-col">
                    <div class="service-image">
                        <?php if ($service_image): ?>
                            <img src="<?php echo esc_url($service_image); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php elseif (has_post_thumbnail()): ?>
                            <?php the_post_thumbnail('large'); ?>
                        <?php else: ?>
                            <div class="placeholder"><svg class="icon"><use href="#icon-tool"/></svg></div>
                        <?php endif; ?>
                    </div>

                    <!--                    <div class="price-box">
                        <?php /*if ($old_price && $old_price > $price): */?>
                            <span class="price-old"><?php /*echo number_format($old_price, 0, '.', ' '); */?> ₽</span>
                            <span class="price-discount">-<?php /*echo round((($old_price - $price) / $old_price) * 100); */?>%</span>
                        <?php /*endif; */?>
                        <div class="price-current"><?php /*echo $price ? number_format($price, 0, '.', ' ') . ' ₽' : 'По запросу'; */?></div>
                        <?php /*if ($in_stock): */?><p class="price-note">Быстрое выполнение • Гарантия</p><?php /*endif; */?>
                    </div>-->
                </div>

                <!-- Правая: описание + параметры + кнопка -->
                <div class="service-content-col">

                    <!-- Описание из редактора -->
                    <?php if (get_the_content()): ?>
                        <div class="service-description"><?php the_content(); ?></div>
                    <?php endif; ?>

                    <!-- Параметры -->
                    <?php if ($duration || $warranty): ?>
                        <div class="specs">
                            <h3 class="specs__title">Параметры</h3>
                            <ul class="specs-list">
                                <?php if ($duration): ?><li class="spec-row"><span class="spec-label">Срок</span><span class="spec-value"><?php echo esc_html($duration); ?></span></li><?php endif; ?>
                                <?php if ($warranty): ?><li class="spec-row"><span class="spec-label">Гарантия</span><span class="spec-value"><?php echo esc_html($warranty); ?></span></li><?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Преимущества -->
                    <?php if (!empty($features)): ?>
                        <div class="features">
                            <h3 class="features__title">Преимущества</h3>
                            <ul class="features-list">
                                <?php foreach ($features as $f): ?>
                                    <li class="feature-item">
                                        <?php if (!empty($f['feature_icon'])): ?>
                                            <span class="feature-icon"><?php echo esc_html($f['feature_icon']); ?></span>
                                        <?php else: ?>
                                            <svg class="feature-icon"><use href="#icon-check"/></svg>
                                        <?php endif; ?>
                                        <div>
                                            <?php if (!empty($f['feature_title'])): ?><strong class="feature-title"><?php echo esc_html($f['feature_title']); ?></strong><?php endif; ?>
                                            <?php if (!empty($f['feature_desc'])): ?><span class="feature-desc"><?php echo esc_html($f['feature_desc']); ?></span><?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Кнопка заказать звонок (внизу описания) -->
                    <a href="tel:<?php echo $phone_clean; ?>" class="btn-call">
                        <svg class="icon"><use href="#icon-phone"/></svg>
                        Заказать звонок
                    </a>

                </div>
            </div>

            <!-- CTA блок в самом низу -->
            <section class="cta-bottom">
                <h2>Готовы заказать "<?php the_title(); ?>"?</h2>
                <p>Специалист свяжется с вами в течение 15 минут</p>
                <a href="tel:<?php echo $phone_clean; ?>" class="btn-call">
                    <svg class="icon"><use href="#icon-phone"/></svg>
                    Позвонить сейчас
                </a>
                <p class="cta-note">Конфиденциально • Без спама</p>
            </section>

        </div>
    </main>

<?php endwhile; get_footer(); ?>