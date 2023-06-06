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
<?php wp_body_open(); ?>

    <!-- ===== INLINE STYLES FOR HEADER ===== -->
    <style>
        /* Header Variables - Светлая тема */
        :root {
            --header-bg: rgba(255, 255, 255, 0.95);
            --header-bg-scrolled: rgba(255, 255, 255, 0.98);
            --header-text: #1e293b;
            --header-text-muted: #64748b;
            --header-border: rgba(0, 0, 0, 0.08);
            --header-gold: #d4af37;
            --header-gold-hover: #f4d03f;
            --header-blue: #2563eb;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== FIXED HEADER BASE ===== */
        .site-header-premium {
            position: fixed; /* ✅ Всегда видим при скролле */
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: var(--header-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--header-border);
            transition: var(--transition);
        }

        .site-header-premium.loaded {
            animation: headerSlideDown 0.5s ease;
        }

        @keyframes headerSlideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Скролл-эффекты */
        .site-header-premium.scrolled {
            background: var(--header-bg-scrolled);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-bottom-color: rgba(212, 175, 55, 0.3);
        }

        .site-header-premium.scrolled .header-top-premium {
            opacity: 0;
            height: 0;
            padding: 0;
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        /* Container */
        .container {
            width: 100%; max-width: 1280px; margin: 0 auto;
            padding: 0 clamp(16px, 4vw, 32px);
        }

        /* ===== TOP BAR (светлый) ===== */
        .header-top-premium {
            background: rgba(248, 250, 252, 0.9);
            border-bottom: 1px solid var(--header-border);
            padding: 6px 0;
            font-size: 13px;
            transition: var(--transition);
        }

        .header-top-inner {
            display: flex; align-items: center; justify-content: space-between;
            gap: 20px; flex-wrap: wrap;
        }

        .header-top-left, .header-top-right {
            display: flex; align-items: center; gap: 18px; flex-wrap: wrap;
        }

        .top-info-item {
            display: flex; align-items: center; gap: 6px;
            color: var(--header-text-muted);
        }

        .top-icon { color: var(--header-gold); flex-shrink: 0; }
        .top-info-divider { width: 1px; height: 12px; background: var(--header-border); }

        .top-contact-link {
            display: flex; align-items: center; gap: 6px;
            color: var(--header-text-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .top-contact-link:hover { color: var(--header-blue); }

        .top-phones-wrapper {
            position: relative; display: flex; align-items: center; gap: 10px;
        }

        .top-phone-primary {
            color: var(--header-text); font-weight: 600; font-size: 15px;
            text-decoration: none; transition: color 0.2s ease;
        }
        .top-phone-primary:hover { color: var(--header-gold); }

        .top-phone-toggle {
            background: none; border: none; color: var(--header-text-muted);
            cursor: pointer; padding: 4px; display: flex; align-items: center;
            transition: transform 0.2s ease, color 0.2s ease;
        }
        .top-phone-toggle:hover, .top-phone-toggle.active { color: var(--header-gold); }
        .top-phone-toggle.active { transform: rotate(180deg); }

        .top-phones-dropdown {
            position: absolute; top: calc(100% + 6px); right: 0;
            background: #fff; border: 1px solid var(--header-border);
            border-radius: 10px; padding: 8px 0; min-width: 200px;
            display: none; flex-direction: column;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            z-index: 100;
        }
        .top-phones-dropdown.active { display: flex; animation: dropdownFade 0.2s ease; }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .top-phones-dropdown a {
            padding: 8px 16px; color: var(--header-text);
            text-decoration: none; font-size: 14px;
            transition: all 0.2s ease;
        }
        .top-phones-dropdown a:hover {
            background: rgba(212, 175, 55, 0.1);
            color: var(--header-gold);
            padding-left: 20px;
        }

        /* ===== BUTTONS (светлые) ===== */
        .btn-premium {
            display: inline-flex; align-items: center; justify-content: center;
            gap: 8px; padding: 10px 20px; border-radius: 8px;
            font-weight: 500; font-size: 14px; text-decoration: none;
            transition: var(--transition); border: none; cursor: pointer;
            position: relative; overflow: hidden;
        }

        .btn-premium::before {
            content: ''; position: absolute; top: 50%; left: 50%;
            width: 0; height: 0; border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.5s, height 0.5s;
        }
        .btn-premium:hover::before { width: 250px; height: 250px; }

        .btn-premium.btn-sm { padding: 8px 16px; font-size: 13px; }
        .btn-premium.btn-full { width: 100%; }

        .btn-premium.btn-ghost {
            background: transparent; color: var(--header-text);
            border: 1px solid var(--header-border);
        }
        .btn-premium.btn-ghost:hover {
            background: rgba(212, 175, 55, 0.1);
            border-color: var(--header-gold);
            color: var(--header-gold);
        }

        .btn-premium.btn-gold {
            background: linear-gradient(135deg, var(--header-gold) 0%, var(--header-gold-hover) 100%);
            color: #0f172a; font-weight: 600;
        }
        .btn-premium.btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.35);
        }

        .btn-premium.btn-outline-gold {
            background: transparent; color: var(--header-gold);
            border: 1px solid var(--header-gold);
        }
        .btn-premium.btn-outline-gold:hover {
            background: var(--header-gold); color: #0f172a;
        }

        .btn-arrow { transition: transform 0.2s ease; }
        .btn-premium:hover .btn-arrow { transform: translateX(3px); }

        /* ===== MAIN HEADER ===== */
        .header-main-premium {
            padding: 14px 0;
            transition: padding 0.3s ease;
        }

        .site-header-premium.scrolled .header-main-premium {
            padding: 10px 0;
        }

        .header-main-inner {
            display: flex; align-items: center; justify-content: space-between;
            gap: 24px;
        }

        /* ===== LOGO ===== */
        .header-logo-premium {
            display: flex; flex-direction: column; align-items: flex-start;
            gap: 2px; text-decoration: none;
        }

        .header-logo-premium .custom-logo {
            height: auto; max-width: 180px; transition: transform 0.2s ease;
        }
        .header-logo-premium:hover .custom-logo { transform: scale(1.02); }

        .logo-premium-default { display: block; }
        .logo-premium-mobile { display: none; }

        .logo-tagline {
            font-size: 10px; color: var(--header-text-muted);
            text-transform: uppercase; letter-spacing: 1.5px;
            font-weight: 500; margin-left: 2px;
        }

        /* ===== NAVIGATION ===== */
        .header-nav-premium { flex: 1; display: flex; justify-content: center; }

        .nav-list-premium {
            display: flex; align-items: center; justify-content: center;
            gap: 4px; list-style: none; padding: 0; margin: 0;
        }

        .nav-list-premium > li { position: relative; }

        .nav-list-premium > li > a {
            display: block; padding: 10px 14px;
            color: var(--header-text-muted);
            text-decoration: none; font-weight: 500; font-size: 14px;
            transition: all 0.2s ease; border-radius: 6px;
            position: relative;
        }

        .nav-list-premium > li > a::after {
            content: ''; position: absolute; bottom: 6px; left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 0; height: 2px; background: var(--header-gold);
            border-radius: 2px; transition: all 0.2s ease;
        }

        .nav-list-premium > li > a:hover,
        .nav-list-premium > li > a.current-menu-item {
            color: var(--header-text);
        }
        .nav-list-premium > li > a:hover::after,
        .nav-list-premium > li > a.current-menu-item::after {
            transform: translateX(-50%) scaleX(1); width: 32px;
        }

        /* Submenu */
        .nav-list-premium .sub-menu {
            position: absolute; top: 100%; left: 50%;
            transform: translateX(-50%) translateY(8px);
            background: #fff; border: 1px solid var(--header-border);
            border-radius: 12px; padding: 8px 0; min-width: 220px;
            list-style: none; opacity: 0; visibility: hidden;
            transition: all 0.2s ease; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        .nav-list-premium li:hover > .sub-menu {
            opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0);
        }

        .nav-list-premium .sub-menu li a {
            display: block; padding: 10px 20px;
            color: var(--header-text); text-decoration: none;
            font-size: 14px; transition: all 0.2s ease;
        }
        .nav-list-premium .sub-menu li a:hover {
            background: rgba(212, 175, 55, 0.1);
            color: var(--header-gold); padding-left: 24px;
        }

        /* ===== HEADER ACTIONS ===== */
        .header-actions-premium {
            display: flex; align-items: center; gap: 6px;
        }

        .header-action-btn-premium {
            background: none; border: none; color: var(--header-text-muted);
            cursor: pointer; padding: 8px 10px; display: flex;
            align-items: center; gap: 5px; border-radius: 6px;
            transition: all 0.2s ease; font-size: 13px;
        }

        .header-action-btn-premium:hover {
            color: var(--header-text); background: rgba(0, 0, 0, 0.04);
        }

        .header-action-btn-premium .action-icon {
            transition: transform 0.2s ease;
        }
        .header-action-btn-premium:hover .action-icon {
            transform: scale(1.1);
        }

        .header-action-btn-premium.phone-tablet { display: none; }

        /* ===== HAMBURGER ===== */
        .hamburger-premium {
            display: flex; flex-direction: column; gap: 4px;
            width: 22px; height: 16px;
        }
        .hamburger-line {
            width: 100%; height: 2px; background: var(--header-text);
            border-radius: 2px; transition: all 0.25s ease;
        }
        .mobile-toggle.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(4px, 4px);
        }
        .mobile-toggle.active .hamburger-line:nth-child(2) { opacity: 0; }
        .mobile-toggle.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(4px, -4px);
        }

        /* ===== SEARCH BAR ===== */
        .header-search-premium {
            position: absolute; top: 100%; left: 0; right: 0;
            background: #fff; border-bottom: 1px solid var(--header-border);
            padding: 16px 0; transform: translateY(-8px);
            opacity: 0; visibility: hidden; transition: all 0.25s ease;
            z-index: 999;
        }
        .header-search-premium.active {
            transform: translateY(0); opacity: 1; visibility: visible;
        }

        .search-form-premium {
            display: flex; align-items: center; justify-content: center;
            gap: 12px; max-width: 500px; margin: 0 auto;
        }

        .search-input-wrapper { flex: 1; position: relative; }

        .search-input-wrapper input {
            width: 100%; padding: 12px 18px; padding-right: 50px;
            background: #f8fafc; border: 1px solid var(--header-border);
            border-radius: 8px; color: var(--header-text); font-size: 15px;
            transition: all 0.2s ease;
        }
        .search-input-wrapper input::placeholder { color: var(--header-text-muted); }
        .search-input-wrapper input:focus {
            outline: none; background: #fff;
            border-color: var(--header-gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
        }

        .search-input-wrapper button[type="submit"] {
            position: absolute; right: 6px; top: 50%;
            transform: translateY(-50%); background: var(--header-gold);
            border: none; width: 36px; height: 36px; border-radius: 6px;
            color: #0f172a; cursor: pointer; display: flex;
            align-items: center; justify-content: center;
            transition: all 0.2s ease;
        }
        .search-input-wrapper button[type="submit"]:hover {
            background: var(--header-gold-hover);
            transform: translateY(-50%) scale(1.05);
        }

        .search-close-premium {
            background: none; border: none; color: var(--header-text-muted);
            cursor: pointer; display: flex; align-items: center; gap: 6px;
            font-size: 12px; padding: 8px 12px; border-radius: 6px;
            transition: all 0.2s ease;
        }
        .search-close-premium:hover {
            color: var(--header-text); background: rgba(0, 0, 0, 0.04);
        }

        /* ===== MOBILE MENU ===== */
        .mobile-menu-premium {
            position: fixed; inset: 0; z-index: 2000; display: none;
        }
        .mobile-menu-premium.active { display: block; }

        .mobile-menu-backdrop {
            position: absolute; inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
        }

        .mobile-menu-panel {
            position: absolute; top: 0; right: 0;
            width: 100%; max-width: 360px; height: 100%;
            background: #fff; padding: 20px;
            display: flex; flex-direction: column; gap: 24px;
            transform: translateX(100%); transition: transform 0.35s ease;
            border-left: 1px solid var(--header-border);
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
        }
        .mobile-menu-premium.active .mobile-menu-panel {
            transform: translateX(0);
        }

        .mobile-menu-header {
            display: flex; align-items: center; justify-content: space-between;
            padding-bottom: 16px; border-bottom: 1px solid var(--header-border);
        }

        .mobile-menu-logo {
            font-weight: 700; font-size: 18px; color: var(--header-text);
            text-decoration: none;
        }

        .mobile-menu-close {
            background: none; border: none; color: var(--header-text-muted);
            cursor: pointer; padding: 6px; display: flex;
            align-items: center; justify-content: center;
            border-radius: 50%; transition: all 0.2s ease;
        }
        .mobile-menu-close:hover {
            color: #fff; background: var(--header-text);
        }

        .mobile-menu-nav-premium { flex: 1; overflow-y: auto; }

        .mobile-nav-list-premium {
            list-style: none; padding: 0; margin: 0;
            display: flex; flex-direction: column; gap: 2px;
        }

        .mobile-nav-list-premium li a {
            display: block; padding: 12px 16px;
            color: var(--header-text); text-decoration: none;
            font-weight: 500; font-size: 16px; border-radius: 8px;
            transition: all 0.2s ease;
        }
        .mobile-nav-list-premium li a:hover,
        .mobile-nav-list-premium li a.current-menu-item {
            background: rgba(212, 175, 55, 0.12);
            color: var(--header-gold); padding-left: 20px;
        }

        .mobile-nav-list-premium .sub-menu {
            margin-left: 16px; margin-top: 4px;
        }
        .mobile-nav-list-premium .sub-menu a {
            font-size: 14px; font-weight: 400; padding: 10px 16px;
        }

        .mobile-menu-contacts-premium {
            padding-top: 16px; border-top: 1px solid var(--header-border);
        }

        .mobile-section-title {
            font-size: 12px; color: var(--header-gold);
            text-transform: uppercase; letter-spacing: 1.5px;
            margin-bottom: 12px; font-weight: 600;
        }

        .mobile-contact-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 0; color: var(--header-text);
        }
        .mobile-contact-item a {
            color: inherit; text-decoration: none;
            font-weight: 500; transition: color 0.2s ease;
        }
        .mobile-contact-item a:hover { color: var(--header-gold); }

        .mobile-menu-cta-premium {
            display: flex; flex-direction: column; gap: 10px;
            padding-top: 12px; border-top: 1px solid var(--header-border);
        }

        .mobile-menu-social {
            padding-top: 12px; border-top: 1px solid var(--header-border);
        }
        .mobile-menu-social .social-label {
            display: block; font-size: 11px; color: var(--header-text-muted);
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;
        }

        .social-links-premium { display: flex; gap: 10px; }

        .social-link-premium {
            width: 38px; height: 38px; border-radius: 50%;
            background: #f1f5f9; display: flex;
            align-items: center; justify-content: center;
            color: var(--header-text); transition: all 0.2s ease;
        }
        .social-link-premium:hover {
            background: var(--header-gold); color: #0f172a;
            transform: translateY(-2px);
        }

        /* ===== MODALS ===== */
        .modal-premium {
            position: fixed; inset: 0; z-index: 3000; display: none;
            align-items: center; justify-content: center; padding: 20px;
        }
        .modal-premium.active { display: flex; animation: modalFadeIn 0.25s ease; }

        @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }

        .modal-backdrop {
            position: absolute; inset: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(6px);
        }

        .modal-panel {
            position: relative; background: #fff;
            border-radius: 16px; padding: 32px 28px;
            max-width: 460px; width: 100%; z-index: 1;
            animation: modalSlideUp 0.35s ease;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        }

        @keyframes modalSlideUp {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-close-btn {
            position: absolute; top: 14px; right: 14px;
            width: 32px; height: 32px; background: #f1f5f9;
            border: none; border-radius: 50%; color: var(--header-text-muted);
            cursor: pointer; display: flex; align-items: center;
            justify-content: center; transition: all 0.2s ease;
        }
        .modal-close-btn:hover {
            background: var(--header-gold); color: #0f172a;
        }

        .modal-header { text-align: center; margin-bottom: 24px; }
        .modal-title {
            font-size: 22px; font-weight: 600; color: var(--header-text);
            margin-bottom: 6px;
        }
        .modal-subtitle {
            font-size: 14px; color: var(--header-text-muted);
        }

        .modal-form-premium { display: flex; flex-direction: column; gap: 12px; }

        .form-group-premium input {
            width: 100%; padding: 14px 16px;
            background: #f8fafc; border: 1px solid var(--header-border);
            border-radius: 8px; color: var(--header-text); font-size: 15px;
            transition: all 0.2s ease;
        }
        .form-group-premium input::placeholder { color: var(--header-text-muted); }
        .form-group-premium input:focus {
            outline: none; background: #fff;
            border-color: var(--header-gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
        }

        .form-privacy {
            font-size: 11px; color: var(--header-text-muted);
            text-align: center; margin-top: 4px;
        }
        .form-privacy a { color: var(--header-gold); text-decoration: none; }
        .form-privacy a:hover { text-decoration: underline; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .desktop-nav { display: none; }
            .header-action-btn-premium.phone-tablet { display: flex; }
            .header-logo-premium .logo-premium-default { display: none; }
            .header-logo-premium .logo-premium-mobile { display: block; }
            .header-top-left { display: none; }
        }

        @media (max-width: 768px) {
            .header-top-premium .header-top-right {
                width: 100%; justify-content: center;
            }
            .header-top-premium .top-contact-link,
            .header-top-premium .btn-premium.btn-ghost {
                display: none;
            }
            .header-main-inner { gap: 12px; }
            .header-actions-premium .btn-premium.btn-sm { display: none; }
            .mobile-menu-panel { max-width: 100%; }
        }

        @media (max-width: 480px) {
            .header-main-premium { padding: 12px 0; }
            .logo-tagline { display: none; }
            .search-form-premium { flex-direction: column; gap: 10px; }
            .search-close-premium { width: 100%; justify-content: center; }
        }

        /* ===== SPACE FOR FIXED HEADER ===== */
        body { padding-top: 120px; }
        @media (max-width: 1024px) { body { padding-top: 100px; } }
        @media (max-width: 768px) { body { padding-top: 80px; } }
    </style>

    <header class="site-header-premium" id="site-header-premium">

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
                            <span style="font-weight:700;font-size:20px;color:#1e293b">ТОПАС</span>
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

    <!-- Modals Premium -->
    <div class="modal-premium" id="modal-callback">
        <div class="modal-backdrop"></div>
        <div class="modal-panel">
            <button class="modal-close-btn" aria-label="Закрыть"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
            <div class="modal-header">
                <h3 class="modal-title">Заказать звонок</h3>
                <p class="modal-subtitle">Перезвоним в течение 15 минут</p>
            </div>
            <form class="modal-form-premium" id="callbackForm">
                <div class="form-group-premium"><input type="text" name="name" placeholder="Ваше имя *" required></div>
                <div class="form-group-premium"><input type="tel" name="phone" placeholder="+7 (___) ___-__-__ *" required></div>
                <button type="submit" class="btn-premium btn-full btn-gold"><span>Жду звонка</span><svg class="btn-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button>
                <p class="form-privacy">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
            </form>
        </div>
    </div>

    <div class="modal-premium" id="modal-engineer">
        <div class="modal-backdrop"></div>
        <div class="modal-panel">
            <button class="modal-close-btn" aria-label="Закрыть"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
            <div class="modal-header">
                <h3 class="modal-title">Вызвать инженера</h3>
                <p class="modal-subtitle">Бесплатный выезд и консультация</p>
            </div>
            <form class="modal-form-premium" id="engineerForm">
                <div class="form-group-premium"><input type="text" name="name" placeholder="Ваше имя *" required></div>
                <div class="form-group-premium"><input type="tel" name="phone" placeholder="+7 (___) ___-__-__ *" required></div>
                <div class="form-group-premium"><input type="text" name="address" placeholder="Адрес участка"></div>
                <button type="submit" class="btn-premium btn-full btn-gold"><span>Вызвать инженера</span><svg class="btn-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button>
                <p class="form-privacy">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
            </form>
        </div>
    </div>

    <!-- Header JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('site-header-premium');
            const headerTop = document.querySelector('.header-top-premium');
            let lastScrollY = window.scrollY;

            // Sticky header logic
            function handleHeaderScroll() {
                const currentScrollY = window.scrollY;
                if (currentScrollY > 30) {
                    header.classList.add('scrolled');
                    headerTop?.classList.add('hidden');
                } else {
                    header.classList.remove('scrolled');
                    headerTop?.classList.remove('hidden');
                }
                lastScrollY = currentScrollY;
            }
            window.addEventListener('scroll', handleHeaderScroll, { passive: true });

            // Phone dropdown
            const phoneToggle = document.querySelector('.top-phone-toggle');
            const phonesDropdown = document.querySelector('.top-phones-dropdown');
            if (phoneToggle && phonesDropdown) {
                phoneToggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    phonesDropdown.classList.toggle('active');
                    phoneToggle.classList.toggle('active');
                });
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.top-phones-wrapper')) {
                        phonesDropdown.classList.remove('active');
                        phoneToggle.classList.remove('active');
                    }
                });
            }

            // Search toggle
            const searchToggle = document.querySelector('.search-toggle');
            const searchClose = document.querySelector('.search-close-premium');
            const headerSearch = document.getElementById('header-search');
            const searchInput = headerSearch?.querySelector('input');

            function toggleSearch(show) {
                if (show) {
                    headerSearch?.classList.add('active');
                    searchInput?.focus();
                } else {
                    headerSearch?.classList.remove('active');
                }
            }
            searchToggle?.addEventListener('click', () => toggleSearch(true));
            searchClose?.addEventListener('click', () => toggleSearch(false));

            // Mobile menu
            const mobileToggle = document.querySelector('.mobile-toggle');
            const mobileMenu = document.getElementById('mobile-menu-premium');
            const mobileClose = document.querySelector('.mobile-menu-close');
            const mobileBackdrop = document.querySelector('.mobile-menu-backdrop');

            function openMobileMenu() { mobileMenu?.classList.add('active'); document.body.style.overflow = 'hidden'; }
            function closeMobileMenu() { mobileMenu?.classList.remove('active'); document.body.style.overflow = ''; }

            mobileToggle?.addEventListener('click', openMobileMenu);
            mobileClose?.addEventListener('click', closeMobileMenu);
            mobileBackdrop?.addEventListener('click', closeMobileMenu);

            // Modals
            const modals = document.querySelectorAll('.modal-premium');
            const openModalBtns = document.querySelectorAll('.open-modal');
            const closeModalBtns = document.querySelectorAll('.modal-close-btn, .modal-backdrop');

            function openModal(id) {
                const modal = document.getElementById(`modal-${id}`);
                if (modal) { modal.classList.add('active'); document.body.style.overflow = 'hidden'; closeMobileMenu(); }
            }
            function closeModal(modal) { modal?.classList.remove('active'); document.body.style.overflow = ''; }

            openModalBtns.forEach(btn => {
                btn.addEventListener('click', function() { openModal(this.dataset.modal); });
            });
            closeModalBtns.forEach(btn => {
                btn.addEventListener('click', e => closeModal(e.target.closest('.modal-premium')));
            });

            // Forms
            document.querySelectorAll('.modal-form-premium').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = this.querySelector('button[type="submit"]');
                    const original = btn.innerHTML;
                    btn.disabled = true; btn.innerHTML = '<span>Отправка...</span>';
                    setTimeout(() => {
                        alert('Спасибо! Мы перезвоним вам в ближайшее время.');
                        this.reset();
                        closeModal(this.closest('.modal-premium'));
                        btn.disabled = false; btn.innerHTML = original;
                    }, 500);
                });
            });

            // Phone mask
            document.querySelectorAll('input[type="tel"]').forEach(input => {
                input.addEventListener('input', function(e) {
                    let v = e.target.value.replace(/\D/g,'');
                    if (!v) { e.target.value = ''; return; }
                    if (v[0]==='7'||v[0]==='8') v = v.slice(1);
                    let f = '+7';
                    if (v.length>0) f += ' (' + v.slice(0,3);
                    if (v.length>=3) f += ') ' + v.slice(3,6);
                    if (v.length>=6) f += '-' + v.slice(6,8);
                    if (v.length>=8) f += '-' + v.slice(8,10);
                    e.target.value = f;
                });
            });

            // Smooth scroll
            document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(a => {
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const headerH = header?.offsetHeight || 0;
                        window.scrollTo({ top: target.getBoundingClientRect().top + window.pageYOffset - headerH - 20, behavior: 'smooth' });
                        closeMobileMenu();
                    }
                });
            });

            // Init
            window.addEventListener('load', () => header?.classList.add('loaded'));
        });
    </script>

<?php // Header end ?>