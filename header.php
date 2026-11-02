<?php
/**
 * Шапка сайта.
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

$company = getCompanyContacts();
$nav = array(
    array('/stations/', 'Каталог'),
    array('/montazh-septika-lyuboj-marki/', 'Монтаж'),
    array('/obsluzhivanie-septikov-lyubyh-marok/', 'Обслуживание'),
    array('/about/', 'О компании'),
    array('/contacts/', 'Контакты'),
);
if (izex_has_published_works()) {
    array_splice($nav, 2, 0, array(array(get_post_type_archive_link('works'), 'Наши работы')));
}
$current = trailingslashit(strtok($_SERVER['REQUEST_URI'] ?? '/', '?'));
$phone_icon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-icon" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/favicon-96x96.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon.png">
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/site.webmanifest">
    <meta name="theme-color" content="#ffffff">
    <?php wp_head(); ?>
</head>

<body <?php body_class('ss'); ?>>
<?php wp_body_open(); ?>
<a class="ss-skip" href="#ss-main">Перейти к содержимому</a>
<header class="ss-header" id="site-header-premium">
    <div class="ss-container ss-header__inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="ss-header__logo">
            <img src="<?php echo esc_url(assets('/images/logo-transparent-216.png')); ?>"
                 alt="Сервис Септик21 — главная" width="108" height="49" fetchpriority="high" decoding="async">
        </a>
        <span class="ss-header__region">Чебоксары и Чувашия</span>

        <nav class="ss-nav" aria-label="Основное меню">
            <ul>
                <?php foreach ($nav as $item) : ?>
                    <li><a href="<?php echo esc_url($item[0]); ?>"<?php echo strpos($current, $item[0]) === 0 ? ' aria-current="page"' : ''; ?>><?php echo esc_html($item[1]); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="ss-header__actions">
            <a href="tel:<?php echo esc_attr($company['phone_clean']); ?>" class="ss-header__phone">
                <span class="ss-header__phone-num"><?php echo esc_html($company['phone']); ?></span>
                <span class="ss-header__hours"><?php echo esc_html($company['work_time']); ?></span>
            </a>
            <button type="button" class="ss-btn ss-btn--primary ss-btn--md ss-header__cta open-modal" data-modal="order">Получить расчёт</button>
            <a href="tel:<?php echo esc_attr($company['phone_clean']); ?>" class="ss-iconbtn ss-header__call" aria-label="Позвонить <?php echo esc_attr($company['phone']); ?>"><?php echo $phone_icon; ?></a>
            <button type="button" class="ss-iconbtn ss-header__burger" aria-label="Открыть меню" aria-expanded="false" aria-controls="ss-menu" data-ss-menu-open>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
            </button>
        </div>
    </div>
</header>

<div class="ss-menu" id="ss-menu" hidden>
    <div class="ss-menu__backdrop" data-ss-menu-close></div>
    <div class="ss-menu__panel" role="dialog" aria-modal="true" aria-label="Меню">
        <div class="ss-menu__head">
            <span class="ss-menu__title">Меню</span>
            <button type="button" class="ss-iconbtn" aria-label="Закрыть меню" data-ss-menu-close>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <nav aria-label="Меню для телефона">
            <ul class="ss-menu__list">
                <?php foreach ($nav as $item) : ?>
                    <li><a href="<?php echo esc_url($item[0]); ?>"><?php echo esc_html($item[1]); ?></a></li>
                <?php endforeach; ?>
                <li><a href="/podbor-septika/">Подбор септика</a></li>
                <li><a href="<?php echo esc_url(izex_prices_page_url()); ?>">Цены</a></li>
                <li><a href="/reviews/">Отзывы</a></li>
            </ul>
        </nav>
        <div class="ss-menu__contacts">
            <a class="ss-menu__phone" href="tel:<?php echo esc_attr($company['phone_clean']); ?>"><?php echo esc_html($company['phone']); ?></a>
            <span class="ss-muted"><?php echo esc_html($company['work_time']); ?></span>
            <a class="ss-link" href="https://max.ru/u/f9LHodD0cOI_AGyWf9AKcrl72RIFsKRL7vOApMiqwT37En8F81IprazW1ro">Написать в MAX</a>
        </div>
        <button type="button" class="ss-btn ss-btn--primary ss-btn--block open-modal" data-modal="order">Получить расчёт</button>
    </div>
</div>

<?php if (!is_front_page() && function_exists('rank_math_the_breadcrumbs')) : ?>
    <div class="ss-crumbs">
        <div class="ss-container">
            <?php rank_math_the_breadcrumbs(array(
                'wrap_before' => '<nav class="yoast-breadcrumbs" aria-label="Хлебные крошки">',
                'wrap_after'  => '</nav>',
                'separator'   => '/',
            )); ?>
        </div>
    </div>
<?php endif; ?>
<div id="ss-main"></div>
