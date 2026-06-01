<?php
/**
 * Шаблон отдельной станции ТОПАС — премиум-стиль (единая схема с хедером)
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
$price_topas_s = $get_meta('crb_price_topas_s');
$old_price = $get_meta('crb_old_price');
$model_number = $get_meta('crb_model_number');
$compressors_topas_s = $get_meta('crb_compressors_topas_s', '1');
$compressors_topas = $get_meta('crb_compressors_topas', '2');

// Склонение слова «компрессор» по числу
$compressor_word = function ($n) {
    $n = (int) $n;
    $mod100 = $n % 100;
    $mod10 = $n % 10;
    if ($mod100 >= 11 && $mod100 <= 14) return 'компрессоров';
    if ($mod10 === 1) return 'компрессор';
    if ($mod10 >= 2 && $mod10 <= 4) return 'компрессора';
    return 'компрессоров';
};

// Метки колонок цены: «ТОПАС-С 5 (1 компрессор)» / «ТОПАС 5 (2 компрессора)»
$label_topas_s = trim('ТОПАС-С ' . $model_number);
$label_topas = trim('ТОПАС ' . $model_number);
$people = $get_meta('crb_people_count_text');
$daily_volume = $get_meta('crb_daily_volume');
$peak_discharge = $get_meta('crb_peak_discharge');
$power_consumption = $get_meta('crb_power_consumption');
$water_disposal = $get_meta('crb_water_disposal', $get_meta('crb_dimensions'));
$mounting_dimensions = $get_meta('crb_mounting_dimensions');
$installation_depth = $get_meta('crb_installation_depth');
$is_hit = $get_meta('crb_is_hit');
$is_new = $get_meta('crb_is_new');
$in_stock = function_exists('station_is_in_stock') ? station_is_in_stock(get_the_ID()) : $get_meta('crb_in_stock');
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
?><!-- 🔷 SVG СПРАЙТ (без изменений) -->
<svg class="svg-sprite" aria-hidden="true">
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

            <div class="station-title-row">
                <h1 class="station-title"><?php the_title(); ?></h1>
                <?php if ($in_stock) : ?>
                    <span class="station-stock-pill">
                            <svg class="icon" width="14" height="14"><use href="#icon-check"/></svg> В наличии
                        </span>
                <?php endif; ?>
            </div>

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
                        <img src="<?php echo esc_url($main_image['url']); ?>" alt="<?php echo esc_attr($main_image['alt']); ?>" id="mainGalleryImage" class="js-open-lightbox" data-gallery-index="0">
                    <?php else: ?>
                        <div class="gallery-placeholder">
                            <svg class="icon"><use href="#icon-tool"/></svg>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (count($gallery_images) > 1): ?>
                    <div class="gallery-thumbs">
                        <?php foreach ($gallery_images as $i => $img): ?>
                            <button class="gallery-thumb <?php echo $i === 0 ? 'active' : ''; ?>" data-image-src="<?php echo esc_url($img['url']); ?>" data-gallery-index="<?php echo esc_attr($i); ?>">
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
                            <div class="station-badges">

                                <span class="badge badge--stock in-stock">В наличии</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="station-price-columns">
                        <div class="station-price-column">
                            <span class="station-price-label"><?php echo esc_html($label_topas_s); ?></span>
                            <?php if ($compressors_topas_s): ?>
                                <span class="station-price-compressors"><?php echo esc_html($compressors_topas_s . ' ' . $compressor_word($compressors_topas_s)); ?></span>
                            <?php endif; ?>
                            <span class="price-current"><?php echo $price_topas_s ? number_format($price_topas_s, 0, '.', ' ') . ' ₽' : 'По запросу'; ?></span>
                        </div>
                        <div class="station-price-column">
                            <span class="station-price-label"><?php echo esc_html($label_topas); ?></span>
                            <?php if ($compressors_topas): ?>
                                <span class="station-price-compressors"><?php echo esc_html($compressors_topas . ' ' . $compressor_word($compressors_topas)); ?></span>
                            <?php endif; ?>
                            <span class="price-current"><?php echo $price ? number_format($price, 0, '.', ' ') . ' ₽' : 'По запросу'; ?></span>
                        </div>
                    </div>
                    <p class="price-note">В наличии • Доставка по РФ • Монтаж </p>
                </div>

                <!-- Кнопки -->
                <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', getCarbonFields('theme_phones')[0]['phone_numbers'][0]['phone_number'] ?? '+79083033282'); ?>" class="btn btn--gold">
                    <svg class="icon" width="18" height="18"><use href="#icon-phone"/></svg>
                    Позвонить
                </a>
                <!--  <button class="btn btn--outline js-open-modal">Заказать звонок</button>-->
                <button class="btn btn--text js-scroll-to-calc">Рассчитать монтаж</button>

                <!-- Характеристики -->
                <div class="specs-card">
                    <h3 class="specs-card__title">
                        <svg class="icon" width="20" height="20"><use href="#icon-tool"/></svg>
                        Характеристики
                    </h3>

                    <ul class="specs-list">
                        <?php if ($daily_volume): ?>
                            <li class="spec-row"><span class="spec-label">Производительность</span><span class="spec-value"><?php echo esc_html($daily_volume); ?></span></li>
                        <?php endif; ?>
                        <?php if ($peak_discharge): ?>
                            <li class="spec-row"><span class="spec-label">Залповый сброс</span><span class="spec-value"><?php echo esc_html($peak_discharge); ?> </span></li>
                        <?php endif; ?>
                        <?php if ($power_consumption): ?>
                            <li class="spec-row"><span class="spec-label">Потребление</span><span class="spec-value"><?php echo esc_html($power_consumption); ?></span></li>
                        <?php endif; ?>
                        <?php if ($water_disposal): ?>
                            <li class="spec-row"><span class="spec-label">Способ водоотведения</span><span class="spec-value"><?php echo esc_html($water_disposal); ?></span></li>
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
                        <svg class="icon"><use href="#icon-bolt"/></svg>
                        Эффективность
                    </div>
                    <div class="guarantee-item">
                        <svg class="icon"><use href="#icon-shield"/></svg>
                        Комфорт
                    </div>
                    <div class="guarantee-item">
                        <svg class="icon"><use href="#icon-leaf"/></svg>
                        Экологичность
                    </div>
                </div>

            </aside>
        </div>

        <!-- Описание -->
        <?php if (get_the_content()): ?>
            <section class="station-section">
                <div class="content-block"><?php the_content(); ?></div>
                <?php if ($mounting_dimensions): ?>
                    <div class="mounting-info">
                        <h3 class="section-subtitle">Информация по монтажу</h3>
                        <table class="mounting-info-table">
                            <tbody>
                            <tr>
                                <th>Габариты</th>
                                <td><?php echo esc_html($mounting_dimensions); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
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
            <h2>Закажите бесплатную консультацию инженера с выездом</h2>
            <p></p>

            <ul class="station-cta__steps">
                <li>
                    <svg class="icon" width="18" height="18"><use href="#icon-check"/></svg>
                    Специалист приедет в удобное для вас время в любой населённый пункт Чувашской Республики
                </li>
                <li>
                    <svg class="icon" width="18" height="18"><use href="#icon-check"/></svg>
                    Поможем подобрать подходящую станцию ТОПАС с учётом необходимой производительности
                </li>
                <li>
                    <svg class="icon" width="18" height="18"><use href="#icon-check"/></svg>
                    Определим наиболее удачное место для монтажа и продумaем систему отвода очищенной воды
                </li>
                <li>
                    <svg class="icon" width="18" height="18"><use href="#icon-check"/></svg>
                    Подготовим индивидуальное решение «под ключ» с учётом особенностей участка и ваших требований
                </li>
                <li>
                    <svg class="icon" width="18" height="18"><use href="#icon-check"/></svg>
                    На месте составим точный расчёт стоимости и расскажем о доступных бонусах и выгодных условиях
                </li>
            </ul>
            <button class="btn btn--gold js-open-modal">Заказать бесплатный выезд инженера</button>
            <p class="cta-note" style="margin-top: 20px;">Бесплатно и без обязательств • Конфиденциально • Ответим за 15 минут</p>
        </section>

    </div>
</main>
<!-- Модальное окно -->
<div class="modal modal-premium" id="callback-modal">
    <div class="modal__overlay js-close-modal"></div>
    <div class="modal__content">
        <button class="modal__close js-close-modal" aria-label="Закрыть">
            <svg class="icon"><use href="#icon-close"/></svg>
        </button>
        <div class="modal__header">
            <h3>Заказать <?php the_title(); ?></h3>
            <p>Перезвоним за 5 минут • Консультация бесплатна</p>
        </div>
        <form class="premium-contact-form modal__form" data-form-type="station_order">
            <input type="hidden" name="action" value="premium_form_submit">
            <input type="hidden" name="product_id" value="<?php echo get_the_ID(); ?>">
            <input type="hidden" name="product_name" value="<?php the_title_attribute(); ?>">
            <input type="hidden" name="product_price" value="<?php echo esc_attr($price); ?>">
            <div class="form-group"><input type="text" name="name" placeholder="Ваше имя *" required><span class="form-error"></span></div>
            <div class="form-group"><input type="tel" name="phone" placeholder="+7 (___) ___-__-__ *" required><span class="form-error"></span></div>
            <div class="form-group"><textarea name="comment" rows="3" placeholder="Комментарий (необязательно)"></textarea></div>
            <label class="form-consent"><input type="checkbox" name="consent" required checked> Согласен на обработку персональных данных</label>
            <button type="submit" class="btn btn--gold">Отправить заявку</button>
        </form>
        <p class="modal__note">Нажимая кнопку, вы соглашаетесь с <a href="/privacy-policy" target="_blank">политикой конфиденциальности</a></p>
    </div>
</div>

<!-- Lightbox -->
<div class="lightbox" id="imageLightbox">
    <button class="lightbox__close js-close-lightbox" aria-label="Закрыть"><svg class="icon"><use href="#icon-close"/></svg></button>
    <img src="" alt="" class="lightbox__img" id="lightboxImage">
</div>

<script type="application/json" id="station-gallery-data"><?php echo wp_json_encode(array_column($gallery_images, 'url')); ?></script>

<?php endwhile; get_footer(); ?>
