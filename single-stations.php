<?php
/**
 * Карточка станции.
 *
 * В карточке ТОПАС-С показываем цену только этой модели. ТОПАС с двумя
 * компрессорами — другая модель, поэтому её цена вынесена в отдельную строку
 * «Модификации», а не стоит рядом как вторая цена того же товара.
 */

get_header();

while (have_posts()) : the_post();

$s = ss_station(get_the_ID());
$company = getCompanyContacts();
$trust = izex_trust();
$equipment = carbon_get_post_meta($s['id'], 'crb_equipment');
$catalog = ss_catalog();

$siblings = array();
$alternatives = array();
foreach ($catalog as $item) {
    if ($item['id'] === $s['id']) {
        continue;
    }
    if ($s['is_topas'] && $item['is_topas'] && $item['model_number'] === $s['model_number']) {
        $siblings[] = $item;
    } elseif ($s['people_max'] && $item['people_max'] && abs($item['people_max'] - $s['people_max']) <= 1
        && ($item['brand_key'] !== $s['brand_key'] || $item['variant'] === $s['variant'])
        && ($item['disposal_type'] === $s['disposal_type'] || $item['disposal_type'] === 'both' || $s['disposal_type'] === 'both')) {
        $alternatives[] = $item;
    }
}
usort($alternatives, function ($a, $b) use ($s) {
    return ($a['brand_key'] === $s['brand_key']) <=> ($b['brand_key'] === $s['brand_key'])
        ?: abs($a['people_max'] - $s['people_max']) <=> abs($b['people_max'] - $s['people_max']);
});
$alternatives = array_slice($alternatives, 0, 3);

$spec_rows = array_filter(array(
    'Марка'              => $s['is_topas'] ? 'ТОПАС' : $s['brand'],
    'Модель'             => $s['title'],
    'Исполнение'         => $s['variant'],
    'Компрессоров'       => $s['is_topas'] ? ($s['series'] === 'ТОПАС-С' ? '1' : '2') : '',
    'Пользователей'      => $s['people_text'],
    'Производительность' => $s['daily'] ? izex_format_station_spec($s['daily'], 'м³/сут') : '',
    'Залповый сброс'     => $s['peak'] ? izex_format_station_spec($s['peak'], 'л') : '',
    'Потребление'        => $s['power'] ? izex_format_station_spec($s['power'], 'кВт·ч/сут') : '',
    'Отвод воды'         => izex_ucfirst($s['disposal_label']),
    'Габариты'           => $s['dimensions'],
));

$pipe = array(
    'Стандарт' => 'подводящая труба на глубине до 80 см',
    'Лонг'     => 'подводящая труба на глубине 80–140 см',
    'Лонг Ус'  => 'подводящая труба на глубине до 240 см',
);
$sections = array(
    'purpose' => 'Назначение',
    'specs'   => 'Характеристики',
    'kit'     => 'Комплектация',
    'install' => 'Монтаж',
    'service' => 'Обслуживание и гарантия',
    'docs'    => 'Документы',
);
if ($alternatives) {
    $sections['compare'] = 'Альтернативы';
}
?>

