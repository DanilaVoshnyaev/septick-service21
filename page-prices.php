<?php
/**
 * Template Name: Цены
 *
 * Страница цен (ТЗ 4.4): таблица по моделям (оборудование / монтаж / под ключ),
 * блоки «входит в монтаж» и «оплачивается отдельно», прайс на обслуживание.
 */
get_header();

$rows = izex_get_prices_table();
$lists = izex_get_prices_lists();

$money = function ($v) {
    $v = (int) $v;
    return $v > 0 ? number_format($v, 0, '.', ' ') . ' ₽' : '—';
};
?>
<main class="prices-page">
    <div class="container">

        <header class="prices-head">
            <h1 class="prices-head__title"><?php echo esc_html(get_the_title() ?: 'Цены на септики ТОПАС «под ключ»'); ?></h1>
            <p class="prices-head__subtitle">Стоимость оборудования, монтажа и решения «под ключ». Итоговая цена фиксируется после бесплатного выезда инженера.</p>
        </header>

        <?php while (have_posts()) : the_post(); if (get_the_content()) : ?>
            <div class="prices-intro"><?php the_content(); ?></div>
        <?php endif; endwhile; ?>

        <?php if (!empty($rows)) : ?>
            <section class="prices-section">
                <h2 class="prices-section__title">Стоимость по моделям</h2>
                <div class="prices-table-wrap">
                    <table class="prices-table">
                        <thead>
                            <tr>
                                <th>Модель</th>
                                <th>Оборудование</th>
                                <th>Монтаж</th>
                                <th>Под ключ</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $r) : ?>
                                <tr>
                                    <td data-label="Модель"><a href="<?php echo esc_url($r['url']); ?>"><?php echo esc_html($r['title']); ?></a></td>
                                    <td data-label="Оборудование"><?php echo esc_html($money($r['equipment'])); ?></td>
                                    <td data-label="Монтаж"><?php echo esc_html($money($r['install'])); ?></td>
                                    <td data-label="Под ключ" class="prices-table__turnkey"><?php echo esc_html($money($r['turnkey'])); ?></td>
                                    <td data-label=""><button type="button" class="btn-card btn-gold open-modal" data-modal="order" data-product="<?php echo esc_attr($r['title']); ?>">Заказать</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="prices-note">Цены ориентировочные. Монтаж указан для стандартных условий; надбавки за глубину врезки, принудительное водоотведение и высокий УГВ рассчитываются индивидуально — воспользуйтесь <a href="<?php echo esc_url(get_post_type_archive_link('stations')); ?>#calc">калькулятором</a>.</p>
            </section>
        <?php endif; ?>

        <section class="prices-section">
            <h2 class="prices-section__title">Что входит в монтаж «под ключ»</h2>
            <div class="prices-cols">
                <div class="prices-col prices-col--in">
                    <h3 class="prices-col__title">Входит в стандартный монтаж</h3>
                    <ul class="prices-list prices-list--in">
                        <?php foreach ($lists['included'] as $item) : ?>
                            <li><?php echo esc_html($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="prices-col prices-col--out">
                    <h3 class="prices-col__title">Оплачивается отдельно</h3>
                    <ul class="prices-list prices-list--out">
                        <?php foreach ($lists['extra'] as $item) : ?>
                            <li><?php echo esc_html($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>

        <?php if (!empty($lists['maintenance'])) : ?>
            <section class="prices-section">
                <h2 class="prices-section__title">Стоимость сервисного обслуживания</h2>
                <div class="prices-table-wrap">
                    <table class="prices-table prices-table--service">
                        <thead>
                            <tr><th>Модель</th><th>Разовое обслуживание</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lists['maintenance'] as $m) : ?>
                                <tr>
                                    <td data-label="Модель"><?php echo esc_html($m['model']); ?></td>
                                    <td data-label="Обслуживание"><?php echo esc_html($money($m['price'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php endif; ?>

        <div class="prices-cta">
            <h2 class="prices-cta__title">Нужен точный расчёт для вашего участка?</h2>
            <p class="prices-cta__text">Инженер бесплатно выезжает, замеряет и составляет смету без скрытых доплат.</p>
            <button type="button" class="btn-footer open-modal" data-modal="engineer">Вызвать инженера бесплатно</button>
        </div>

    </div>
</main>
<?php get_footer(); ?>
