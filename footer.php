<?php
/**
 * The template for displaying the footer - Premium Light
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

// Контакты компании
$company = array(
    'phone' => '+7 (8352) 44-65-34',
    'phone_clean' => '78352446534',
    'phone_alt' => '+7 (8352) 44-36-22',
    'email' => 'prodtorgservis21@mail.ru',
    'address' => 'г. Чебоксары, проезд Ишлейский, 13',
    'work_time' => 'Пн-Вс: 9:00 - 20:00',
);
?>

</main><!-- #main -->

<!-- ===== FOOTER STYLES ===== -->
<style>
    /* Footer Variables - Светлая тема */
    :root {
        --footer-bg: #f8fafc;
        --footer-bg-dark: #f1f5f9;
        --footer-text: #1e293b;
        --footer-text-muted: #64748b;
        --footer-border: #e2e8f0;
        --footer-gold: #d4af37;
        --footer-gold-hover: #f4d03f;
        --footer-blue: #2563eb;
        --transition: all 0.3s ease;
    }

    /* ===== FOOTER BASE ===== */
    .site-footer {
        background: var(--footer-bg);
        color: var(--footer-text);
        font-size: 14px;
        line-height: 1.6;
        margin-top: auto;
    }

    /* Container */
    .container {
        width: 100%; max-width: 1280px; margin: 0 auto;
        padding: 0 clamp(16px, 4vw, 32px);
    }

    /* ===== FOOTER TOP ===== */
    .footer-top {
        padding: clamp(48px, 8vw, 80px) 0;
        border-bottom: 1px solid var(--footer-border);
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr 1.2fr;
        gap: 40px;
    }
    @media (max-width: 1024px) {
        .footer-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .footer-grid { grid-template-columns: 1fr; gap: 32px; }
    }

    .footer-col { display: flex; flex-direction: column; gap: 20px; }

    /* Company Column */
    .footer-logo {
        display: inline-flex; align-items: center; gap: 10px;
        text-decoration: none; font-weight: 700; font-size: 20px;
        color: var(--footer-text);
    }
    .footer-logo img { max-width: 180px; height: auto; }

    .footer-description {
        color: var(--footer-text-muted);
        font-size: 14px; line-height: 1.7; margin: 0;
    }

    /* Social Links */
    .footer-social .social-label {
        display: block; font-size: 12px; color: var(--footer-gold);
        text-transform: uppercase; letter-spacing: 1px;
        margin-bottom: 12px; font-weight: 600;
    }
    .social-links { display: flex; gap: 10px; }
    .social-link {
        width: 40px; height: 40px; border-radius: 50%;
        background: #fff; border: 1px solid var(--footer-border);
        display: flex; align-items: center; justify-content: center;
        color: var(--footer-text); transition: var(--transition);
    }
    .social-link:hover {
        background: var(--footer-gold); border-color: var(--footer-gold);
        color: #0f172a; transform: translateY(-3px);
    }

    /* Footer Headings */
    .footer-heading {
        font-size: 15px; font-weight: 600; color: var(--footer-text);
        margin: 0 0 16px 0; padding-bottom: 12px;
        border-bottom: 2px solid var(--footer-gold);
        display: inline-block;
    }

    /* Footer Navigation */
    .footer-menu, .footer-list {
        list-style: none; padding: 0; margin: 0;
        display: flex; flex-direction: column; gap: 10px;
    }
    .footer-menu li a, .footer-list li a {
        color: var(--footer-text-muted); text-decoration: none;
        transition: var(--transition); position: relative;
        padding-left: 14px;
    }
    .footer-menu li a::before, .footer-list li a::before {
        content: ''; position: absolute; left: 0; top: 50%;
        transform: translateY(-50%); width: 4px; height: 4px;
        background: var(--footer-gold); border-radius: 50%;
        opacity: 0; transition: opacity 0.2s ease;
    }
    .footer-menu li a:hover, .footer-list li a:hover {
        color: var(--footer-gold); padding-left: 18px;
    }
    .footer-menu li a:hover::before, .footer-list li a:hover::before {
        opacity: 1;
    }

    /* Contact Items */
    .contact-item {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 8px 0; color: var(--footer-text);
    }
    .contact-icon {
        color: var(--footer-gold); flex-shrink: 0; margin-top: 2px;
    }
    .contact-link {
        color: var(--footer-text); font-weight: 500;
        text-decoration: none; transition: color 0.2s ease;
    }
    .contact-link:hover { color: var(--footer-gold); }
    .contact-text { color: var(--footer-text-muted); }

    /* Footer Button */
    .btn-footer {
        margin-top: 8px; padding: 12px 24px;
        background: var(--footer-gold); color: #0f172a;
        border: none; border-radius: 8px; font-weight: 600;
        font-size: 14px; cursor: pointer; transition: var(--transition);
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-footer:hover {
        background: var(--footer-gold-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(212, 175, 55, 0.3);
    }

    /* ===== FOOTER BOTTOM ===== */
    .footer-bottom {
        padding: 20px 0;
        background: var(--footer-bg-dark);
        font-size: 13px;
    }
    .footer-bottom-inner {
        display: flex; align-items: center; justify-content: space-between;
        gap: 20px; flex-wrap: wrap;
    }
    @media (max-width: 768px) {
        .footer-bottom-inner {
            flex-direction: column; text-align: center; gap: 12px;
        }
    }

    .footer-copyright p {
        margin: 0; color: var(--footer-text-muted);
    }
    .footer-copyright a {
        color: var(--footer-gold); text-decoration: none;
    }
    .footer-copyright a:hover { text-decoration: underline; }

    .footer-legal {
        display: flex; gap: 20px; flex-wrap: wrap;
    }
    .footer-legal a {
        color: var(--footer-text-muted); text-decoration: none;
        transition: color 0.2s ease;
    }
    .footer-legal a:hover { color: var(--footer-gold); }
    @media (max-width: 768px) {
        .footer-legal { order: 3; justify-content: center; }
    }

    .footer-developer {
        display: flex; align-items: center; gap: 6px;
        color: var(--footer-text-muted);
    }
    .footer-developer a {
        color: var(--footer-gold); text-decoration: none;
        font-weight: 500;
    }
    .footer-developer a:hover { text-decoration: underline; }
    @media (max-width: 768px) {
        .footer-developer { order: 2; }
    }

    /* ===== BACK TO TOP ===== */
    .back-to-top {
        position: fixed; bottom: 24px; right: 24px;
        width: 48px; height: 48px; border-radius: 50%;
        background: var(--footer-gold); color: #0f172a;
        border: none; cursor: pointer; display: flex;
        align-items: center; justify-content: center;
        transition: var(--transition); opacity: 0;
        visibility: hidden; transform: translateY(20px);
        z-index: 100; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }
    .back-to-top.visible {
        opacity: 1; visibility: visible; transform: translateY(0);
    }
    .back-to-top:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.5);
    }

    /* ===== MODAL (shared with header) ===== */
    .modal-premium {
        position: fixed; inset: 0; z-index: 3000; display: none;
        align-items: center; justify-content: center; padding: 20px;
    }
    .modal-premium.active { display: flex; animation: modalFadeIn 0.25s ease; }
    @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }

    .modal-backdrop, .modal-overlay {
        position: absolute; inset: 0;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(6px);
    }
    .modal-content-premium, .modal-panel {
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
    .modal-close, .modal-close-btn {
        position: absolute; top: 14px; right: 14px;
        width: 32px; height: 32px; background: #f1f5f9;
        border: none; border-radius: 50%;
        color: var(--footer-text-muted); cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: var(--transition); font-size: 20px;
    }
    .modal-close:hover, .modal-close-btn:hover {
        background: var(--footer-gold); color: #0f172a;
    }
    .modal-title {
        font-size: 22px; font-weight: 600; color: var(--footer-text);
        margin-bottom: 6px;
    }
    .modal-text, .modal-subtitle {
        font-size: 14px; color: var(--footer-text-muted);
        margin-bottom: 24px; line-height: 1.6;
    }
    .modal-form, .modal-form-premium {
        display: flex; flex-direction: column; gap: 12px;
    }
    .modal-form input, .form-group-premium input {
        width: 100%; padding: 14px 16px;
        background: #f8fafc; border: 1px solid var(--footer-border);
        border-radius: 8px; color: var(--footer-text);
        font-size: 15px; transition: var(--transition);
    }
    .modal-form input::placeholder, .form-group-premium input::placeholder {
        color: var(--footer-text-muted);
    }
    .modal-form input:focus, .form-group-premium input:focus {
        outline: none; background: #fff;
        border-color: var(--footer-gold);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
    }
    .modal-form .btn-full, .form-privacy {
        margin-top: 4px;
    }
    .form-note, .form-privacy {
        font-size: 11px; color: var(--footer-text-muted);
        text-align: center;
    }
    .form-note a, .form-privacy a {
        color: var(--footer-gold); text-decoration: none;
    }
    .form-note a:hover, .form-privacy a:hover {
        text-decoration: underline;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .footer-top { padding: 40px 0; }
        .footer-heading { font-size: 14px; }
        .contact-item { gap: 10px; }
        .back-to-top {
            width: 44px; height: 44px; bottom: 16px; right: 16px;
        }
    }
</style>

<!-- Premium Footer -->
<footer class="site-footer" id="site-footer">

    <!-- Footer Top -->
    <div class="footer-top">
        <div class="container">
            <div class="footer-grid">

                <!-- Company Column -->
                <div class="footer-col footer-col-company">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
                        <?php if (has_custom_logo()) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <span>ТОПАС</span>
                        <?php endif; ?>
                    </a>
                    <p class="footer-description">
                        Официальный дилер автономных канализаций ТОПАС.
                        Установка под ключ с гарантией качества и сервисным обслуживанием в Чувашской Республике.
                    </p>

                    <div class="footer-social">
                        <span class="social-label">Мы в соцсетях:</span>
                        <div class="social-links">
                            <a href="#" class="social-link" aria-label="Telegram">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M11.944 17.97L4.58 13.62 19.54 3l-5.13 14.972z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-link" aria-label="WhatsApp">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-link" aria-label="VKontakte">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M15.684 0H8.316C1.592 0 0 1.592 0 8.316v7.368C0 22.408 1.592 24 8.316 24h7.368C22.408 24 24 22.408 24 15.684V8.316C24 1.592 22.408 0 15.684 0zm3.692 16.168h-1.403c-.534 0-.698-.425-1.654-1.397-1.013-.972-1.454-1.104-1.703-1.104-.346 0-.441.099-.441.582v1.537c0 .415-.132.657-1.219.657-1.812 0-3.818-1.096-5.223-2.957C5.61 10.685 5 8.64 5 8.105c0-.314.115-.598.681-.598h1.403c.363 0 .494.165.632.598.69 2.012 1.845 3.78 2.314 3.78.181 0 .263-.082.263-.582v-2.25c-.05-1.03-.607-1.113-.607-1.476 0-.181.148-.363.363-.363h2.25c.314 0 .429.165.429.548v2.924c0 .314.148.429.247.429.198 0 .363-.214.726-.582 1.137-1.268 1.945-3.214 1.945-3.214.099-.314.28-.598.648-.598h1.403c.429 0 .528.214.429.598-.181.842-1.945 3.33-1.945 3.33s-.115.181-.115.363c0 .082.049.165.165.247.632.726 2.693 2.61 3.023 3.13.33.528.214.775-.198.775z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Navigation Column -->
                <div class="footer-col footer-col-nav">
                    <h4 class="footer-heading">Навигация</h4>
                    <nav class="footer-nav">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'menu_class' => 'footer-menu',
                            'container' => false,
                            'fallback_cb' => function() {
                                echo '<ul class="footer-menu">';
                                echo '<li><a href="/">Главная</a></li>';
                                echo '<li><a href="/station/">Каталог</a></li>';
                                echo '<li><a href="/services/">Услуги</a></li>';
                                echo '<li><a href="/about/">О компании</a></li>';
                                echo '<li><a href="/reviews/">Отзывы</a></li>';
                                echo '</ul>';
                            }
                        ));
                        ?>
                    </nav>
                </div>

                <!-- Services Column -->

                <!-- Contacts Column -->
                <div class="footer-col footer-col-contacts">
                    <h4 class="footer-heading">Контакты</h4>

                    <div class="contact-item">
                        <svg class="contact-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <a href="tel:<?php echo $company['phone_clean']; ?>" class="contact-link"><?php echo $company['phone']; ?></a>
                    </div>

                    <div class="contact-item">
                        <svg class="contact-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <path d="m22,6 -10,7L2,6"/>
                        </svg>
                        <a href="mailto:<?php echo antispambot($company['email']); ?>" class="contact-link"><?php echo antispambot($company['email']); ?></a>
                    </div>

                    <div class="contact-item">
                        <svg class="contact-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span class="contact-text"><?php echo $company['address']; ?></span>
                    </div>

                    <div class="contact-item">
                        <svg class="contact-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                        <span class="contact-text"><?php echo $company['work_time']; ?></span>
                    </div>

                    <button class="btn-footer open-modal" data-modal="callback">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        Заказать звонок
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">

                <!-- Copyright -->
                <div class="footer-copyright">
                    <p>&copy; <?php echo date('Y'); ?> <a href="<?php echo esc_url(home_url('/')); ?>">ТОПАС Чебоксары</a>. Все права защищены.</p>
                </div>

                <!-- Legal Links -->
                <div class="footer-legal">
                    <a href="/privacy/">Политика конфиденциальности</a>
                    <a href="/terms/">Пользовательское соглашение</a>
                </div>

                <!-- Developer Credit -->
                <div class="footer-developer">
                    <span>Разработка:</span>
                    <a href="https://izex.org/" target="_blank" rel="noopener">IZEX</a>
                </div>

            </div>
        </div>
    </div>

    <!-- Back to Top -->
    <button class="back-to-top" id="backToTop" aria-label="Наверх">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="m18 15-6-6-6 6"/>
        </svg>
    </button>

