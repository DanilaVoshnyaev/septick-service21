<?php
/**
 * The header for our theme - Premium Light Edition
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

// Контакты компании
$company = array(
    'phone' => '8908 303 32 82',
    'phone_clean' => '+79083033282',
    'phone_alt' => '8937 37 37 700',
    'email' => 'servis.septik.pro@yandex.ru',
    'address' => '',
    'work_time' => 'Пн-Вс: 9:00 - 20:00',
);
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
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/favicon-16x16.png">
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
                        <div class="top-info-divider"></div>
                        <div class="top-info-item">
                            <svg class="top-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span><?php echo esc_html($company['address']); ?></span>
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
                            <button class="top-phone-toggle" aria-label="Показать дополнительные номера">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="top-phones-dropdown">
                                <a href="tel:<?php echo $company['phone_clean']; ?>"><?php echo esc_html($company['phone_alt']); ?></a>
                            </div>
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
                        <?php if (has_custom_logo()) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <span class="header-logo-text">ТОПАС</span>
                        <?php endif; ?>
                        <span class="logo-tagline">автономные канализации</span>
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
                                echo '<li><a href="/services/">Услуги</a></li>';
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
                    'menu_class' => 'mobile-nav-list-premium',
                    'container' => false,
                    'fallback_cb' => false,
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
                    <a href="#" class="social-link-premium" aria-label="Telegram"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 17.97L4.58 13.62 19.54 3l-5.13 14.972z"/></svg></a>
                    <a href="#" class="social-link-premium" aria-label="WhatsApp"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></a>
                    <a href="#" class="social-link-premium" aria-label="VKontakte"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M15.684 0H8.316C1.592 0 0 1.592 0 8.316v7.368C0 22.408 1.592 24 8.316 24h7.368C22.408 24 24 22.408 24 15.684V8.316C24 1.592 22.408 0 15.684 0zm3.692 16.168h-1.403c-.534 0-.698-.425-1.654-1.397-1.013-.972-1.454-1.104-1.703-1.104-.346 0-.441.099-.441.582v1.537c0 .415-.132.657-1.219.657-1.812 0-3.818-1.096-5.223-2.957C5.61 10.685 5 8.64 5 8.105c0-.314.115-.598.681-.598h1.403c.363 0 .494.165.632.598.69 2.012 1.845 3.78 2.314 3.78.181 0 .263-.082.263-.582v-2.25c-.05-1.03-.607-1.113-.607-1.476 0-.181.148-.363.363-.363h2.25c.314 0 .429.165.429.548v2.924c0 .314.148.429.247.429.198 0 .363-.214.726-.582 1.137-1.268 1.945-3.214 1.945-3.214.099-.314.28-.598.648-.598h1.403c.429 0 .528.214.429.598-.181.842-1.945 3.33-1.945 3.33s-.115.181-.115.363c0 .082.049.165.165.247.632.726 2.693 2.61 3.023 3.13.33.528.214.775-.198.775z"/></svg></a>
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
