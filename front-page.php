<?php
/**
 * Template Name: Front Page Premium
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

get_header();
$company = array(
    'phone' => '8908 303 32 82',
    'phone_clean' => '+79083033282',
    'phone_alt' => '89373737 700',
    'email' => 'servis.septik.pro@yandex.ru',
    'address' => '',
    'work_time' => 'Пн-Вс: 9:00 - 20:00',
);

?>

    <!-- ===== STYLES ===== -->
    <style>
        /* Premium Variables */
        :root {
            --premium-dark: #0f172a;
            --premium-darker: #020617;
            --premium-light: #f8fafc;
            --premium-gray: #64748b;
            --premium-gold: #21b224;
            --premium-gold-light: #21b224;
            --premium-blue: #2563eb;
            --premium-blue-dark: #1d4ed8;
            --premium-text: #1e293b;
            --premium-border: #e2e8f0;
            --transition: all 0.3s ease;
            --color-primary: #22c55e;           /* Основной зелёный */
            --color-primary-dark: #16a34a;      /* Тёмно-зелёный для ховера */
            --color-primary-light: #4ade80;     /* Светло-зелёный для акцентов */
            --color-primary-soft: #dcfce7;
            --card-green: #22c55e;
            --card-green-hover: #16a34a;
            --card-blue: #2563eb;
            --card-text: #1e293b;
            --card-text-muted: #64748b;
            --card-bg: #ffffff;
            --card-border: rgba(0,0,0,0.08);
            --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
            --card-radius: 12px;
            --card-transition: all 0.3s cubic-bezier(0.4,0,0.2,1);

            /* 🔤 Текст */
            --text-primary: #1e293b;            /* Тёмный текст */
            --text-secondary: #64748b;          /* Вторичный текст */
            --text-muted: #94a3b8;              /* Приглушённый */
            --text-inverse: #ffffff;            /* Белый текст на тёмном */

            /* 🖼️ Фоны — СВЕТЛЫЕ */
            --bg-primary: #ffffff;              /* Белый для карточек */
            --bg-secondary: #f8fafc;            /* Основной фон страницы */
            --bg-tertiary: #f1f5f9;             /* Альтернативный фон */
            --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);

            /* 📐 Границы и тени */
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.03);
            --shadow: 0 4px 6px -1px rgba(0,0,0,0.06);
            --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.08);
            --shadow-lg: 0 20px 25px -5px rgba(0,0,0,0.1);

            /* ⭕ Радиусы */
            --radius: 0.5rem;
            --radius-md: 0.75rem;
            --radius-lg: 1rem;
            --radius-xl: 1.5rem;

            /* ⏱️ Анимации */
            --transition: all 0.25s ease;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }


        /* Base Reset */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 16px; line-height: 1.6; color: var(--premium-text);
            background: var(--premium-dark); -webkit-font-smoothing: antialiased;
        }
        img, svg { max-width: 100%; height: auto; display: block; }
        a { color: inherit; text-decoration: none; transition: var(--transition); }
        button { font: inherit; background: none; border: none; cursor: pointer; }

        /* Container */
        .container {
            width: 100%; max-width: 1200px; margin: 0 auto;
            padding: 0 clamp(16px, 4vw, 32px);
        }
        @supports (padding: max(0px)) {
            .container { padding: 0 max(clamp(16px, 4vw, 32px), env(safe-area-inset-right)) max(clamp(16px, 4vw, 32px), env(safe-area-inset-left)); }
        }

        /* ===== HERO PREMIUM ===== */
        .hero-premium {
            position: relative; min-height: 100vh; display: flex; align-items: center;
            padding: 120px 0 80px; overflow: hidden;
        }
        .hero-bg-image {
            position: absolute; inset: 0; background-size: cover; background-position: center;
            background-repeat: no-repeat; z-index: 0;
        }
        .hero-bg-image::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(
                    135deg,
                    rgba(15, 23, 42, 0.55) 0%,
                    rgba(30, 41, 59, 0.65) 50%,
                    rgba(15, 23, 42, 0.5) 100%
            );
        }
        .hero-bg-overlay {
            position: absolute; inset: 0; z-index: 0; pointer-events: none;
            background: radial-gradient(circle at 20% 50%, rgba(37,99,235,0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(212,175,55,0.05) 0%, transparent 50%);
        }
        .hero-content { position: relative; z-index: 1; text-align: center; max-width: 900px; margin: 0 auto; }

        .hero-contacts-top {
            display: flex; align-items: center; justify-content: center; gap: 20px;
            margin-bottom: 24px; flex-wrap: wrap;
        }
        .hero-phone-link {
            display: flex; align-items: center; gap: 8px; color: #fff; font-weight: 600;
            font-size: 18px; padding: 8px 16px; border-radius: 8px;
            background: rgba(255,255,255,0.1); transition: var(--transition);
        }
        .hero-phone-link:hover { background: var(--premium-gold); color: var(--premium-darker); }

        .hero-title {
            font-size: clamp(36px, 5vw, 56px); font-weight: 300; line-height: 1.1;
            margin-bottom: 24px; color: #fff; letter-spacing: -1px;
        }
        .gradient-text {
            background: linear-gradient(135deg, var(--header-green) 0%, var(--premium-gold-light) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; font-weight: 400;
        }
        .hero-subtitle {
            font-size: clamp(16px, 2vw, 20px); line-height: 1.6; color: rgba(255,255,255,0.85);
            margin-bottom: 40px; max-width: 700px; margin-left: auto; margin-right: auto;
        }

        .hero-stats {
            display: flex; align-items: center; justify-content: center; gap: 40px;
            margin-bottom: 48px; padding: 32px 0;
            border-top: 1px solid rgba(255,255,255,0.15);
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }
        .stat-item { text-align: center; }
        .stat-number { font-size: clamp(36px, 4vw, 48px); font-weight: 300; color: var(--premium-gold); line-height: 1; }
        .stat-suffix { font-size: clamp(24px, 3vw, 32px); color: var(--premium-gold); vertical-align: super; }
        .stat-label { display: block; margin-top: 8px; font-size: 13px; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; }
        .stat-divider { width: 1px; height: 50px; background: rgba(255,255,255,0.15); }

        .hero-buttons { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }

        /* Buttons */
        .btn-premium {
            display: inline-flex; align-items: center; justify-content: center; gap: 10px;
            padding: 14px 28px; border-radius: 8px; font-weight: 500; font-size: 15px;
            transition: var(--transition); border: none; cursor: pointer; position: relative; overflow: hidden;
        }
        @media (max-width: 480px) {
            .btn-premium{
                width: 100%;
            }
        }
        .btn-premium::before {
            content: ''; position: absolute; top: 50%; left: 50%; width: 0; height: 0;
            border-radius: 50%; background: rgba(255,255,255,0.2);
            transform: translate(-50%, -50%); transition: width 0.6s, height 0.6s;
        }
        .btn-premium:hover::before { width: 300px; height: 300px; }

        .btn-premium.btn-primary {
            background:  var(--premium-gold);
            color: white;
            border: 1px solid var(--premium-gold);
        }
        .btn-premium.btn-primary:hover {
            transform: translateY(-2px);
            background-color: white;
            color: black;
        }

        .btn-premium.btn-outline {
            background: transparent; color: #fff; border: 2px solid rgba(255,255,255,0.4);
        }
        .btn-premium.btn-outline:hover { background: rgba(255,255,255,0.15); border-color: var(--premium-gold); color: var(--premium-gold); }

        .btn-premium.btn-sm { padding: 10px 20px; font-size: 14px; }
        .btn-premium.btn-large { padding: 16px 36px; font-size: 16px; }
        .btn-premium.btn-full { width: 100%; }

        .btn-icon, .btn-arrow { transition: transform 0.3s ease; }
        .btn-premium:hover .btn-arrow { transform: translateX(3px); }

        /* ===== LUXURY FEATURES ===== */
        .luxury-features { padding: clamp(60px, 10vw, 100px) 0; background: var(--premium-light); }
        .features-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
        }
        .feature-luxury {
            text-align: center; padding: 32px 24px; background: #fff; border-radius: 12px;
            border: 1px solid var(--premium-border); transition: var(--transition);
        }
        .feature-luxury:hover {
            transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: var(--premium-gold);
        }
        .feature-icon-wrapper {
            width: 72px; height: 72px; margin: 0 auto 20px;
            background: linear-gradient(135deg, rgba(37,99,235,0.1) 0%, rgba(212,175,55,0.1) 100%);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: var(--premium-blue); transition: var(--transition);
        }
        .feature-luxury:hover .feature-icon-wrapper {
            background: linear-gradient(135deg, var(--premium-blue) 0%, var(--premium-gold) 100%);
            color: #fff;
        }
        .feature-luxury h3 { font-size: 18px; font-weight: 600; margin-bottom: 10px; color: var(--premium-text); }
        .feature-luxury p { font-size: 14px; color: var(--premium-gray); line-height: 1.5; }

        /* ===== CATALOG PREMIUM ===== */
        .catalog-premium { padding: clamp(80px, 12vw, 120px) 0; background: #fff; }
        .section-header { text-align: center; margin-bottom: 48px; }
        .section-label {
            display: inline-block; font-size: 12px; font-weight: 600; color: var(--premium-gold);
            text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;
            padding: 6px 16px; background: rgba(212,175,55,0.1); border-radius: 20px;
        }
        .section-title {
            font-size: clamp(28px, 4vw, 40px); font-weight: 300; color: var(--premium-text);
            margin-bottom: 12px; letter-spacing: -0.5px;
        }
        .section-title.text-left { text-align: left; }
        .section-subtitle {
            font-size: 16px; color: var(--premium-gray); max-width: 600px;
            margin: 0 auto; line-height: 1.6;
        }
        .section-subtitle.text-left { margin: 0; text-align: left; }

        .catalog-tabs {
            display: flex; justify-content: center; gap: 10px; margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .tab-btn {
            padding: 10px 22px; background: transparent; border: 1px solid var(--premium-border);
            border-radius: 30px; cursor: pointer; transition: var(--transition);
            font-size: 14px; font-weight: 500; color: var(--premium-gray);
        }
        .tab-btn:hover, .tab-btn.active {
            background: var(--premium-dark); color: #fff; border-color: var(--premium-dark);
        }

        .catalog-grid-premium {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px; margin-bottom: 48px;
        }
        .product-card-premium {
            background: #fff; border-radius: 16px; overflow: hidden;
            border: 1px solid var(--premium-border); transition: var(--transition);
            position: relative; display: flex; flex-direction: column;
        }
        .product-card-premium:hover {
            transform: translateY(-6px); box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            border-color: transparent;
        }
        .product-card-premium.featured {
            border: 2px solid var(--premium-gold); transform: scale(1.02);
        }
        .product-card-premium.featured:hover { transform: scale(1.02) translateY(-6px); }

        .product-badge-premium {
            position: absolute; top: 16px; right: 16px; z-index: 2;
            padding: 5px 14px; background: var(--premium-blue); color: #fff;
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            border-radius: 16px; letter-spacing: 0.5px;
        }
        .product-badge-premium.best { background: var(--premium-gold); color: var(--premium-darker); }

        .product-image-wrapper {
            position: relative; height: 220px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            display: flex; align-items: center; justify-content: center; overflow: hidden;
        }
        .product-image { max-width: 85%; max-height: 85%; object-fit: contain; transition: transform 0.4s ease; }
        .product-card-premium:hover .product-image { transform: scale(1.05); }

        .product-overlay {
            position: absolute; inset: 0; background: rgba(15,23,42,0.92);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .product-card-premium:hover .product-overlay { opacity: 1; }

        .btn-quick-view {
            padding: 10px 24px; background: #fff; color: var(--premium-dark);
            border: none; border-radius: 8px; font-weight: 500; font-size: 14px;
            cursor: pointer; transition: var(--transition);
        }
        .btn-quick-view:hover { background: var(--premium-gold); color: var(--premium-darker); }

        .product-content { padding: 24px; flex: 1; display: flex; flex-direction: column; }
        .product-title { font-size: 20px; font-weight: 600; margin-bottom: 16px; color: var(--premium-text); }

        .product-specs-premium { margin-bottom: 20px; flex: 1; }
        .spec-item {
            display: flex; align-items: center; gap: 10px; padding: 8px 0;
            border-bottom: 1px solid var(--premium-border); font-size: 14px; color: var(--premium-gray);
        }
        .spec-item:last-child { border-bottom: none; }
        .spec-icon { font-size: 16px; }

        .product-footer { display: flex; flex-direction: column; gap: 12px; margin-top: auto; }
        .product-price-premium { text-align: center; }
        .price-old { display: block; font-size: 14px; color: var(--premium-gray); text-decoration: line-through; margin-bottom: 2px; }
        .price-current { font-size: 24px; font-weight: 700; color: var(--premium-gold); }

        .catalog-cta-premium {
            text-align: center; padding: 24px; background: var(--premium-light);
            border-radius: 12px; font-size: 15px; color: white;position: relative;
        }
        .catalog-cta-premium::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(
                    135deg,
                    rgba(15, 23, 42, 0.55) 0%,
                    rgba(30, 41, 59, 0.65) 50%,
                    rgba(15, 23, 42, 0.5) 100%
            );
        }
        .catalog-cta-premium--block{
            position: relative;
            z-index: 1;
        }
        .catalog-cta-premium p { margin-bottom: 8px; }
        .catalog-cta-premium strong { color: white; }
        .phone-link-premium {
            font-size: 20px; font-weight: 700; color: var(--header-green);
            margin: 0 8px;
        }
        .phone-link-premium:hover { transform: translateX(3px);  }

        /* ===== WHY CHOOSE PREMIUM ===== */
        .why-choose-premium { padding: clamp(80px, 12vw, 120px) 0; background: var(--premium-light); }
        .split-layout {
            display: grid; grid-template-columns: 1fr 1.2fr; gap: 60px; align-items: start;
        }
        @media (max-width: 968px) { .split-layout { grid-template-columns: 1fr; gap: 40px; } }

        .split-image { position: relative; }
        .image-wrapper {
            position: relative; border-radius: 16px; overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }
        .image-wrapper img { width: 100%; height: auto; display: block; }
        .image-badge {
            position: absolute; bottom: 24px; left: 24px; background: #fff;
            padding: 20px 28px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .badge-number { display: block; font-size: 40px; font-weight: 300; color: var(--premium-gold); line-height: 1; }
        .badge-text { font-size: 13px; color: var(--premium-gray); margin-top: 4px; }

        .advantages-list-premium { margin: 32px 0; }
        .advantage-item {
            display: flex; gap: 16px; margin-bottom: 24px; padding: 20px;
            background: #fff; border-radius: 12px; transition: var(--transition);
        }
        .advantage-item:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.08); transform: translateX(6px); }
        .advantage-check {
            width: 40px; height: 40px; background: linear-gradient(135deg, var(--premium-blue) 0%, var(--premium-gold) 100%);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 18px; flex-shrink: 0;
        }
        .advantage-content h4 { font-size: 16px; font-weight: 600; margin-bottom: 6px; color: var(--premium-text); }
        .advantage-content p { font-size: 14px; color: var(--premium-gray); line-height: 1.5; margin: 0; }

        /* ===== PROCESS PREMIUM ===== */
        .process-premium { padding: clamp(80px, 12vw, 120px) 0; background: #fff; }
        .process-steps {
            display: flex; align-items: flex-start; justify-content: space-between; gap: 24px;
        }
        @media (max-width: 1024px) { .process-steps { flex-direction: column; } }

        .step-item { flex: 1; text-align: center; position: relative; padding: 0 8px; }
        .step-number {
            font-size: 56px; font-weight: 300; color: rgba(37,99,235,0.12);
            line-height: 1; margin-bottom: 12px; position: relative; z-index: 1;
        }
        .step-number::after {
            content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 90px; height: 90px; background: linear-gradient(135deg, rgba(37,99,235,0.06) 0%, rgba(212,175,55,0.06) 100%);
            border-radius: 50%; z-index: -1;
        }
        .step-content h4 { font-size: 18px; font-weight: 600; margin-bottom: 8px; color: var(--premium-text); }
        .step-content p { font-size: 14px; color: var(--premium-gray); line-height: 1.5; margin: 0; }

        .step-connector {
            width: 80px; height: 2px; background: linear-gradient(90deg, var(--premium-blue), var(--premium-gold));
            align-self: center;
        }
        @media (max-width: 1024px) { .step-connector { width: 2px; height: 40px; } }

        /* ===== TESTIMONIALS PREMIUM ===== */
        .testimonials-premium {
            padding: clamp(80px, 12vw, 120px) 0;
            background: linear-gradient(135deg, var(--premium-darker) 0%, var(--premium-dark) 100%);
        }
        .testimonials-premium .section-label { color: var(--premium-gold); }
        .testimonials-premium .section-title { color: #fff; }

        .testimonials-slider-premium {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }
        .testimonial-card-premium {
            background: rgba(255,255,255,0.06); backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.12); border-radius: 16px;
            padding: 32px; transition: var(--transition);
        }
        .testimonial-card-premium:hover {
            background: rgba(255,255,255,0.1); transform: translateY(-4px);
        }
        .testimonial-rating { color: var(--premium-gold); font-size: 18px; margin-bottom: 16px; letter-spacing: 3px; }
        .testimonial-text {
            font-size: 15px; line-height: 1.7; color: rgba(255,255,255,0.92);
            margin-bottom: 24px; font-style: italic;
        }
        .testimonial-author { display: flex; align-items: center; gap: 14px; }
        .author-avatar {
            width: 48px; height: 48px; background: linear-gradient(135deg, var(--premium-blue) 0%, var(--premium-gold) 100%);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 600; font-size: 16px; flex-shrink: 0;
        }
        .author-info h5 { font-size: 15px; font-weight: 600; color: #fff; margin: 0 0 2px 0; }
        .author-info span { font-size: 13px; color: rgba(255,255,255,0.6); }

        /* ===== CTA PREMIUM ===== */
        .cta-premium {
            position: relative; padding: clamp(80px, 12vw, 120px) 0;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .cta-premium::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.55) 0%, rgba(30, 41, 59, 0.65) 50%, rgba(15, 23, 42, 0.5) 100%);
        }
        .cta-bg-pattern {
            position: absolute; inset: 0; pointer-events: none;
            background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.12) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(212,175,55,0.12) 0%, transparent 50%);
        }
        .cta-content { position: relative; z-index: 1; text-align: center; max-width: 650px; margin: 0 auto; }
        .cta-title {
            font-size: clamp(28px, 4vw, 40px); font-weight: 300; color: #fff;
            margin-bottom: 12px; letter-spacing: -0.5px;
        }
        .cta-subtitle {
            font-size: 16px; color: rgba(255,255,255,0.92); margin-bottom: 40px; line-height: 1.6;
        }

        .cta-form-premium { display: flex; flex-direction: column; gap: 14px; margin-bottom: 32px; }
        .form-group input {
            width: 100%; padding: 16px 20px; border: none; border-radius: 10px;
            background: rgba(255,255,255,0.96); font-size: 15px; transition: var(--transition);
        }
        .form-group input:focus { outline: none; box-shadow: 0 0 0 3px rgba(212,175,55,0.35); }
        .form-note { font-size: 12px; color: rgba(255,255,255,0.75); }
        .form-note a { color: var(--premium-gold); text-decoration: none; }
        .form-note a:hover { text-decoration: underline; }

        .cta-contacts-premium { display: flex; gap: 24px; justify-content: center; flex-wrap: wrap; }
        .cta-contacts-premium a{
            color: white;
        }


        /* ===== MODALS PREMIUM ===== */
        .modal-premium {
            position: fixed; inset: 0; z-index: 3000; display: none;
            align-items: center; justify-content: center; padding: 20px;
        }
        .modal-premium.active { display: flex; animation: modalFadeIn 0.3s ease; }
        @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }

        .modal-backdrop, .modal-overlay {
            position: absolute; inset: 0; background: rgba(2,6,23,0.85);
            backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
        }
        .modal-content-premium {
            position: relative; background: #fff; border-radius: 20px;
            padding: 40px 32px; max-width: 480px; width: 100%; z-index: 1;
            animation: modalSlideUp 0.4s ease; box-shadow: 0 30px 60px rgba(0,0,0,0.4);
        }
        @keyframes modalSlideUp {
            from { opacity: 0; transform: translateY(25px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .modal-close {
            position: absolute; top: 16px; right: 16px; width: 36px; height: 36px;
            background: rgba(0,0,0,0.06); border: none; border-radius: 50%;
            color: var(--premium-gray); font-size: 24px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: var(--transition);
        }
        .modal-close:hover { background: var(--premium-gold); color: var(--premium-darker); }

        .modal-title { font-size: 24px; font-weight: 500; color: var(--premium-text); margin-bottom: 8px; }
        .modal-text { color: var(--premium-gray); margin-bottom: 28px; line-height: 1.6; font-size: 15px; }

        .modal-form { display: flex; flex-direction: column; gap: 14px; }
        .modal-form input, .modal-form textarea {
            padding: 14px 18px; border: 1px solid var(--premium-border); border-radius: 10px;
            font-size: 15px; transition: var(--transition); background: #fff;
        }
        .modal-form input:focus, .modal-form textarea:focus {
            outline: none; border-color: var(--premium-gold);
            box-shadow: 0 0 0 3px rgba(212,175,55,0.15);
        }
        .modal-form textarea { resize: vertical; min-height: 90px; }

        /* ===== ANIMATIONS ===== */
        .animate-fade-up { opacity: 0; transform: translateY(25px); animation: fadeUp 0.7s ease forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; } .delay-4 { animation-delay: 0.4s; }

        .animate-in { opacity: 1 !important; transform: translateY(0) !important; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero-stats { flex-direction: column; gap: 24px; }
            .stat-divider { width: 50px; height: 1px; }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .catalog-tabs { overflow-x: auto; justify-content: flex-start; padding-bottom: 8px; scrollbar-width: none; }
            .catalog-tabs::-webkit-scrollbar { display: none; }
            .tab-btn { flex-shrink: 0; }
            .process-steps { gap: 32px; }
            .step-connector { display: none; }
            .testimonials-slider-premium { grid-template-columns: 1fr; }
            .cta-contacts-premium { flex-direction: column; align-items: center; }
            .modal-content-premium { padding: 32px 24px; }
        }
        @media (max-width: 480px) {
            .hero-title { font-size: 32px; }
            .hero-subtitle { font-size: 15px; }
            .section-title { font-size: 26px; }
            .features-grid { grid-template-columns: 1fr; }
            .advantage-item { padding: 16px; gap: 12px; }
            .product-card-premium.featured { transform: none; }
            .product-card-premium.featured:hover { transform: translateY(-6px); }
        }
        /* Карточка как в архиве, но с зелёным акцентом */
        .station-card-home {
            background: var(--card-bg); border-radius: var(--card-radius);
            overflow: hidden; box-shadow: var(--card-shadow);
            transition: var(--card-transition); display: flex; flex-direction: column;
            position: relative; border: 1px solid transparent;
        }
        .station-card-home:hover {
            transform: translateY(-4px);
            border-color: var(--card-green);
            box-shadow: 0 10px 30px rgba(34,197,94,0.15);
        }
        .station-badge-home {
            position: absolute; top: 1rem; left: 1rem; z-index: 2;
            background: var(--card-green); color: #fff;
            padding: 0.35rem 0.85rem; border-radius: 20px;
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
        }
        .station-card-home__image {
            aspect-ratio: 4/3; overflow: hidden; background: white;
        }
        .station-card-home__image a { display: block; height: 100%; }
        .station-card-home__image img {
            width: 100%; height: 100%; object-fit: contain; padding: 1rem;
            transition: transform 0.3s ease;
        }
        .station-card-home:hover .station-card-home__image img { transform: scale(1.05); }
        .station-card-home__content { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
        .station-card-home__title {
            margin: 0 0 0.5rem; font-size: 1.1rem; font-weight: 600; color: var(--card-text); line-height: 1.4;
        }
        .station-card-home__title a { color: inherit; text-decoration: none; }
        .station-card-home__title a:hover { color: var(--card-blue); }
        .station-card-home__people {
            margin: 0 0 1rem; color: var(--card-text-muted); font-size: 0.9rem;
            display: flex; align-items: center; gap: 0.4rem;
        }
        .station-card-home__price { margin-top: auto; margin-bottom: 1rem; }
        .price-old {
            display: block; color: var(--card-text-muted); text-decoration: line-through;
            font-size: 0.9rem; margin-bottom: 0.25rem;
        }
        .price-current { font-size: 1.35rem; font-weight: 700; color: var(--card-text); }
        .station-card-home__actions {
            display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem;
        }
        .btn-card-home {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.65rem 0.85rem; border-radius: 8px;
            font-weight: 600; font-size: 0.9rem; text-decoration: none;
            transition: var(--card-transition); text-align: center; border: none; cursor: pointer;
        }
        .btn-card-home.btn-outline {
            background: transparent; border: 2px solid var(--card-blue); color: var(--card-blue);
        }
        .btn-card-home.btn-outline:hover { background: var(--card-blue); color: #fff; }
        .btn-card-home.btn-green {
            background: linear-gradient(135deg, var(--card-green) 0%, var(--card-green-hover) 100%);
            color: #fff;
        }
        .btn-card-home.btn-green:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34,197,94,0.35);
        }
        @media (max-width: 768px) {
            .station-card-home__actions { grid-template-columns: 1fr; }
        }
        .icon { display: inline-block; vertical-align: middle; }
        .contact-link{
            display: flex;
            align-items: center;
            gap: 12px;
        }
    </style>

    <main class="main-content">

        <!-- Hero Section Premium -->
        <section class="hero-premium">
            <div class="hero-bg-image" style="background-image: url('https://sun9-56.userapi.com/s/v1/ig2/kbikJlm6FecDGc7rQ2y4nLt1d_CnM77y-rFvuDia8OG9XFQWu9PPrVRs-TWwpSV223IrJLI-IwP-QDFyB20lbQCZ.jpg?quality=95&as=32x24,48x36,72x54,108x81,160x120,240x180,360x270,480x360,540x405,640x480,720x540,1080x810,1280x960,1440x1080,1448x1086&from=bu&u=dSfrTf5fBdqBF5EfR4h6Xq0IypKt2NkMsitYpkqpHv8&cs=1448x0');"></div>
            <div class="hero-bg-overlay"></div>

            <div class="container">
                <div class="hero-content">

                    <!-- Телефон + кнопка -->
                    <div class="hero-contacts-top animate-fade-up">
                        <a href="tel:<?php echo $company['phone_clean']; ?>" class="hero-phone-link">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            <span><?php echo $company['phone']; ?></span>
                        </a>
                        <button class="btn-premium btn-sm btn-outline open-modal" data-modal="callback">
                            Заказать звонок
                        </button>
                    </div>

                    <!-- Заголовок -->
                    <h1 class="hero-title animate-fade-up delay-1">
                        Автономная канализация ТОПАС<br>
                        <span class="gradient-text">для дома и дачи без переплат!</span>
                    </h1>

                    <!-- Подзаголовок -->
                    <p class="hero-subtitle animate-fade-up delay-2">
                        Станции в наличии! Бесплатная доставка по всей <?php echo $company['region']; ?>
                        и выгодная стоимость монтажа
                    </p>

                    <!-- Статистика -->
                    <div class="hero-stats animate-fade-up delay-3">
                        <div class="stat-item">
                            <span class="stat-number" data-count="8">0</span><span class="stat-suffix">+</span>
                            <span class="stat-label">лет опыта</span>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="1000">0</span><span class="stat-suffix">+</span>
                            <span class="stat-label">установок</span>
                        </div>
                    </div>

                    <!-- Кнопки -->
                    <div class="hero-buttons animate-fade-up delay-4">
                        <a href="#catalog" class="btn-premium btn-primary">
                            <span class="btn-text">Подобрать станцию</span>
                            <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <button class="btn-premium btn-outline open-modal" data-modal="engineer">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                            </svg>
                            <span>Бесплатный выезд инженера</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Luxury Features -->
        <section class="luxury-features">
            <div class="container">
                <div class="features-grid">
                    <div class="feature-luxury">
                        <div class="feature-icon-wrapper">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                            </svg>
                        </div>
                        <h3>Простота монтажа</h3>
                        <p>Независимость от типа грунта и уровня грунтовых вод — установка в любых условиях</p>
                    </div>
                    <div class="feature-luxury">
                        <div class="feature-icon-wrapper">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="M8 11h.01M12 11h.01M16 11h.01"/>
                            </svg>
                        </div>
                        <h3>Комфорт без запахов</h3>
                        <p>Полная герметичность и биологическая очистка — никаких неприятных запахов на участке</p>
                    </div>
                    <div class="feature-luxury">
                        <div class="feature-icon-wrapper">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                            </svg>
                        </div>
                        <h3>Срок службы 50+ лет</h3>
                        <p>Прочный полипропиленовый корпус не подвержен коррозии и разрушению</p>
                    </div>
                    <div class="feature-luxury">
                        <div class="feature-icon-wrapper">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </div>
                        <h3>Экономия на обслуживании</h3>
                        <p>Не требуется вызов ассенизаторской машины — чистка 2-4 раза в год самостоятельно</p>
                    </div>
                </div>
            </div>
        </section>

        <?php
        // Параметры запроса
        $args = array(
            'post_type' => 'stations',
            'posts_per_page' => 8,
            'orderby' => 'menu_order',
            'order' => 'ASC',

        );
        $stations_query = new WP_Query($args);
        ?>

        <section id="catalog" class="catalog-premium" style="background: var(--bg-secondary); padding: clamp(80px, 12vw, 120px) 0;">
            <div class="container">

                <!-- Заголовок -->
                <div class="section-header" style="text-align: center; margin-bottom: 48px;">
            <span class="section-label" style="display: inline-block; font-size: 12px; font-weight: 600; color: var(--color-primary); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px; padding: 6px 16px; background: var(--color-primary-soft); border-radius: 20px;">
                Каталог
            </span>
                    <h2 class="section-title" style="font-size: clamp(28px, 4vw, 40px); font-weight: 600; color: var(--text-primary); margin-bottom: 12px;">
                        Выберите идеальную станцию
                    </h2>
                    <p class="section-subtitle" style="font-size: 16px; color: var(--text-secondary); max-width: 600px; margin: 0 auto; line-height: 1.6;">
                        Индивидуальный подбор под количество проживающих и особенности участка
                    </p>
                </div>

                <!-- Табы -->
                <div class="catalog-tabs" style="display: flex; justify-content: center; gap: 10px; margin-bottom: 40px; flex-wrap: wrap;">
                    <button class="tab-btn active" data-tab="all" style="padding: 10px 22px; background: var(--bg-primary); border: 2px solid var(--border-color); border-radius: 30px; cursor: pointer; transition: var(--transition); font-size: 14px; font-weight: 500; color: var(--text-secondary);">Все модели</button>
                    <button class="tab-btn" data-tab="small" style="padding: 10px 22px; background: var(--bg-primary); border: 2px solid var(--border-color); border-radius: 30px; cursor: pointer; transition: var(--transition); font-size: 14px; font-weight: 500; color: var(--text-secondary);">До 5 человек</button>
                    <button class="tab-btn" data-tab="medium" style="padding: 10px 22px; background: var(--bg-primary); border: 2px solid var(--border-color); border-radius: 30px; cursor: pointer; transition: var(--transition); font-size: 14px; font-weight: 500; color: var(--text-secondary);">5-10 человек</button>
                    <button class="tab-btn" data-tab="large" style="padding: 10px 22px; background: var(--bg-primary); border: 2px solid var(--border-color); border-radius: 30px; cursor: pointer; transition: var(--transition); font-size: 14px; font-weight: 500; color: var(--text-secondary);">10+ человек</button>
                </div>

                <!-- Сетка карточек -->
                <div class="catalog-grid-premium" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; margin-bottom: 48px;">
                    <?php if ($stations_query->have_posts()) : ?>
                        <?php while ($stations_query->have_posts()) : $stations_query->the_post();
                            $price = carbon_get_post_meta(get_the_ID(), 'crb_price');
                            $old_price = carbon_get_post_meta(get_the_ID(), 'crb_old_price');
                            $people = carbon_get_post_meta(get_the_ID(), 'crb_people_count_text');
                            $is_hit = carbon_get_post_meta(get_the_ID(), 'crb_is_hit');

                            $people_num = (int) preg_replace('/[^0-9]/', '', $people);
                            $category = $people_num <= 5 ? 'small' : ($people_num <= 10 ? 'medium' : 'large');
                            ?>
                            <article class="station-card-home" data-category="<?php echo esc_attr($category); ?>">
                                <?php if ($is_hit) : ?>
                                    <span class="station-badge-home">✓ Хит</span>
                                <?php endif; ?>

                                <div class="station-card-home__image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('medium_large', array('loading' => 'lazy', 'style' => 'width:100%;height:100%;object-fit:contain;padding:1rem;')); ?>
                                        <?php else : ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/no-image.jpg" loading="lazy" style="width:100%;height:100%;object-fit:contain;padding:1rem;">
                                        <?php endif; ?>
                                    </a>
                                </div>

                                <div class="station-card-home__content">
                                    <h3 class="station-card-home__title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>

                                    <?php if ($people) : ?>
                                        <p class="station-card-home__people">
                                            <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                            </svg>
                                            <?php echo esc_html($people); ?>
                                        </p>
                                    <?php endif; ?>

                                    <div class="station-card-home__price">
                                        <?php if ($old_price && $old_price > $price) : ?>
                                            <span class="price-old"><?php echo number_format($old_price, 0, '.', ' '); ?> ₽</span>
                                        <?php endif; ?>
                                        <span class="price-current">
                                    <?php echo $price ? number_format($price, 0, '.', ' ') . ' ₽' : 'По запросу'; ?>
                                </span>
                                    </div>

                                    <div class="station-card-home__actions">
                                        <a href="<?php the_permalink(); ?>" class="btn-card-home btn-outline">Подробнее</a>
                                        <a href="<?php the_permalink(); ?>#order" class="btn-card-home btn-green">Заказать</a>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: var(--bg-primary); border-radius: var(--radius-lg);">
                            <p style="color: var(--text-secondary);">Станции пока не добавлены в каталог</p>
                        </div>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>

                <div class="catalog-cta-premium" style="background-image: url(<?=assets('/images/cta.jpg')?>;">
                    <div class="catalog-cta-premium--block">
                        <p>Не нашли подходящую модель? <strong>Мы поставляем всю линейку ТОПАС</strong></p>
                        <a href="tel:<?php echo $company['phone_clean']; ?>" class="phone-link-premium"><?php echo $company['phone']; ?></a>
                        <span>— подберем индивидуально за 5 минут</span>
                    </div>
                </div>

            </div>
        </section>

        <!-- Why Choose Us Premium -->
        <section class="why-choose-premium">
            <div class="container">
                <div class="split-layout">
                    <div class="split-image">
                        <div class="image-wrapper">
                            <img src=<?=assets('/images/topas-montazh.jpg')?>" alt="Монтаж ТОПАС">
                            <div class="image-badge">
                                <span class="badge-number">1 день</span>
                                <span class="badge-text">монтаж под ключ</span>
                            </div>
                        </div>
                    </div>
                    <div class="split-content">
                        <span class="section-label">Почему мы</span>
                        <h2 class="section-title text-left">Преимущества работы с нами</h2>
                        <p class="section-subtitle text-left">Мы предлагаем не просто оборудование, а комплексное решение для комфортной жизни</p>

                        <div class="advantages-list-premium">
                            <div class="advantage-item">
                                <div class="advantage-check">✓</div>
                                <div class="advantage-content">
                                    <h4>Бесплатный выезд инженера</h4>
                                    <p>После оформления заявки мы свяжемся с вами, чтобы договориться о бесплатной встрече на объекте — она ни к чему вас не обязывает</p>
                                </div>
                            </div>
                            <div class="advantage-item">
                                <div class="advantage-check">✓</div>
                                <div class="advantage-content">
                                    <h4>Доступные цены</h4>
                                    <p>Благодаря прямым поставкам и отлаженным процессам у нас максимально сокращены лишние расходы и прочие траты</p>
                                </div>
                            </div>
                            <div class="advantage-item">
                                <div class="advantage-check">✓</div>
                                <div class="advantage-content">
                                    <h4>Гарантия и договор</h4>
                                    <p>Мы работаем официально: перед началом работ подписываем договор, в котором закреплены все гарантии и ответственность сторон</p>
                                </div>
                            </div>
                            <div class="advantage-item">
                                <div class="advantage-check">✓</div>
                                <div class="advantage-content">
                                    <h4>Прозрачная смета</h4>
                                    <p>Работаем открыто и заранее согласовываем стоимость всех работ — никаких скрытых платежей</p>
                                </div>
                            </div>
                            <div class="advantage-item">
                                <div class="advantage-check">✓</div>
                                <div class="advantage-content">
                                    <h4>Монтаж за 1 день</h4>
                                    <p>Производим монтаж в любых типах грунта и в любую погоду — профессиональная бригада с опытом</p>
                                </div>
                            </div>
                            <div class="advantage-item">
                                <div class="advantage-check">✓</div>
                                <div class="advantage-content">
                                    <h4>Сервисное обслуживание</h4>
                                    <p>Производим гарантийное и постгарантийное обслуживание — всегда на связи и готовы помочь</p>
                                </div>
                            </div>
                        </div>

                        <button class="btn-premium btn-primary open-modal" data-modal="engineer">
                            <span>Вызвать инженера</span>
                            <svg class="btn-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process Premium -->
        <section class="process-premium">
            <div class="container">
                <div class="section-header">
                    <span class="section-label">Как мы работаем</span>
                    <h2 class="section-title">4 простых шага до комфортной жизни за городом без неприятных запахов и лишних хлопот</h2>
                </div>
                <div class="process-steps">
                    <div class="step-item">
                        <div class="step-number">01</div>
                        <div class="step-content"><h4>Заявка</h4><p>Оставьте заявку на сайте или позвоните нам</p></div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item">
                        <div class="step-number">02</div>
                        <div class="step-content"><h4>Выезд инженера</h4><p>Бесплатный замер участка и подбор оптимальной модели</p></div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item">
                        <div class="step-number">03</div>
                        <div class="step-content"><h4>Монтаж</h4><p>Установка и подключение за 1 день с гарантией качества</p></div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item">
                        <div class="step-number">04</div>
                        <div class="step-content"><h4>Запуск и сервис</h4><p>Пусконаладка, инструктаж и поддержка на весь срок эксплуатации</p></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Premium -->
        <section class="testimonials-premium">
            <div class="container">
                <div class="section-header">
                    <span class="section-label">Отзывы</span>
                    <h2 class="section-title">Что говорят наши клиенты</h2>
                </div>

                <?php
                // 🔷 ПРОСТОЙ ЗАПРОС БЕЗ META_QUERY
                $testimonials_args = array(
                    'post_type'      => 'reviews',
                    'posts_per_page' => 6,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );

                $testimonials_query = new WP_Query($testimonials_args);

                // 🔍 ОТЛАДКА (раскомментируйте для проверки)
                // echo '<!-- Запросов найдено: ' . $testimonials_query->found_posts . ' -->';
                // echo '<!-- SQL: ' . $testimonials_query->request . ' -->';

                if ($testimonials_query->have_posts()) :
                    ?>

                    <div class="testimonials-slider-premium">
                        <?php while ($testimonials_query->have_posts()) : $testimonials_query->the_post();

                            // 🔷 ПОЛУЧЕНИЕ ПОЛЕЙ - ПРОБУЕМ НЕСКОЛЬКО ВАРИАНТОВ
                            $author = get_post_meta(get_the_ID(), 'crb_review_author', true);
                            $position = get_post_meta(get_the_ID(), 'crb_review_position', true);
                            $rating = get_post_meta(get_the_ID(), 'crb_review_rating', true);
                            $avatar = get_post_meta(get_the_ID(), 'crb_review_avatar', true);
                            $service = get_post_meta(get_the_ID(), 'crb_review_service', true);
                            $content = wp_trim_words(get_the_content(), 40, '...');

                            // Если Carbon Fields возвращает массив
                            if (is_array($rating) && !empty($rating[0]['rating_value'])) {
                                $rating = intval($rating[0]['rating_value']);
                            } else {
                                $rating = intval($rating) ?: 5;
                            }

                            // Инициалы для аватара
                            $initials = 'К';
                            if ($author) {
                                $name_parts = explode(' ', trim($author));
                                $first_initial = mb_strtoupper(mb_substr($name_parts[0], 0, 1));
                                $second_initial = isset($name_parts[1]) ? mb_strtoupper(mb_substr($name_parts[1], 0, 1)) : '';
                                $initials = $first_initial . $second_initial;
                            }

                            // 🔍 ОТЛАДКА КОНКРЕТНОГО ОТЗЫВА
                            // echo '<!-- Отзыв ID: ' . get_the_ID() . ', Автор: ' . $author . ' -->';
                            ?>

                            <div class="testimonial-card-premium">
                                <div class="testimonial-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star <?php echo $i > $rating ? 'empty' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </div>

                                <p class="testimonial-text">
                                    "<?php echo esc_html($content); ?>"
                                </p>

                                <div class="testimonial-author">
                                    <div class="author-avatar">
                                        <?php if ($avatar): ?>
                                            <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($author); ?>">
                                        <?php else: ?>
                                            <?php echo esc_html($initials); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="author-info">
                                        <h5><?php echo esc_html($author ?: 'Анонимный клиент'); ?></h5>
                                        <span><?php echo esc_html($service ?: $position ?: ''); ?></span>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    </div>

                    <div class="testimonials-footer">
                        <a href="<?php echo esc_url(get_post_type_archive_link('reviews')); ?>" class="btn btn--outline">
                            Все отзывы →
                        </a>
                    </div>

                <?php
                else :
                    ?>

                    <div class="testimonials-empty">
                        <p>Отзывов пока нет. Будьте первым!</p>
                        <?php
                        // 🔍 ОТЛАДКА - покажем сколько всего отзывов
                        $total_reviews = wp_count_posts('reviews');
                        echo '<!-- Всего отзывов: ' . $total_reviews->publish . ' -->';
                        ?>
                    </div>

                <?php endif; wp_reset_postdata(); ?>

            </div>
        </section>

        <!-- CTA Premium -->
        <section class="cta-premium" id="contacts" style="background-image: url(<?=assets('/images/cta.jpg')?>;">
            <div class="cta-bg-pattern"></div>
            <div class="container">
                <div class="cta-content">
                    <h2 class="cta-title">Готовы сделать первый шаг?</h2>
                    <p class="cta-subtitle">Получите бесплатную консультацию инженера и скидку на монтаж при заказе до конца месяца</p>
                    <form class="cta-form-premium" id="consultationForm">
                        <div class="form-group"><input type="text" name="name" placeholder="Ваше имя" required></div>
                        <div class="form-group"><input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required></div>
                        <button type="submit" class="btn-premium btn-primary btn-large">
                            <span>Получить консультацию</span>
                            <svg class="btn-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                        <p class="form-note" style="color: white">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
                    </form>
                    <div class="cta-contacts-premium">
                        <a href="tel:<?php echo $company['phone_clean']; ?>" class="contact-link">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span><?php echo $company['phone']; ?></span>
                        </a>
                        <a href="mailto:<?php echo $company['email']; ?>" class="contact-link">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22,6 -10,7L2,6"/></svg>
                            <span><?php echo $company['email']; ?></span>
                        </a>

                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Modals -->
    <div class="modal-premium" id="modal-callback">
        <div class="modal-backdrop"></div>
        <div class="modal-content-premium">
            <button class="modal-close" aria-label="Закрыть">&times;</button>
            <h3 class="modal-title">Заказать звонок</h3>
            <p class="modal-text" id="callbackModalText">Оставьте номер телефона, и мы перезвоним вам в течение 15 минут</p>
            <form class="modal-form" id="callbackForm">
                <input type="text" name="name" placeholder="Ваше имя" required>
                <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                <button type="submit" class="btn-premium btn-primary btn-full">Жду звонка</button>
                <p class="form-note">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
            </form>
        </div>
    </div>

    <div class="modal-premium" id="modal-engineer">
        <div class="modal-backdrop"></div>
        <div class="modal-content-premium">
            <button class="modal-close" aria-label="Закрыть">&times;</button>
            <h3 class="modal-title">Вызвать инженера</h3>
            <p class="modal-text">Бесплатный выезд специалиста для замера и консультации</p>
            <form class="modal-form" id="engineerForm">
                <input type="text" name="name" placeholder="Ваше имя" required>
                <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                <input type="text" name="address" placeholder="Адрес участка">
                <button type="submit" class="btn-premium btn-primary btn-full">Вызвать инженера</button>
                <p class="form-note">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
            </form>
        </div>
    </div>

    <div class="modal-premium" id="modal-order">
        <div class="modal-backdrop"></div>
        <div class="modal-content-premium">
            <button class="modal-close" aria-label="Закрыть">&times;</button>
            <h3 class="modal-title">Заказать станцию</h3>
            <p class="modal-text" id="orderProductModalName"></p>
            <form class="modal-form" id="orderForm">
                <input type="text" name="name" placeholder="Ваше имя" required>
                <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                <textarea name="comment" placeholder="Комментарий" rows="3"></textarea>
                <button type="submit" class="btn-premium btn-primary btn-full">Отправить заявку</button>
                <p class="form-note">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Counters
            const counters = document.querySelectorAll('.stat-number');
            const animateCounters = () => {
                counters.forEach(counter => {
                    const target = +counter.getAttribute('data-count');
                    const count = +counter.innerText;
                    if (count < target) {
                        counter.innerText = Math.ceil(count + target / 150);
                        requestAnimationFrame(animateCounters);
                    } else counter.innerText = target;
                });
            };
            const statsObserver = new IntersectionObserver(entries => {
                entries.forEach(e => { if (e.isIntersecting) { animateCounters(); statsObserver.unobserve(e.target); } });
            }, { threshold: 0.5 });
            const statsSection = document.querySelector('.hero-stats');
            if (statsSection) statsObserver.observe(statsSection);

            // Catalog tabs
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const filter = this.dataset.tab;
                    document.querySelectorAll('.station-card-home').forEach(card => {
                        const cat = card.dataset.category;
                        const show = filter === 'all' || (cat && cat.includes(filter));
                        card.style.display = show ? 'flex' : 'none';
                        if (show) setTimeout(() => { card.style.opacity='1'; card.style.transform='translateY(0)'; }, 10);
                        else { card.style.opacity='0'; card.style.transform='translateY(20px)'; }
                    });
                });
            });

            // Smooth scroll
            document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(a => {
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const headerH = document.querySelector('.site-header-premium')?.offsetHeight || 0;
                        window.scrollTo({ top: target.getBoundingClientRect().top + window.pageYOffset - headerH - 20, behavior: 'smooth' });
                    }
                });
            });

            // Modals
            const modals = document.querySelectorAll('.modal-premium');
            const openBtns = document.querySelectorAll('.open-modal');
            const closeBtns = document.querySelectorAll('.modal-close, .modal-backdrop');

            function openModal(id) {
                const modal = document.getElementById(`modal-${id}`);
                if (modal) { modal.classList.add('active'); document.body.style.overflow = 'hidden'; }
            }
            function closeModal(modal) { modal?.classList.remove('active'); document.body.style.overflow = ''; }

            openBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const modalId = this.dataset.modal, product = this.dataset.product;
                    if (modalId === 'order' && product) {
                        const el = document.getElementById('orderProductModalName');
                        if (el) el.textContent = `Вы выбрали: ${product}`;
                    }
                    if (modalId === 'callback' && product) {
                        const el = document.getElementById('callbackModalText');
                        if (el) el.innerHTML = `Оставьте заявку на <strong>${product}</strong>, и мы перезвоним в течение 15 минут`;
                    }
                    openModal(modalId);
                });
            });
            closeBtns.forEach(btn => btn.addEventListener('click', e => closeModal(e.target.closest('.modal-premium'))));
            document.addEventListener('keydown', e => { if (e.key === 'Escape') modals.forEach(m => closeModal(m)); });

            // Forms
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const btn = this.querySelector('button[type="submit"]');
                    const original = btn.innerHTML;
                    btn.disabled = true; btn.innerHTML = '<span>Отправка...</span>';
                    try {
                        // Здесь ваша логика отправки
                        console.log('Form data:', Object.fromEntries(new FormData(this)));
                        alert('Спасибо! Мы свяжемся с вами в ближайшее время.');
                        this.reset();
                        closeModal(this.closest('.modal-premium'));
                    } catch(err) { alert('Ошибка отправки. Попробуйте ещё раз.'); }
                    finally { btn.disabled = false; btn.innerHTML = original; }
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

            // Scroll animations
            const scrollObs = new IntersectionObserver(entries => {
                entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('animate-in'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.product-card-premium, .advantage-item, .step-item, .testimonial-card-premium').forEach(el => {
                el.style.opacity = '0'; el.style.transform = 'translateY(25px)'; el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                scrollObs.observe(el);
            });
        });
    </script>

<?php get_footer(); ?>