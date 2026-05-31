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
    ?><!-- 🔷 SVG СПРАЙТ -->
    <svg class="svg-sprite" aria-hidden="true">
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
<!--            <div class="badges">
                <?php /*if ($is_popular): */?>
                    <span class="badge badge--popular">Популярное</span>
                <?php /*endif; */?>
                <?php /*if ($is_new): */?>
                    <span class="badge badge--new">Новинка</span>
                <?php /*endif; */?>
                <span class="badge badge--stock <?php /*echo $in_stock ? 'in-stock' : ''; */?>">
                    <?php /*echo $in_stock ? 'В наличии' : 'Под заказ'; */?>
                </span>
            </div>
-->
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

                    <!-- Действия -->
                    <div class="service-actions">
                        <button type="button" class="btn-call open-modal" data-modal="order" data-product="<?php the_title_attribute(); ?>">
                            <svg class="icon"><use href="#icon-phone"/></svg>
                            Заказать звонок
                        </button>
                        <a href="tel:<?php echo $phone_clean; ?>" class="btn-call-link">
                            Или позвоните: <?php echo esc_html($phone_raw); ?>
                        </a>
                    </div>

                </div>
            </div>

            <!-- CTA блок в самом низу -->
            <section class="cta-bottom" id="order-form">
                <h2>Нужна услуга «<?php the_title(); ?>»?</h2>
                <p>Оставьте заявку — и вот что мы сделаем:</p>

                <ul class="cta-bottom__steps">
                    <li><svg class="icon" width="18" height="18"><use href="#icon-check"/></svg> Перезвоним в течение 15 минут</li>
                    <li><svg class="icon" width="18" height="18"><use href="#icon-check"/></svg> Бесплатно проконсультируем и подберём решение</li>
                    <li><svg class="icon" width="18" height="18"><use href="#icon-check"/></svg> Рассчитаем точную стоимость работ</li>
                </ul>

                <div class="cta-bottom__actions">
                    <button type="button" class="btn-call open-modal" data-modal="order" data-product="<?php the_title_attribute(); ?>">
                        <svg class="icon"><use href="#icon-phone"/></svg>
                        Заказать звонок
                    </button>
                    <a href="tel:<?php echo $phone_clean; ?>" class="cta-bottom__phone">Позвонить: <?php echo esc_html($phone_raw); ?></a>
                </div>

                <p class="cta-note">Бесплатно и без обязательств • Конфиденциально • Без спама</p>
            </section>

        </div>
    </main>

<?php endwhile; get_footer(); ?>