<main class="ss-product">
    <div class="ss-container">
        <div class="ss-product__top">
            <div class="ss-product__media">
                <?php if ($s['img_large']) : ?>
                    <img src="<?php echo esc_url($s['img_large']); ?>" alt="<?php echo esc_attr($s['title']); ?>" width="800" height="620" fetchpriority="high">
                <?php else : ?>
                    <div class="ss-card__noimg ss-product__noimg">Фото модели скоро добавим</div>
                <?php endif; ?>
            </div>

            <div class="ss-product__summary">
                <p class="ss-card__meta">
                    <span class="ss-card__brand"><?php echo esc_html($s['is_topas'] ? 'ТОПАС' : $s['brand']); ?></span>
                    <?php if ($s['in_stock']) : ?>
                        <span class="ss-badge ss-badge--ok">В наличии</span>
                    <?php else : ?>
                        <span class="ss-badge">Срок поставки — по запросу</span>
                    <?php endif; ?>
                </p>
                <h1 class="ss-h1 ss-h1--page"><?php the_title(); ?></h1>
                <p class="ss-product__variant">
                    <?php
                    $line = array();
                    if ($s['variant']) {
                        $line[] = 'Исполнение ' . $s['variant'];
                    }
                    if ($s['is_topas']) {
                        $line[] = $s['series'] === 'ТОПАС-С' ? 'один компрессор' : 'два компрессора';
                    }
                    $line[] = 'отвод ' . $s['disposal_label'];
                    echo esc_html(izex_ucfirst(implode(' · ', $line)));
                    ?>
                </p>

                <dl class="ss-keyspecs">
                    <div><dt>Пользователей</dt><dd><?php echo esc_html($s['people_text'] ?: '—'); ?></dd></div>
                    <div><dt>Производительность</dt><dd><?php echo $s['daily'] ? esc_html(izex_format_station_spec($s['daily'], 'м³/сут')) : '—'; ?></dd></div>
                    <div><dt>Залповый сброс</dt><dd><?php echo $s['peak'] ? esc_html(izex_format_station_spec($s['peak'], 'л')) : 'уточняется'; ?></dd></div>
                    <div><dt>Потребление</dt><dd><?php echo $s['power'] ? esc_html(izex_format_station_spec($s['power'], 'кВт·ч/сут')) : 'уточняется'; ?></dd></div>
                </dl>

                <div class="ss-pricebox">
                    <?php if ($s['price']) : ?>
                        <div class="ss-pricebox__row">
                            <span>Цена оборудования</span>
                            <strong class="ss-pricebox__main"><?php echo esc_html(ss_money($s['price'])); ?></strong>
                        </div>
                        <div class="ss-pricebox__row">
                            <span>Монтаж</span>
                            <strong><?php echo $s['install'] ? 'от ' . esc_html(ss_money($s['install'])) : 'по расчёту'; ?></strong>
                        </div>
                        <p class="ss-muted">Доставка и дополнительные работы зависят от участка и идут в смете отдельными строками.</p>
                    <?php else : ?>
                        <div class="ss-pricebox__row">
                            <span>Оборудование и монтаж</span>
                            <strong>цена по запросу</strong>
                        </div>
                        <p class="ss-muted">Уточним цену и срок поставки у производителя и пришлём расчёт с монтажом.</p>
                    <?php endif; ?>
                    <div class="ss-actions">
                        <button type="button" class="ss-btn ss-btn--primary ss-btn--lg open-modal" data-modal="order"
                                data-product="<?php echo esc_attr($s['title']); ?>" data-product-id="<?php echo (int) $s['id']; ?>">Получить расчёт монтажа</button>
                        <a class="ss-btn ss-btn--secondary ss-btn--lg" href="tel:<?php echo esc_attr($company['phone_clean']); ?>">Позвонить</a>
                    </div>
                </div>
            </div>
        </div>

        <nav class="ss-anchors" aria-label="Разделы карточки">
            <?php foreach ($sections as $anchor => $label) : ?>
                <a href="#<?php echo esc_attr($anchor); ?>"><?php echo esc_html($label); ?></a>
            <?php endforeach; ?>
        </nav>

        <section class="ss-psec" id="purpose">
            <h2 class="ss-h2">Назначение и ограничения</h2>
            <ul class="ss-list ss-list--dots">
                <?php if ($s['people_max']) : ?>
                    <li>Для дома, где постоянно живут <?php echo esc_html(str_replace(' чел.', '', $s['people_text'])); ?> человек<?php echo $s['daily'] ? ': переработка до ' . esc_html(izex_format_station_spec($s['daily'], 'м³')) . ' стоков в сутки' : ''; ?>.</li>
                <?php endif; ?>
                <?php if ($s['peak']) : ?>
                    <li>Залповый сброс до <?php echo esc_html(izex_format_station_spec($s['peak'], 'л')); ?> — столько воды станция принимает за один раз. Если в доме большая ванна или джакузи, сравните её объём с этим значением.</li>
                <?php endif; ?>
                <?php if ($s['is_topas'] && isset($pipe[$s['variant']])) : ?>
                    <li>Исполнение «<?php echo esc_html($s['variant']); ?>»: <?php echo esc_html($pipe[$s['variant']]); ?>. Для другой глубины нужно другое исполнение.</li>
                <?php endif; ?>
                <li><?php
                    if ($s['disposal_type'] === 'gravity') {
                        echo 'Отвод самотёком: на участке должно быть куда отвести очищенную воду — канава, дренаж или грунт, который хорошо впитывает воду. Если некуда, нужна модель с насосом.';
                    } elseif ($s['disposal_type'] === 'pump') {
                        echo 'Принудительный отвод: насос поднимает очищенную воду, поэтому её можно отвести выше или дальше — например, при близких грунтовых водах или глине.';
                    } else {
                        echo 'Отвод самотёком или насосом — зависит от комплектации, подбираем под участок.';
                    }
                ?></li>
                <li>Станции нужно электропитание: подача воздуха работает постоянно.</li>
            </ul>
        </section>

        <section class="ss-psec" id="specs">
            <h2 class="ss-h2">Характеристики</h2>
            <table class="ss-table">
                <tbody>
                <?php foreach ($spec_rows as $label => $value) : ?>
                    <tr><th scope="row"><?php echo esc_html($label); ?></th><td><?php echo esc_html($value); ?></td></tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (!$s['is_topas']) : ?>
                <p class="ss-note">Характеристики — по данным производителя для этого исполнения. Перед заказом сверяем с паспортом.</p>
            <?php endif; ?>
        </section>

        <section class="ss-psec" id="kit">
            <h2 class="ss-h2">Комплектация и опции</h2>
            <?php if (!empty($equipment) && is_array($equipment)) : ?>
                <ul class="ss-list ss-list--dots">
                    <?php foreach ($equipment as $row) : ?>
                        <li><?php echo esc_html($row['item_name'] ?? ''); ?> — <?php echo esc_html($row['item_qty'] ?? '1'); ?> шт.</li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>Станция поставляется в заводской комплектации. Точный состав комплекта пришлём вместе с расчётом.</p>
            <?php endif; ?>

            <?php $has_topas_pair = $s['is_topas'] && $s['series'] === 'ТОПАС-С' && $s['price_topas']; ?>
            <?php if ($siblings || $has_topas_pair) : ?>
                <h3 class="ss-h3">Другие исполнения и модификации</h3>
                <div class="ss-tablewrap">
                    <table class="ss-table ss-table--list">
                        <thead><tr><th scope="col">Модель</th><th scope="col">Чем отличается</th><th scope="col">Оборудование</th></tr></thead>
                        <tbody>
                        <?php if ($has_topas_pair) : ?>
                            <tr>
                                <td><?php echo esc_html(str_replace('ТОПАС-С', 'ТОПАС', $s['title'])); ?></td>
                                <td>Отдельная модель: два компрессора вместо одного, производительность та же</td>
                                <td><?php echo esc_html(ss_money($s['price_topas'])); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($siblings as $item) : ?>
                            <tr>
                                <td><a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['title']); ?></a></td>
                                <td><?php echo esc_html('Исполнение ' . $item['variant'] . ', отвод ' . $item['disposal_label']); ?></td>
                                <td><?php echo $item['price'] ? esc_html(ss_money($item['price'])) : 'по запросу'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <section class="ss-psec" id="install">
            <h2 class="ss-h2">Описание и условия монтажа</h2>
            <div class="ss-prose"><?php the_content(); ?></div>
            <div class="ss-box ss-box--tint">
                <p>Что входит в стандартный монтаж и что может изменить смету — <a href="<?php echo esc_url(home_url('/#cost')); ?>">в разделе «Стоимость под ключ»</a>. Точную смету инженер составляет после бесплатного выезда.</p>
            </div>
        </section>

        <section class="ss-psec" id="service">
            <h2 class="ss-h2">Обслуживание и гарантия</h2>
            <div class="ss-grid ss-grid--2">
                <div class="ss-box">
                    <h3 class="ss-h3">Обслуживание</h3>
                    <p>Несколько раз в год из станции удаляют избыточный ил и проверяют оборудование — порядок указан в паспорте. Можно делать самостоятельно или заказать у нас.</p>
                    <a class="ss-link" href="/obsluzhivanie-septikov-lyubyh-marok/">Об обслуживании станций</a>
                </div>
                <div class="ss-box">
                    <h3 class="ss-h3">Гарантия</h3>
                    <ul class="ss-list ss-list--dots">
                        <li>Корпус: <?php echo $trust['warranty_body'] ? esc_html(izex_years($trust['warranty_body'])) : 'по паспорту производителя'; ?></li>
                        <li>Электрооборудование: <?php echo $trust['warranty_compressor'] ? esc_html(izex_years($trust['warranty_compressor'])) : 'по паспорту производителя'; ?></li>
                        <li>Монтаж: <?php echo $trust['warranty_works'] ? esc_html(izex_years($trust['warranty_works'])) : 'по договору'; ?></li>
                    </ul>
                    <p class="ss-muted">Сроки для вашей станции указываем в договоре.</p>
                </div>
            </div>
        </section>

        <section class="ss-psec" id="docs">
            <h2 class="ss-h2">Паспорт и монтажная схема</h2>
            <?php if ($s['pdf']) : ?>
                <a class="ss-doc" href="<?php echo esc_url($s['pdf']); ?>" target="_blank" rel="noopener">
                    <span class="ss-doc__icon" aria-hidden="true">PDF</span>
                    <span><strong>Паспорт <?php echo esc_html($s['title']); ?></strong><span class="ss-muted">Документ производителя, откроется в новой вкладке</span></span>
                </a>
            <?php else : ?>
                <p>Паспорт и монтажную схему этой модели пришлём по запросу вместе с расчётом.</p>
            <?php endif; ?>
        </section>

        <?php if ($alternatives) : ?>
            <section class="ss-psec" id="compare">
                <h2 class="ss-h2">Подходящие альтернативы</h2>
                <p>Станции на такое же число пользователей — сравните главные параметры.</p>
                <div class="ss-tablewrap">
                    <table class="ss-table ss-table--compare">
                        <thead>
                        <tr>
                            <th scope="col">Модель</th>
                            <th scope="col">Пользователей</th>
                            <th scope="col">Производительность</th>
                            <th scope="col">Залповый сброс</th>
                            <th scope="col">Отвод</th>
                            <th scope="col">Оборудование</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach (array_merge(array($s), $alternatives) as $i => $item) : ?>
                            <tr<?php echo $i === 0 ? ' class="is-current"' : ''; ?>>
                                <th scope="row"><?php echo $i === 0 ? esc_html($item['title']) . ' <span class="ss-muted">(эта модель)</span>' : '<a href="' . esc_url($item['url']) . '">' . esc_html($item['title']) . '</a>'; ?></th>
                                <td><?php echo esc_html($item['people_text'] ?: '—'); ?></td>
                                <td><?php echo $item['daily'] ? esc_html(izex_format_station_spec($item['daily'], 'м³/сут')) : '—'; ?></td>
                                <td><?php echo $item['peak'] ? esc_html(izex_format_station_spec($item['peak'], 'л')) : '—'; ?></td>
                                <td><?php echo esc_html($item['disposal_label']); ?></td>
                                <td><?php echo $item['price'] ? esc_html(ss_money($item['price'])) : 'по запросу'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php endif; ?>

        <section class="ss-psec ss-split ss-product__cta">
            <div class="ss-split__intro">
                <h2 class="ss-h2">Расчёт монтажа <?php echo esc_html($s['title']); ?></h2>
                <p>Оставьте телефон — уточним условия участка и пришлём расчёт оборудования, монтажа и доставки.</p>
            </div>
            <div class="ss-panel">
                <form class="premium-contact-form ss-form" data-form-type="station_order" novalidate>
                    <input type="text" name="hp_email" class="form-hp" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <input type="hidden" name="product_id" value="<?php echo (int) $s['id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo esc_attr($s['title']); ?>">
                    <div class="form-group">
                        <label class="ss-field__label" for="station-phone">Телефон</label>
                        <input class="ss-input" id="station-phone" type="tel" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel" inputmode="tel" required>
                        <span class="form-error" role="alert"></span>
                    </div>
                    <div class="form-group">
                        <label class="ss-field__label" for="station-name">Имя <span class="ss-field__opt">— необязательно</span></label>
                        <input class="ss-input" id="station-name" type="text" name="name" autocomplete="given-name">
                        <span class="form-error" role="alert"></span>
                    </div>
                    <?php echo ss_consent_field(); ?>
                    <button type="submit" class="ss-btn ss-btn--primary ss-btn--block ss-btn--lg">Получить расчёт монтажа</button>
                </form>
            </div>
        </section>
    </div>
</main>

<?php endwhile; get_footer(); ?>