</footer>

<!-- Callback Modal -->
<div class="modal-premium" id="modal-callback">
    <div class="modal-backdrop"></div>
    <div class="modal-panel">
        <button class="modal-close-btn" aria-label="Закрыть">&times;</button>
        <h3 class="modal-title">Заказать звонок</h3>
        <p class="modal-subtitle">Оставьте номер телефона, и мы перезвоним вам в течение 15 минут</p>
        <form class="modal-form-premium" id="footerCallbackForm">
            <div class="form-group-premium">
                <input type="text" name="name" placeholder="Ваше имя *" required>
            </div>
            <div class="form-group-premium">
                <input type="tel" name="phone" placeholder="+7 (___) ___-__-__ *" required>
            </div>
            <button type="submit" class="btn-footer btn-full">
                <span>Жду звонка</span>
            </button>
            <p class="form-privacy">
                Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a>
            </p>
        </form>
    </div>
</div>

<!-- Footer JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ===== Back to Top Button =====
        const backToTop = document.getElementById('backToTop');

        function toggleBackToTop() {
            if (window.pageYOffset > 400) {
                backToTop?.classList.add('visible');
            } else {
                backToTop?.classList.remove('visible');
            }
        }

        window.addEventListener('scroll', toggleBackToTop, { passive: true });

        if (backToTop) {
            backToTop.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // ===== Modal Functionality =====
        const modals = document.querySelectorAll('.modal-premium');
        const openModalBtns = document.querySelectorAll('.open-modal');
        const closeModalBtns = document.querySelectorAll('.modal-close-btn, .modal-backdrop');

        function openModal(modalId) {
            const modal = document.getElementById(`modal-${modalId}`);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modal) {
            modal?.classList.remove('active');
            document.body.style.overflow = '';
        }

        openModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                openModal(this.getAttribute('data-modal'));
            });
        });

        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                closeModal(e.target.closest('.modal-premium'));
            });
        });

        // Close modal on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                modals.forEach(modal => closeModal(modal));
            }
        });

        // ===== Form Submission =====
        const callbackForm = document.getElementById('footerCallbackForm');
        if (callbackForm) {
            callbackForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = this.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;

                btn.disabled = true;
                btn.innerHTML = '<span>Отправка...</span>';

                const formData = new FormData(this);
                console.log('Callback form:', Object.fromEntries(formData));

                setTimeout(() => {
                    alert('Спасибо! Мы перезвоним вам в ближайшее время.');
                    this.reset();
                    closeModal(this.closest('.modal-premium'));
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }, 600);
            });
        }

        // ===== Phone Mask =====
        document.querySelectorAll('input[type="tel"]').forEach(input => {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (!value) { e.target.value = ''; return; }
                if (value[0] === '7' || value[0] === '8') value = value.slice(1);

                let formatted = '+7';
                if (value.length > 0) formatted += ' (' + value.slice(0, 3);
                if (value.length >= 3) formatted += ') ' + value.slice(3, 6);
                if (value.length >= 6) formatted += '-' + value.slice(6, 8);
                if (value.length >= 8) formatted += '-' + value.slice(8, 10);
                e.target.value = formatted;
            });
        });

    });
</script>

<?php wp_footer(); ?>
</body>
</html>