<?php
/**
 * Карточка станции — вёрстка по прототипу заказчика.
 *
 * Отличия от прежней карточки: штрихованная подложка под фото, моно-микролейблы,
 * характеристики строками с разделителями, цена «под ключ» рядом с ценой станции
 * и кнопка сравнения прямо на фото.
 *
 * Используется на главной и в архиве станций. Данные берутся из
 * izex_pro_station_data(), data-атрибуты нужны мгновенному фильтру (catalog-instant.js).
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

$station_id = isset($args['id']) ? (int) $args['id'] : get_the_ID();
$d = izex_pro_station_data($station_id);
?>

<article class="pro-card"
         data-station="<?php echo esc_attr($station_id); ?>"
         data-people-min="<?php echo esc_attr($d['people_min']); ?>"
         data-people-max="<?php echo esc_attr($d['people_max']); ?>"
         data-disposal="<?php echo esc_attr($d['disposal']); ?>"
         data-price="<?php echo esc_attr($d['price']); ?>"
         itemscope itemtype="https://schema.org/Product">

    <div class="pro-card__media">
        <span class="pro-card__ghost"><?php echo esc_html($d['title']); ?></span>
        <a href="<?php echo esc_url($d['url']); ?>" aria-label="<?php echo esc_attr($d['title']); ?>">
            <?php
            // the_post_thumbnail вместо <img src>: WordPress сам добавит srcset и
            // width/height — браузер возьмёт подходящий размер и не «прыгнет» вёрсткой.
            echo get_the_post_thumbnail($station_id, 'medium_large', array(
                'loading'  => 'lazy',
                'decoding' => 'async',
                'alt'      => $d['title'] . ' — септик ТОПАС',
                'itemprop' => 'image',
            ));
            ?>
        </a>
        <span class="pro-card__badge">В наличии</span>
        <?php if ($d['is_hit']) : ?>
            <span class="pro-card__badge pro-card__badge--hit">Хит продаж</span>
        <?php endif; ?>
        <button type="button"
                class="pro-card__cmp js-compare-toggle"
                data-compare-id="<?php echo esc_attr($station_id); ?>"
                aria-pressed="false">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M7 16V4M7 4 3 8M7 4l4 4M17 8v12M17 20l4-4M17 20l-4-4"/>
            </svg>
            <span class="station-compare__label">Сравнить</span>
        </button>
    </div>

    <div class="pro-card__body">
        <div class="pro-card__head">
            <h3 class="pro-card__title">
                <a href="<?php echo esc_url($d['url']); ?>" itemprop="name"><?php echo esc_html($d['title']); ?></a>
            </h3>
            <?php if ($d['people_text']) : ?>
                <span class="pro-card__people"><?php echo esc_html($d['people_text']); ?></span>
            <?php endif; ?>
        </div>

        <?php if (!empty($d['specs'])) : ?>
            <ul class="pro-card__specs">
                <?php foreach ($d['specs'] as $spec) : ?>
                    <li>
                        <span><?php echo esc_html($spec['label']); ?></span>
                        <strong><?php echo esc_html($spec['value']); ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div class="pro-card__foot">
        <div class="pro-card__prices" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
            <meta itemprop="priceCurrency" content="RUB">
            <meta itemprop="price" content="<?php echo esc_attr($d['price']); ?>">
            <link itemprop="availability" href="https://schema.org/InStock">
            <div>
                <div class="pro-eyebrow">Станция ТОПАС-С</div>
                <?php if ($d['old_price']) : ?>
                    <span class="pro-card__price-old"><?php echo esc_html(izex_pro_money($d['old_price'])); ?></span>
                <?php endif; ?>
                <div class="pro-card__price-value"><?php echo esc_html(izex_pro_money($d['price'])); ?></div>
            </div>
            <?php if ($d['turnkey']) : ?>
                <div class="pro-card__turnkey">
                    ≈ станция + монтаж
                    <b><?php echo esc_html(izex_pro_money($d['turnkey'])); ?></b>
                </div>
            <?php endif; ?>
        </div>

        <div class="pro-card__actions">
            <a href="<?php echo esc_url($d['url']); ?>" class="pro-btn pro-btn--ghost">Подробнее</a>
            <button type="button"
                    class="pro-btn pro-btn--solid open-modal"
                    data-modal="order"
                    data-product="<?php echo esc_attr($d['title']); ?>">Купить в 1 клик</button>
        </div>
    </div>
</article>
