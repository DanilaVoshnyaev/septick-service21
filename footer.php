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

</main><!-- #main --><!-- Premium Footer -->
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
<!--                <div class="footer-developer">
                    <span>Разработка:</span>
                    <a href="https://izex.org/" target="_blank" rel="noopener">IZEX</a>
                </div>-->

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
<!-- Modals Premium -->
<div class="modal-premium" id="modal-callback">
    <div class="modal-backdrop"></div>
    <div class="modal-panel">
        <button class="modal-close-btn" aria-label="Закрыть">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
        </button>
        <div class="modal-header">
            <h3 class="modal-title">Заказать звонок</h3>
            <p class="modal-subtitle">Перезвоним в течение 15 минут</p>
        </div>

        <form class="modal-form-premium" id="callbackForm" data-form-type="callback">
            <!-- Hidden поля для AJAX-обработчика -->
            <input type="hidden" name="action" value="premium_form_submit">
            <input type="hidden" name="page_url" value="<?php echo esc_url($_SERVER['REQUEST_URI'] ?? ''); ?>">

            <div class="form-group form-group-premium">
                <input type="text" name="name" placeholder="Ваше имя *" required>
                <span class="form-error"></span>
            </div>
            <div class="form-group form-group-premium">
                <input type="tel" name="phone" placeholder="+7 (___) ___-__-__ *" required>
                <span class="form-error"></span>
            </div>

            <button type="submit" class="btn-premium btn-full btn-gold">
                <span class="spinner"></span>
                <span class="btn-text">Жду звонка</span>
                <svg class="btn-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </button>
            <p class="form-privacy">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
        </form>
    </div>
</div>

<div class="modal-premium" id="modal-engineer">
    <div class="modal-backdrop"></div>
    <div class="modal-panel">
        <button class="modal-close-btn" aria-label="Закрыть">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
        </button>
        <div class="modal-header">
            <h3 class="modal-title">Вызвать инженера</h3>
            <p class="modal-subtitle">Бесплатный выезд и консультация</p>
        </div>

        <form class="modal-form-premium" id="engineerForm" data-form-type="engineer">
            <!-- Hidden поля для AJAX-обработчика -->
            <input type="hidden" name="action" value="premium_form_submit">
            <input type="hidden" name="page_url" value="<?php echo esc_url($_SERVER['REQUEST_URI'] ?? ''); ?>">

            <div class="form-group form-group-premium">
                <input type="text" name="name" placeholder="Ваше имя *" required>
                <span class="form-error"></span>
            </div>
            <div class="form-group form-group-premium">
                <input type="tel" name="phone" placeholder="+7 (___) ___-__-__ *" required>
                <span class="form-error"></span>
            </div>
            <div class="form-group form-group-premium">
                <input type="text" name="address" placeholder="Адрес участка">
            </div>

            <button type="submit" class="btn-premium btn-full btn-gold">
                <span class="spinner"></span>
                <span class="btn-text">Вызвать инженера</span>
                <svg class="btn-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </button>
            <p class="form-privacy">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
        </form>
    </div>
</div><?php wp_footer(); ?>
</body>
</html>