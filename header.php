<?php
/**
 * The header for our theme - Premium Light Edition
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

// Контакты компании (из Carbon Fields, с запасными значениями)
$company = getCompanyContacts();
?>
    <!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <link rel="profile" href="https://gmpg.org/xfn/11">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Favicon -->
        <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-icon" sizes="any">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/favicon-16x16.png">
        <link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/favicon-96x96.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon.png">
        <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/site.webmanifest">
        <meta name="theme-color" content="#ffffff">

        <?php wp_head(); ?>
    </head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?><header class="site-header-premium" id="site-header-premium">

        <div class="header-top-premium">
            <div class="container">
                <div class="header-top-inner">

                    <div class="header-top-left">
                        <div class="top-info-item">
                            <svg class="top-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                            </svg>
                            <span><?php echo esc_html($company['work_time']); ?></span>
                        </div>
                    </div>

                    <!-- Right: Contacts & CTA -->
                    <div class="header-top-right">
                        <a href="mailto:<?php echo antispambot($company['email']); ?>" class="top-contact-link">
                            <svg class="top-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22,6 -10,7L2,6"/>
                            </svg>
                            <?php echo antispambot($company['email']); ?>
                        </a>

                        <div class="top-phones-wrapper">
                            <a href="tel:<?php echo $company['phone_clean']; ?>" class="top-phone-primary"><?php echo esc_html($company['phone']); ?></a>

                        </div>

                        <button class="btn-premium btn-sm btn-ghost open-modal" data-modal="callback">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            <span>Заказать звонок</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header Premium -->
        <div class="header-main-premium">
            <div class="container">
                <div class="header-main-inner">

                    <!-- Logo -->
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo-premium">
                        <?php if (true) : ?>
                            <img src="<?=assets('/images/logo-transparent.png')?>" alt="" width="108">
                        <?php else : ?>
                            <span class="header-logo-text">ТОПАС</span>
                        <?php endif; ?>
                    </a>

                    <!-- Desktop Navigation -->
                    <nav class="header-nav-premium desktop-nav">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_class' => 'nav-list-premium',
                            'container' => false,
                            'fallback_cb' => function() {
                                echo '<ul class="nav-list-premium">';
                                echo '<li><a href="/">Главная</a></li>';
                                echo '<li><a href="/stations/">Каталог</a></li>';
                                echo '<li><a href="' . esc_url(izex_prices_page_url()) . '">Цены</a></li>';
                                echo '<li><a href="/services/">Услуги</a></li>';
                                echo '<li><a href="' . esc_url(get_post_type_archive_link('works')) . '">Наши работы</a></li>';
                                echo '<li><a href="/about/">О компании</a></li>';
                                echo '<li><a href="/reviews/">Отзывы</a></li>';
                                echo '</ul>';
                            },
                            'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                            'depth' => 2
                        ));
                        ?>
                    </nav>

                    <!-- Header Actions Premium -->
                    <div class="header-actions-premium">

                        <!-- Search Toggle -->

                        <!-- Phone (tablet) -->
                        <a href="tel:<?php echo $company['phone_clean']; ?>" class="header-action-btn-premium phone-tablet">
                            <svg class="action-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </a>

                        <!-- CTA Button -->
                        <button class="btn-premium btn-sm btn-gold open-modal" data-modal="engineer">
                            <span>Вызвать инженера</span>
                            <svg class="btn-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>

                        <!-- Mobile Menu Toggle -->
                        <button class="header-action-btn-premium mobile-toggle" aria-label="Меню">
                        <span class="hamburger-premium">
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                        </span>
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar Premium -->
        <div class="header-search-premium" id="header-search">
            <div class="container">
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-form-premium">
                    <div class="search-input-wrapper">
                        <input type="search" name="s" placeholder="Поиск по сайту..." value="<?php echo get_search_query(); ?>" aria-label="Поиск">
                        <button type="submit" aria-label="Найти">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        </button>
                    </div>
                    <button type="button" class="search-close-premium" aria-label="Закрыть поиск">
                        <span>Esc</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </form>
            </div>
        </div>

    </header>

    <!-- Mobile Menu Premium -->
    <div class="mobile-menu-premium" id="mobile-menu-premium">
        <div class="mobile-menu-backdrop"></div>
        <div class="mobile-menu-panel">

            <div class="mobile-menu-header">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="mobile-menu-logo">ТОПАС</a>
                <button class="mobile-menu-close" aria-label="Закрыть меню">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="mobile-menu-nav-premium">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'nav-list-premium',
                    'container' => false,
                    'fallback_cb' => function() {
                        echo '<ul class="nav-list-premium">';
                        echo '<li><a href="/">Главная</a></li>';
                        echo '<li><a href="/stations/">Каталог</a></li>';
                        echo '<li><a href="' . esc_url(izex_prices_page_url()) . '">Цены</a></li>';
                        echo '<li><a href="/services/">Услуги</a></li>';
                        echo '<li><a href="' . esc_url(get_post_type_archive_link('works')) . '">Наши работы</a></li>';
                        echo '<li><a href="/about/">О компании</a></li>';
                        echo '<li><a href="/reviews/">Отзывы</a></li>';
                        echo '</ul>';
                    },
                    'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                    'depth' => 2
                ));
                ?>
            </nav>

            <div class="mobile-menu-contacts-premium">
                <h4 class="mobile-section-title">Контакты</h4>
                <div class="mobile-contact-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <a href="tel:<?php echo $company['phone_clean']; ?>"><?php echo esc_html($company['phone']); ?></a>
                </div>
                <div class="mobile-contact-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22,6 -10,7L2,6"/></svg>
                    <a href="mailto:<?php echo antispambot($company['email']); ?>"><?php echo antispambot($company['email']); ?></a>
                </div>
                <div class="mobile-contact-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    <span><?php echo esc_html($company['work_time']); ?></span>
                </div>
            </div>

            <div class="mobile-menu-cta-premium">
                <button class="btn-premium btn-full btn-gold open-modal" data-modal="engineer">Вызвать инженера</button>
                <button class="btn-premium btn-full btn-outline-gold open-modal" data-modal="callback">Заказать звонок</button>
            </div>

            <div class="mobile-menu-social">
                <span class="social-label">Мы в соцсетях:</span>
                <div class="social-links-premium">
                    <a href="https://max.ru/u/f9LHodD0cOI_AGyWf9AKcrl72RIFsKRL7vOApMiqwT37En8F81IprazW1ro" class="social-link-premium" aria-label="MAX">
                        <img src="https://maxicons.ru/icons/MAX.svg" alt="Иконка MAX" width="32" height="32">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if (!is_front_page() && function_exists('yoast_breadcrumb')) : ?>
        <div class="breadcrumbs-wrapper">
            <div class="container">
                <?php yoast_breadcrumb('<nav class="yoast-breadcrumbs" aria-label="Хлебные крошки">', '</nav>'); ?>
            </div>
        </div>
    <?php endif; ?>

<?php // Header end ?>
