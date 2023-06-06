<?php
/**
 * Шаблон отдельной станции ТОПАС — премиум-стиль
 * Template Name: Station Single Premium
 */

get_header();

while (have_posts()) : the_post();

    // Безопасное получение полей
    $get_meta = function($key, $default = '') {
        if (function_exists('carbon_get_post_meta')) {
            $value = carbon_get_post_meta(get_the_ID(), $key);
            return $value !== null && $value !== '' ? $value : $default;
        }
        return get_post_meta(get_the_ID(), $key, true) ?: $default;
    };

    // Данные
    $price = $get_meta('crb_price');
    $old_price = $get_meta('crb_old_price');
    $people = $get_meta('crb_people_count_text');
    $daily_volume = $get_meta('crb_daily_volume');
    $peak_discharge = $get_meta('crb_peak_discharge');
    $power_consumption = $get_meta('crb_power_consumption');
    $dimensions = $get_meta('crb_dimensions');
    $installation_depth = $get_meta('crb_installation_depth');
    $is_hit = $get_meta('crb_is_hit');
    $is_new = $get_meta('crb_is_new');
    $in_stock = $get_meta('crb_in_stock');
    $equipment = $get_meta('crb_equipment', []);
    $manual_pdf = $get_meta('crb_manual_pdf');
    $gallery = $get_meta('crb_gallery', []);

    // Галерея
    $gallery_images = [];
    if (has_post_thumbnail()) {
        $gallery_images[] = ['url' => get_the_post_thumbnail_url(get_the_ID(), 'large'), 'alt' => get_the_title()];
    }
    if (!empty($gallery) && is_array($gallery)) {
        foreach ($gallery as $item) {
            if (!empty($item['photo'])) {
                $gallery_images[] = ['url' => $item['photo'], 'alt' => $item['alt'] ?? get_the_title()];
            }
        }
    }
    $main_image = $gallery_images[0] ?? ['url' => get_template_directory_uri() . '/assets/images/no-image.jpg', 'alt' => get_the_title()];
    ?>

    <!-- 🎨 СТИЛИ (цвета из хедера) -->
    <style>
        :root {
            --gold: #d4af37;
            --gold-hover: #f4d03f;
            --blue: #2563eb;
            --blue-hover: #1d4ed8;
            --text: #1e293b;
            --text-muted: #64748b;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --border: rgba(0, 0, 0, 0.08);
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .price-wrapper{
            display: flex;
            gap: 12px;
        }
        .station-single { padding: 2rem 0; background: var(--bg); }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }

        /* Бейджи */
        .station-badges { display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap; }
        .badge {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.4rem 0.9rem; border-radius: 20px;
            font-size: 0.8rem; font-weight: 600; color: #fff;
        }
        .badge--hit { background: var(--gold); }
        .badge--new { background: var(--blue); }
        .badge--stock { background: var(--text-muted); }
        .badge--stock.in-stock { background: #10b981; }

        /* Заголовок */
        .station-title {
            font-size: 1.8rem; font-weight: 700; color: var(--text);
            margin: 0 0 0.5rem; line-height: 1.3;
        }
        .station-subtitle {
            color: var(--text-muted); font-size: 1rem;
            display: flex; align-items: center; gap: 0.5rem;            margin-bottom: 12px !important;

        }
        .station-subtitle .icon { width: 18px; height: 18px; color: var(--blue);
        }

        /* Сетка */
        .station-grid { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 2.5rem; margin-bottom: 2.5rem; }
        @media (max-width: 900px) { .station-grid { grid-template-columns: 1fr; } }

        /* Галерея */
        .station-gallery { background: var(--card-bg); border-radius: var(--radius);height: fit-content; overflow: hidden; box-shadow: var(--shadow); margin-bottom: 1.5rem; }
        .gallery-main { aspect-ratio: 4/3; background: #f1f5f9; display: flex; align-items: center; justify-content: center; }
        .gallery-main img { width: 100%; height: 100%; object-fit: contain; padding: 1rem; transition: transform 0.3s ease; }
        .station-gallery:hover .gallery-main img { transform: scale(1.03); }
        .gallery-placeholder { color: var(--text-muted); }
        .gallery-placeholder .icon { width: 64px; height: 64px; opacity: 0.6; }
        .gallery-thumbs { display: flex; gap: 0.5rem; padding: 0.75rem; overflow-x: auto; }
        .gallery-thumb {
            width: 70px; height: 52px; border-radius: 8px; overflow: hidden;
            border: 2px solid transparent; cursor: pointer; flex-shrink: 0;
            background: #f1f5f9; transition: var(--transition);
        }
        .gallery-thumb.active, .gallery-thumb:hover { border-color: var(--gold); }
        .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }

        /* Сайдбар */
        .station-sidebar { display: flex; flex-direction: column; gap: 1rem; }

        /* Цена */
        .price-card {
            background: var(--card-bg); border-radius: var(--radius); padding: 1.5rem;
            box-shadow: var(--shadow); border-top: 3px solid var(--gold);
        }
        .price-old { display: block; color: var(--text-muted); text-decoration: line-through; font-size: 1rem; margin-bottom: 0.25rem; }
        .price-discount {
            display: inline-block; background: #ef4444; color: #fff;
            padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.5rem;
        }
        .price-current { font-size: 1.75rem; font-weight: 700; color: var(--text); }
        .price-note { color: var(--text-muted); font-size: 0.9rem; margin-top: 0.75rem; }

        /* Кнопки */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.85rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: 0.95rem;
            text-decoration: none; transition: var(--transition); border: none; cursor: pointer; width: 100%;
        }
        .btn--gold {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-hover) 100%); color: #0f172a;
        }
        .btn--gold:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(212, 175, 55, 0.35); }
        .btn--outline { background: transparent; border: 2px solid var(--blue); color: var(--blue); }
        .btn--outline:hover { background: var(--blue); color: #fff; }
        .btn--text { background: none; border: none; color: var(--text-muted); font-weight: 500; padding: 0.5rem; }
        .btn--text:hover { color: var(--blue); text-decoration: underline; }

        /* Характеристики */
        .specs-card { background: var(--card-bg); border-radius: var(--radius); padding: 1.25rem; box-shadow: var(--shadow); }
        .specs-card__title { font-size: 1.1rem; font-weight: 600; color: var(--text); margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .specs-list { list-style: none; padding: 0; margin: 0; }
        .spec-row { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--border); font-size: 0.95rem; }
        .spec-row:last-child { border-bottom: none; }
        .spec-label { color: var(--text-muted); }
        .spec-value { color: var(--text); font-weight: 500; }

        /* Документация */
        .docs-card { background: var(--card-bg); border-radius: var(--radius); padding: 1rem 1.25rem; box-shadow: var(--shadow); }
        .docs-card__title { font-size: 1rem; font-weight: 600; color: var(--text); margin: 0 0 0.75rem; }
        .docs-link {
            display: flex; align-items: center; gap: 0.5rem; color: var(--blue);
            text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: var(--transition);
        }
        .docs-link:hover { gap: 0.75rem; }
        .docs-link .icon { width: 18px; height: 18px; }

        /* Гарантии */
        .guarantees { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; }
        .guarantee-item {
            background: var(--card-bg); border-radius: var(--radius); padding: 1rem;
            text-align: center; box-shadow: var(--shadow); font-size: 0.85rem; color: var(--text);
            display: flex;flex-direction: column;align-items: center;
        }
        .guarantee-item .icon { width: 24px; height: 24px; color: var(--gold); margin-bottom: 0.4rem; display: block; }

        /* Описание */
        .station-section { background: var(--card-bg); border-radius: var(--radius); padding: 1.75rem; box-shadow: var(--shadow); margin-bottom: 1.5rem; }
        .section-title { font-size: 1.3rem; font-weight: 600; color: var(--text); margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .content-block { color: var(--text-muted); line-height: 1.7; }
        .content-block p { margin: 0 0 1rem; }
        .content-block p:last-child { margin-bottom: 0; }

        /* Комплектация */
        .equipment-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 0.75rem; }
        .equipment-item {
            display: flex; justify-content: space-between; padding: 0.75rem 1rem;
            background: var(--bg); border-radius: 8px; font-size: 0.9rem;
        }
        .equipment-item__name { color: var(--text-muted); }
        .equipment-item__qty { font-weight: 600; color: var(--blue); background: rgba(37,99,235,0.1); padding: 0.2rem 0.6rem; border-radius: 6px; }

        /* Преимущества */
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .feature-card {
            background: var(--card-bg); border-radius: var(--radius); padding: 1.25rem;
            text-align: center; box-shadow: var(--shadow); transition: var(--transition);
        }
        .feature-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }
        .feature-card .icon { width: 32px; height: 32px; color: var(--gold); margin-bottom: 0.5rem; display: block; margin-left: auto; margin-right: auto; }
        .feature-card h4 { font-size: 1rem; font-weight: 600; color: var(--text); margin: 0 0 0.4rem; }
        .feature-card p { font-size: 0.9rem; color: var(--text-muted); margin: 0; line-height: 1.5; }

        /* CTA */
        .station-cta {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-hover) 100%);
            border-radius: var(--radius); padding: 2rem; text-align: center; margin-top: 1rem;
        }
        .station-cta h2 { color: #0f172a; font-size: 1.4rem; margin: 0 0 0.5rem; }
        .station-cta p { color: rgba(15,23,42,0.85); margin: 0 0 1.25rem; }
        .station-cta .btn--gold { background: #0f172a; color: #fff; max-width: 320px; }
        .station-cta .btn--gold:hover { background: #1e293b; box-shadow: 0 6px 20px rgba(15,23,42,0.3); }
        .cta-note { color: rgba(15,23,42,0.7); font-size: 0.85rem; margin-top: 0.75rem; }

        /* Модальное окно */
        .modal {
            display: none; position: fixed; inset: 0; z-index: 2000;
            align-items: center; justify-content: center; padding: 1rem;
        }
        .modal.active { display: flex; }
        .modal__overlay { position: absolute; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); }
        .modal__content {
            position: relative; background: var(--card-bg); border-radius: var(--radius);
            padding: 2rem; max-width: 460px; width: 100%; z-index: 1; box-shadow: var(--shadow);
        }
        .modal__close {
            position: absolute; top: 1rem; right: 1rem; background: none; border: none;
            color: var(--text-muted); cursor: pointer; padding: 0.4rem; transition: var(--transition);
        }
        .modal__close:hover { color: var(--text); }
        .modal__close .icon { width: 24px; height: 24px; }
        .modal__header { text-align: center; margin-bottom: 1.5rem; }
        .modal__header h3 { font-size: 1.3rem; font-weight: 600; color: var(--text); margin: 0 0 0.4rem; }
        .modal__header p { color: var(--text-muted); font-size: 0.9rem; margin: 0; }
        .modal__form { display: flex; flex-direction: column; gap: 1rem; }
        .form-group input, .form-group textarea {
            width: 100%; padding: 0.85rem 1rem; border: 1px solid var(--border);
            border-radius: 8px; font-size: 0.95rem; transition: var(--transition);
        }
        .form-group input:focus, .form-group textarea:focus {
            outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(212,175,55,0.15);
        }
        .form-consent { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted); }
        .form-consent input { margin-top: 0.2rem; }
        .modal__note { font-size: 0.8rem; color: var(--text-muted); text-align: center; margin-top: 1rem; }
        .modal__note a { color: var(--blue); text-decoration: none; }
        .modal__note a:hover { text-decoration: underline; }

        /* Lightbox */
        .lightbox {
            display: none; position: fixed; inset: 0; z-index: 2001;
            background: rgba(0,0,0,0.9); align-items: center; justify-content: center; padding: 1rem;
        }
        .lightbox.active { display: flex; }
        .lightbox__img { max-width: 95%; max-height: 90vh; border-radius: 8px; }
        .lightbox__close {
            position: absolute; top: 1rem; right: 1rem; background: rgba(255,255,255,0.15);
            border: none; color: #fff; width: 40px; height: 40px; border-radius: 50%;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
        }
        .lightbox__close .icon { width: 24px; height: 24px; }

        /* SVG */
        .icon { display: inline-block; vertical-align: middle; }
    </style>

    <!-- 🔷 SVG СПРАЙТ -->
    <svg style="display:none">
        <symbol id="icon-people" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
        <symbol id="icon-phone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></symbol>
        <symbol id="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></symbol>
        <symbol id="icon-tool" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
        <symbol id="icon-download" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></symbol>
        <symbol id="icon-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></symbol>
        <symbol id="icon-wrench" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
        <symbol id="icon-leaf" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></symbol>
        <symbol id="icon-bolt" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></symbol>
        <symbol id="icon-volume" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/></symbol>
        <symbol id="icon-ruler" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"/><path d="m14.5 12.5 2-2"/><path d="m11.5 9.5 2-2"/><path d="m8.5 6.5 2-2"/><path d="m17.5 15.5 2-2"/></symbol>
        <symbol id="icon-depth" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></symbol>
        <symbol id="icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></symbol>
        <symbol id="icon-zoom" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></symbol>
        <symbol id="icon-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></symbol>
    </svg>

    <main class="station-single">
        <div class="container">

            <!-- Бейджи + Заголовок -->
            <header class="station-header">
                <div class="station-badges">
                    <?php if ($is_hit): ?>
                        <span class="badge badge--hit">
                            <svg class="icon" width="14" height="14"><use href="#icon-check"/></svg>
                            Хит продаж
                        </span>
                    <?php endif; ?>
                    <?php if ($is_new): ?>
                        <span class="badge badge--new">
                            <svg class="icon" width="14" height="14"><use href="#icon-check"/></svg>
                            Новинка
                        </span>
                    <?php endif; ?>
                    <span class="badge badge--stock <?php echo $in_stock ? 'in-stock' : ''; ?>">
                        <?php echo $in_stock ? 'В наличии' : 'Под заказ'; ?>
                    </span>
                </div>
                <h1 class="station-title"><?php the_title(); ?></h1>
                <?php if ($people): ?>
                    <p class="station-subtitle">
                        <svg class="icon"><use href="#icon-people"/></svg>
                        Для семьи из <?php echo esc_html($people); ?> человек
                    </p>
                <?php endif; ?>
            </header>

            <!-- Сетка -->
            <div class="station-grid">

                <!-- Галерея -->
                <div class="station-gallery">
                    <div class="gallery-main">
                        <?php if ($main_image['url']): ?>
                            <img src="https://sankt-peterburg.tstn.ru/upload/iblock/323/nzh6rfor0i7wm2sknknsr4fx6hlf1t4s/4701712_1.jpg" alt="<?php echo esc_attr($main_image['alt']); ?>" id="mainGalleryImage">
                        <?php else: ?>
                            <div class="gallery-placeholder">
                                <svg class="icon"><use href="#icon-tool"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (count($gallery_images) > 1): ?>
                        <div class="gallery-thumbs">
                            <?php foreach ($gallery_images as $i => $img): ?>
                                <button class="gallery-thumb <?php echo $i === 0 ? 'active' : ''; ?>" onclick="changeMainImage('<?php echo esc_url($img['url']); ?>', this, <?php echo $i; ?>)">
                                    <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Сайдбар -->
                <aside class="station-sidebar">

                    <!-- Цена -->
                    <div class="price-card">
                        <?php if ($old_price && $old_price > $price): ?>
                            <div class="price-wrapper">
                                <span class="price-old"><?php echo number_format($old_price, 0, '.', ' '); ?> ₽</span>
                                <span class="price-discount">-<?php echo round((($old_price - $price) / $old_price) * 100); ?>%</span>
                            </div>
                        <?php endif; ?>
                        <div class="price-current">от  <?php echo $price ? number_format($price, 0, '.', ' ') . ' ₽' : 'По запросу'; ?></div>
                        <?php if ($in_stock): ?>
                            <p class="price-note">Доставка по РФ • Монтаж под ключ</p>
                        <?php endif; ?>
                    </div>

                    <!-- Кнопки -->
                    <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', getCarbonFields('theme_phones')[0]['phone_numbers'][0]['phone_number'] ?? '+74998400555'); ?>" class="btn btn--gold">
                        <svg class="icon" width="18" height="18"><use href="#icon-phone"/></svg>
                        Позвонить
                    </a>
                    <button class="btn btn--outline js-open-modal">Заказать звонок</button>
                    <button class="btn btn--text js-scroll-to-calc">Рассчитать монтаж</button>

                    <!-- Характеристики -->
                    <div class="specs-card">
                        <h3 class="specs-card__title">
                            <svg class="icon" width="20" height="20"><use href="#icon-tool"/></svg>
                            Характеристики
                        </h3>
                        <ul class="specs-list">
                            <?php if ($daily_volume): ?>
                                <li class="spec-row"><span class="spec-label">Производительность</span><span class="spec-value"><?php echo esc_html($daily_volume); ?> м³/сутки</span></li>
                            <?php endif; ?>
                            <?php if ($peak_discharge): ?>
                                <li class="spec-row"><span class="spec-label">Залповый сброс</span><span class="spec-value"><?php echo esc_html($peak_discharge); ?> л</span></li>
                            <?php endif; ?>
                            <?php if ($power_consumption): ?>
                                <li class="spec-row"><span class="spec-label">Потребление</span><span class="spec-value"><?php echo esc_html($power_consumption); ?> кВт/сутки</span></li>
                            <?php endif; ?>
                            <?php if ($dimensions): ?>
                                <li class="spec-row"><span class="spec-label">Габариты</span><span class="spec-value"><?php echo esc_html($dimensions); ?></span></li>
                            <?php endif; ?>
                            <?php if ($installation_depth): ?>
                                <li class="spec-row"><span class="spec-label">Глубина монтажа</span><span class="spec-value"><?php echo esc_html($installation_depth); ?> м</span></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Документация -->
                    <?php if ($manual_pdf): ?>
                        <div class="docs-card">
                            <h4 class="docs-card__title">Документация</h4>
                            <a href="<?php echo esc_url($manual_pdf); ?>" class="docs-link" target="_blank" download>
                                <svg class="icon"><use href="#icon-download"/></svg>
                                Скачать инструкцию (PDF)
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Гарантии -->
                    <div class="guarantees">
                        <div class="guarantee-item">
                            <svg class="icon"><use href="#icon-shield"/></svg>
                            Гарантия 2 года
                        </div>
                        <div class="guarantee-item">
                            <svg class="icon"><use href="#icon-wrench"/></svg>
                            Сервисное обслуживание
                        </div>
                        <div class="guarantee-item">
                            <svg class="icon"><use href="#icon-leaf"/></svg>
                            Экологически безопасно
                        </div>
                    </div>

                </aside>
            </div>

            <!-- Описание -->
            <?php if (get_the_content()): ?>
                <section class="station-section">
                    <div class="content-block"><?php the_content(); ?></div>
                </section>
            <?php endif; ?>

            <!-- Комплектация -->
            <?php if (!empty($equipment) && is_array($equipment)): ?>
                <section class="station-section">
                    <h2 class="section-title">В комплекте</h2>
                    <div class="equipment-grid">
                        <?php foreach ($equipment as $item): ?>
                            <div class="equipment-item">
                                <span class="equipment-item__name"><?php echo esc_html($item['item_name'] ?? ''); ?></span>
                                <span class="equipment-item__qty"><?php echo esc_html($item['item_qty'] ?? '1'); ?> шт.</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Преимущества -->
            <section class="station-section">
                <h2 class="section-title">Преимущества</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <svg class="icon"><use href="#icon-check"/></svg>
                        <h4>Очистка 98%</h4>
                        <p>Соответствует санитарным нормам, вода безопасна для почвы</p>
                    </div>
                    <div class="feature-card">
                        <svg class="icon"><use href="#icon-volume"/></svg>
                        <h4>Бесшумная работа</h4>
                        <p>Компрессор с шумоизоляцией — комфортно для участка</p>
                    </div>
                    <div class="feature-card">
                        <svg class="icon"><use href="#icon-bolt"/></svg>
                        <h4>Энергоэффективность</h4>
                        <p>Минимальное потребление электричества — экономия бюджета</p>
                    </div>
                    <div class="feature-card">
                        <svg class="icon"><use href="#icon-wrench"/></svg>
                        <h4>Простой монтаж</h4>
                        <p>Установка за 1 день без спецтехники на большинстве участков</p>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="station-cta" id="order-form">
                <h2>Готовы установить <?php the_title(); ?>?</h2>
                <p>Оставьте заявку — инженер бесплатно подберёт решение под ваш участок</p>
                <button class="btn btn--gold js-open-modal">Заказать бесплатный выезд инженера</button>
                <p class="cta-note">Конфиденциально • Ответим в течение 15 минут</p>
            </section>

        </div>
    </main>

    <!-- Модальное окно -->
    <div class="modal" id="callback-modal">
        <div class="modal__overlay" onclick="closeModal()"></div>
        <div class="modal__content">
            <button class="modal__close" onclick="closeModal()" aria-label="Закрыть">
                <svg class="icon"><use href="#icon-close"/></svg>
            </button>
            <div class="modal__header">
                <h3>Заказать <?php the_title(); ?></h3>
                <p>Перезвоним за 5 минут • Консультация бесплатна</p>
            </div>
            <form class="modal__form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                <input type="hidden" name="action" value="station_order_request">
                <input type="hidden" name="product_id" value="<?php echo get_the_ID(); ?>">
                <input type="hidden" name="product_name" value="<?php the_title_attribute(); ?>">
                <input type="hidden" name="product_price" value="<?php echo esc_attr($price); ?>">
                <div class="form-group"><input type="text" name="name" placeholder="Ваше имя *" required></div>
                <div class="form-group"><input type="tel" name="phone" placeholder="+7 (___) ___-__-__ *" required></div>
                <div class="form-group"><textarea name="comment" rows="3" placeholder="Комментарий (необязательно)"></textarea></div>
                <label class="form-consent"><input type="checkbox" name="consent" required checked> Согласен на обработку персональных данных</label>
                <button type="submit" class="btn btn--gold">Отправить заявку</button>
            </form>
            <p class="modal__note">Нажимая кнопку, вы соглашаетесь с <a href="/privacy-policy" target="_blank">политикой конфиденциальности</a></p>
        </div>
    </div>

    <!-- Lightbox -->
    <div class="lightbox" id="imageLightbox" onclick="closeLightbox()">
        <button class="lightbox__close" aria-label="Закрыть"><svg class="icon"><use href="#icon-close"/></svg></button>
        <img src="" alt="" class="lightbox__img" id="lightboxImage">
    </div>

    <!-- Скрипты -->
    <script>
        const galleryImages = <?php echo json_encode(array_column($gallery_images, 'url')); ?>;
        let currentImageIndex = 0;

        function changeMainImage(src, thumb, index) {
            document.getElementById('mainGalleryImage').src = src;
            document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
            currentImageIndex = index;
        }

        function openLightbox(index) {
            currentImageIndex = index;
            const lb = document.getElementById('imageLightbox');
            const img = document.getElementById('lightboxImage');
            img.src = galleryImages[index] || document.getElementById('mainGalleryImage').src;
            lb.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeLightbox() { document.getElementById('imageLightbox').classList.remove('active'); document.body.style.overflow = ''; }

        function openModal() { document.getElementById('callback-modal').classList.add('active'); document.body.style.overflow = 'hidden'; }
        function closeModal() { document.getElementById('callback-modal').classList.remove('active'); document.body.style.overflow = ''; }

        document.querySelectorAll('.js-open-modal').forEach(btn => btn.addEventListener('click', openModal));
        document.querySelectorAll('.js-scroll-to-calc').forEach(btn => btn.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector('#order-form');
            if (target) {
                const headerH = document.querySelector('.site-header-premium')?.offsetHeight || 100;
                window.scrollTo({ top: target.getBoundingClientRect().top + window.pageYOffset - headerH - 20, behavior: 'smooth' });
            }
        }));

        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') { closeLightbox(); closeModal(); } });

        // Маска телефона
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
    </script>

<?php endwhile; get_footer(); ?>