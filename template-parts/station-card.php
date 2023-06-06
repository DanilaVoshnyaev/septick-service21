<?php
/**
 * Карточка станции в каталоге — светлый премиум-стиль
 */

$price = carbon_get_post_meta(get_the_ID(), 'crb_price');
$old_price = carbon_get_post_meta(get_the_ID(), 'crb_old_price');
$people = carbon_get_post_meta(get_the_ID(), 'crb_people_count_text');
$is_hit = carbon_get_post_meta(get_the_ID(), 'crb_is_hit');
$is_new = carbon_get_post_meta(get_the_ID(), 'crb_is_new');
$in_stock = carbon_get_post_meta(get_the_ID(), 'crb_in_stock');

// Спецхарактеристики
$daily_volume = carbon_get_post_meta(get_the_ID(), 'crb_daily_volume');
$peak_discharge = carbon_get_post_meta(get_the_ID(), 'crb_peak_discharge');
$power_consumption = carbon_get_post_meta(get_the_ID(), 'crb_power_consumption');
?>

<article class="station-card" itemscope itemtype="https://schema.org/Product">

    <!-- Бейджи -->
    <div class="station-card__badges">
        <?php if ($is_hit) : ?>
            <span class="badge badge--hit">🔥 Хит</span>
        <?php endif; ?>
        <?php if ($is_new) : ?>
            <span class="badge badge--new">✨ Новинка</span>
        <?php endif; ?>
        <?php if (!$in_stock) : ?>
            <span class="badge badge--out">Нет в наличии</span>
        <?php endif; ?>
    </div>

    <!-- Изображение -->
    <a href="<?php the_permalink(); ?>" class="station-card__image-wrap">
        <div class="station-card__image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium_large', [
                    'loading' => 'lazy',
                    'alt' => get_the_title(),
                    'class' => 'station-card__img'
                ]); ?>
            <?php else : ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/no-image.jpg"
                     alt="<?php the_title_attribute(); ?>"
                     class="station-card__img"
                     loading="lazy">
            <?php endif; ?>
        </div>
    </a>

    <!-- Контент -->
    <div class="station-card__body">

        <!-- Заголовок -->
        <h3 class="station-card__title">
            <a href="<?php the_permalink(); ?>" itemprop="name">
                <?php the_title(); ?>
            </a>
        </h3>

        <!-- Количество человек -->
        <?php if ($people) : ?>
            <div class="station-card__people">
                <span class="icon">👥</span>
                <span><?php echo esc_html($people); ?></span>
            </div>
        <?php endif; ?>

        <!-- Характеристики -->
        <ul class="station-card__specs">
            <?php if ($daily_volume) : ?>
                <li class="spec-item">
                    <span class="spec-icon">📊</span>
                    <span class="spec-value"><?php echo esc_html($daily_volume); ?></span>
                    <span class="spec-label">м³/сутки</span>
                </li>
            <?php endif; ?>
            <?php if ($peak_discharge) : ?>
                <li class="spec-item">
                    <span class="spec-icon">💧</span>
                    <span class="spec-value"><?php echo esc_html($peak_discharge); ?></span>
                    <span class="spec-label">л залповый</span>
                </li>
            <?php endif; ?>
            <?php if ($power_consumption) : ?>
                <li class="spec-item">
                    <span class="spec-icon">⚡</span>
                    <span class="spec-value"><?php echo esc_html($power_consumption); ?></span>
                    <span class="spec-label">кВт/сутки</span>
                </li>
            <?php endif; ?>
        </ul>

        <!-- Цена -->
        <div class="station-card__price-block">
            <?php if ($old_price && $old_price > $price) : ?>
                <span class="price-old"><?php echo number_format($old_price, 0, '.', ' '); ?> ₽</span>
                <span class="price-discount">
                    -<?php echo round((($old_price - $price) / $old_price) * 100); ?>%
                </span>
            <?php endif; ?>
            <div class="price-current-wrap" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                <meta itemprop="priceCurrency" content="RUB">
                <meta itemprop="price" content="<?php echo esc_attr($price); ?>">
                <span class="price-current">
                    <?php echo number_format($price, 0, '.', ' '); ?> ₽
                </span>
                <link itemprop="availability" href="<?php echo $in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'; ?>">
            </div>
        </div>

        <!-- Кнопки -->
        <div class="station-card__actions">
            <a href="<?php the_permalink(); ?>" class="btn btn--outline">
                Подробнее
            </a>
            <button class="btn btn--primary js-order-popup"
                    data-product="<?php the_title_attribute(); ?>"
                    data-price="<?php echo esc_attr($price); ?>">
                📞 Заказать
            </button>
        </div>

    </div>
</article>