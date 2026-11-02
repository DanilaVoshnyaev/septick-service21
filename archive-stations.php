<?php
/**
 * Каталог станций всех марок с фильтрами.
 *
 * Фильтрует браузер (redesign.js), сервер по тем же параметрам скрывает
 * неподходящие карточки — каталог работает и без JavaScript. Отфильтрованные
 * адреса закрыты от индексации в inc/redesign.php.
 */
get_header();

$state = ss_filter_state();
$options = ss_filter_options();
$catalog = ss_catalog();
$base_url = get_post_type_archive_link('stations');

if (($state['sort'] ?? '') === 'price_desc') {
    usort($catalog, function ($a, $b) { return ($b['price'] ?: -1) <=> ($a['price'] ?: -1); });
} elseif (($state['sort'] ?? '') === 'users') {
    usort($catalog, function ($a, $b) { return $a['people_max'] <=> $b['people_max'] ?: ($a['price'] ?: PHP_INT_MAX) <=> ($b['price'] ?: PHP_INT_MAX); });
}

$shown = 0;
foreach ($catalog as $s) {
    $shown += ss_station_matches($s, $state) ? 1 : 0;
}
$active = count(array_diff_key($state, array('sort' => 1)));

$groups = array(
    'brand'    => 'Производитель',
    'users'    => 'Сколько человек живёт',
    'disposal' => 'Отвод очищенной воды',
    'price'    => 'Цена оборудования',
    'depth'    => 'Глубина трубы из дома',
);
?>
<main class="ss-catalog">
    <div class="ss-container">
        <header class="ss-catalog__head">
            <h1 class="ss-h1 ss-h1--page">Каталог септиков и станций очистки</h1>
            <p class="ss-lead">ТОПАС и ещё семь марок. Цены оборудования — по каталогу, монтаж рассчитываем после выезда инженера.</p>
        </header>

        <div class="ss-catalog__layout" data-ss-catalog>
            <form class="ss-filters" id="ss-filters" action="<?php echo esc_url($base_url); ?>" method="get" data-ss-filters aria-label="Фильтры каталога">
                <div class="ss-filters__head">
                    <p class="ss-filters__title">Фильтры</p>
                    <button type="button" class="ss-iconbtn ss-filters__close" aria-label="Закрыть фильтры" data-ss-filters-close>
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>
                <?php foreach ($groups as $key => $label) : ?>
                    <fieldset class="ss-filters__group">
                        <legend><?php echo esc_html($label); ?></legend>
                        <?php if ($key === 'users') : ?>
                            <p class="ss-filters__hint">Покажем станции с запасом до двух человек</p>
                        <?php endif; ?>
                        <label class="ss-chip">
                            <input type="radio" name="<?php echo esc_attr($key); ?>" value="" <?php checked(empty($state[$key])); ?>>
                            <span>Все</span>
                        </label>
                        <?php foreach ($options[$key] as $value => $text) : ?>
                            <label class="ss-chip">
                                <input type="radio" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" <?php checked(($state[$key] ?? '') === (string) $value); ?>>
                                <span><?php echo esc_html($text); ?></span>
                            </label>
                        <?php endforeach; ?>
                        <?php if ($key === 'depth') : ?>
                            <p class="ss-filters__hint">По исполнениям ТОПАС. Для других марок глубину подбираем по паспорту — при выборе они скрываются</p>
                        <?php endif; ?>
                        <?php if ($key === 'price') : ?>
                            <p class="ss-filters__hint">Модели с ценой по запросу при выборе цены скрываются</p>
                        <?php endif; ?>
                    </fieldset>
                <?php endforeach; ?>
                <div class="ss-filters__foot">
                    <noscript><button type="submit" class="ss-btn ss-btn--primary ss-btn--block">Показать</button></noscript>
                    <button type="button" class="ss-btn ss-btn--primary ss-btn--block ss-filters__apply" data-ss-filters-close>Показать <span data-ss-count><?php echo (int) $shown; ?></span></button>
                    <a class="ss-btn ss-btn--ghost ss-btn--block" href="<?php echo esc_url($base_url); ?>" data-ss-reset>Сбросить фильтры</a>
                </div>
            </form>

            <div class="ss-catalog__main">
                <div class="ss-toolbar">
                    <button type="button" class="ss-btn ss-btn--secondary ss-toolbar__filters" aria-controls="ss-filters" aria-expanded="false" data-ss-filters-open>
                        Фильтры<span class="ss-toolbar__badge" data-ss-active <?php echo $active ? '' : 'hidden'; ?>><?php echo (int) $active; ?></span>
                    </button>
                    <p class="ss-toolbar__count" aria-live="polite">Найдено: <strong data-ss-count><?php echo (int) $shown; ?></strong></p>
                    <label class="ss-toolbar__sort">
                        <span>Сортировка</span>
                        <select class="ss-input ss-input--sm" name="sort" form="ss-filters" data-ss-sort>
                            <option value="" <?php selected(empty($state['sort'])); ?>>По марке и цене</option>
                            <option value="price_desc" <?php selected(($state['sort'] ?? '') === 'price_desc'); ?>>Сначала дороже</option>
                            <option value="users" <?php selected(($state['sort'] ?? '') === 'users'); ?>>По числу пользователей</option>
                        </select>
                    </label>
                </div>

                <div class="ss-grid ss-grid--catalog" data-ss-grid>
                    <?php foreach ($catalog as $s) {
                        get_template_part('template-parts/station-card-ss', null, array(
                            'id'      => $s['id'],
                            'heading' => 'h2',
                            'hidden'  => !ss_station_matches($s, $state),
                        ));
                    } ?>
                </div>

                <div class="ss-empty" data-ss-empty <?php echo $shown ? 'hidden' : ''; ?>>
                    <p class="ss-h3">Таких моделей в каталоге нет</p>
                    <p>Измените условия или сбросьте фильтры. Можно и не выбирать самому — подберём станцию по телефону.</p>
                    <div class="ss-actions">
                        <a class="ss-btn ss-btn--secondary" href="<?php echo esc_url($base_url); ?>" data-ss-reset>Сбросить фильтры</a>
                        <button type="button" class="ss-btn ss-btn--primary open-modal" data-modal="order">Получить расчёт</button>
                    </div>
                </div>
            </div>
        </div>

        <section class="ss-catalog__help">
            <div>
                <h2 class="ss-h3">Не знаете, какая станция нужна?</h2>
                <p>Ответьте на несколько вопросов о доме и участке — предложим подходящие модели.</p>
            </div>
            <a class="ss-btn ss-btn--primary" href="/podbor-septika/">Подобрать септик</a>
        </section>

        <section class="ss-prose ss-catalog__about">
            <h2 class="ss-h3">Как читать названия станций ТОПАС</h2>
            <p>Цифра в названии — сколько человек может постоянно пользоваться станцией. ТОПАС — аэрационная станция глубокой биологической очистки: стоки перерабатывают аэробные бактерии, которым компрессор подаёт воздух. Откачка ассенизатором не нужна — избыточный ил удаляют при плановом обслуживании.</p>
            <ul>
                <li><strong>ТОПАС и ТОПАС-С.</strong> В ТОПАС два компрессора, работают попеременно, в ТОПАС-С — один. Производительность моделей с одной цифрой одинаковая, цены разные.</li>
                <li><strong>«Пр»</strong> — принудительный отвод очищенной воды насосом, если самотёком её отвести некуда.</li>
                <li><strong>«Лонг»</strong> — для трубы, которая входит на глубине 80–140 см, <strong>«Лонг Ус»</strong> — до 240 см. Обычное исполнение рассчитано на трубу до 80 см.</li>
            </ul>
        </section>
    </div>
</main>

<?php get_footer(); ?>
