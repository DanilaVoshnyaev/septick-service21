<?php
/**
 * Карточка модели: главная, каталог, альтернативы.
 *
 * @var array $args id, hidden (bool), heading (h2|h3)
 */
$s = ss_station($args['id']);
$tag = $args['heading'] ?? 'h3';
$variant_line = array();
if ($s['variant']) {
    $variant_line[] = 'исполнение ' . $s['variant'];
}
$variant_line[] = $s['is_topas'] ? ($s['series'] === 'ТОПАС-С' ? 'один компрессор' : 'два компрессора') : $s['brand'];
?>
<article class="ss-card"
         data-ss-card
         data-brand="<?php echo esc_attr($s['brand_key']); ?>"
         data-max="<?php echo (int) $s['people_max']; ?>"
         data-disposal="<?php echo esc_attr($s['disposal_type']); ?>"
         data-price="<?php echo (int) $s['price']; ?>"
         data-depth="<?php echo esc_attr($s['depth']); ?>"
         <?php echo !empty($args['hidden']) ? 'hidden' : ''; ?>>
    <a class="ss-card__media" href="<?php echo esc_url($s['url']); ?>" tabindex="-1" aria-hidden="true">
        <?php if ($s['img']) : ?>
            <img src="<?php echo esc_url($s['img']); ?>" alt="" loading="lazy" decoding="async" width="400" height="300">
        <?php else : ?>
            <span class="ss-card__noimg">Фото модели скоро добавим</span>
        <?php endif; ?>
    </a>
    <div class="ss-card__body">
        <div class="ss-card__meta">
            <span class="ss-card__brand"><?php echo esc_html($s['is_topas'] ? 'ТОПАС' : $s['brand']); ?></span>
            <?php if ($s['in_stock']) : ?>
                <span class="ss-badge ss-badge--ok">В наличии</span>
            <?php else : ?>
                <span class="ss-badge">Срок поставки — по запросу</span>
            <?php endif; ?>
        </div>
        <<?php echo $tag; ?> class="ss-card__title"><a href="<?php echo esc_url($s['url']); ?>"><?php echo esc_html($s['title']); ?></a></<?php echo $tag; ?>>
        <p class="ss-card__variant"><?php echo esc_html(izex_ucfirst(implode(' · ', $variant_line))); ?></p>
        <dl class="ss-specs">
            <div><dt>Пользователей</dt><dd><?php echo esc_html($s['people_text'] ?: '—'); ?></dd></div>
            <div><dt>Производительность</dt><dd><?php echo $s['daily'] ? esc_html(izex_format_station_spec($s['daily'], 'м³/сут')) : '—'; ?></dd></div>
            <div><dt>Залповый сброс</dt><dd><?php echo $s['peak'] ? esc_html(izex_format_station_spec($s['peak'], 'л')) : 'уточняется'; ?></dd></div>
            <div><dt>Отвод воды</dt><dd><?php echo esc_html($s['disposal_short']); ?></dd></div>
        </dl>
        <div class="ss-card__price">
            <?php if ($s['price']) : ?>
                <div><span>Оборудование</span><strong><?php echo esc_html(ss_money($s['price'])); ?></strong></div>
                <div><span>Монтаж</span><strong><?php echo $s['install'] ? 'от ' . esc_html(ss_money($s['install'])) : 'по расчёту'; ?></strong></div>
            <?php else : ?>
                <div class="ss-card__price-ask"><span>Оборудование и монтаж</span><strong>цена по запросу</strong></div>
            <?php endif; ?>
        </div>
        <button type="button" class="ss-btn ss-btn--primary ss-btn--block open-modal"
                data-modal="order"
                data-product="<?php echo esc_attr($s['title']); ?>"
                data-product-id="<?php echo (int) $s['id']; ?>">Получить расчёт монтажа</button>
    </div>
</article>
