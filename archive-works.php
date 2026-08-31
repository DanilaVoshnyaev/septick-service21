<?php
/**
 * Архив выполненных работ «Наши работы» (ТЗ 4.3).
 */
get_header();

$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$works = new WP_Query(array(
    'post_type'      => 'works',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'post_status'    => 'publish',
));

// Список уникальных моделей для фильтра.
$models = array();
if ($works->have_posts()) {
    foreach ($works->posts as $p) {
        $m = carbon_get_post_meta($p->ID, 'crb_work_model');
        if ($m && !in_array($m, $models, true)) {
            $models[] = $m;
        }
    }
}
?>
<main class="works-archive">
    <div class="container">
        <header class="works-archive__head">
            <h1 class="works-archive__title">Наши работы</h1>
            <p class="works-archive__subtitle">Более 1000 выполненных монтажей станций ТОПАС. Реальные объекты в Чебоксарах, Новочебоксарске и по Чувашии.</p>
        </header>

        <?php if ($works->have_posts()) : ?>

            <?php if (count($models) > 1) : ?>
                <div class="works-filter" role="group" aria-label="Фильтр по модели">
                    <button type="button" class="works-filter__btn is-active" data-filter="*">Все</button>
                    <?php foreach ($models as $m) : ?>
                        <button type="button" class="works-filter__btn" data-filter="<?php echo esc_attr($m); ?>"><?php echo esc_html($m); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="works-grid" data-works-grid>
                <?php while ($works->have_posts()) : $works->the_post(); ?>
                    <?php izex_render_work_card(get_the_ID()); ?>
                <?php endwhile; ?>
            </div>

            <?php
            $big = 999999999;
            echo paginate_links(array(
                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'    => '?paged=%#%',
                'current'   => max(1, $paged),
                'total'     => $works->max_num_pages,
                'prev_text' => '←',
                'next_text' => '→',
                'type'      => 'list',
            ));
            ?>

        <?php else : ?>
            <p class="works-archive__empty">Работы скоро появятся. Уже выполнили более 1000 монтажей — фотоотчёты добавляются.</p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>
</main>

<?php if (count($models) > 1) : ?>
<script>
(function () {
    var grid = document.querySelector('[data-works-grid]');
    var btns = document.querySelectorAll('.works-filter__btn');
    if (!grid) return;
    Array.prototype.forEach.call(btns, function (btn) {
        btn.addEventListener('click', function () {
            var f = btn.getAttribute('data-filter');
            Array.prototype.forEach.call(btns, function (b) { b.classList.toggle('is-active', b === btn); });
            Array.prototype.forEach.call(grid.querySelectorAll('.work-card'), function (card) {
                card.style.display = (f === '*' || card.getAttribute('data-model') === f) ? '' : 'none';
            });
        });
    });
})();
</script>
<?php endif; ?>

<?php get_footer(); ?>
