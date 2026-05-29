<?php
/**
 * The template for displaying the footer - Premium Light
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

// Контакты компании (из Carbon Fields, с запасными значениями)
$company = getCompanyContacts();
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
                            <a href="https://max.ru/u/f9LHodD0cOI_AGyWf9AKcrl72RIFsKRL7vOApMiqwT37En8F81IprazW1ro" class="social-link" aria-label="MAX">
                                <img src="https://maxicons.ru/icons/MAX.svg" alt="Иконка MAX" width="32" height="32">
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
</div>

<!-- Order Modal (каталог: «Заказать») -->
<div class="modal-premium" id="modal-order">
    <div class="modal-backdrop"></div>
    <div class="modal-panel">
        <button class="modal-close-btn" aria-label="Закрыть">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
        </button>
        <div class="modal-header">
            <h3 class="modal-title">Оставить заявку</h3>
            <p class="modal-subtitle">Перезвоним в течение 15 минут и ответим на вопросы</p>
            <p class="modal-product js-order-product" hidden></p>
        </div>

        <form class="modal-form-premium" id="orderForm" data-form-type="catalog_order">
            <input type="hidden" name="action" value="premium_form_submit">
            <input type="hidden" name="product_name" class="js-order-product-field" value="">
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
                <textarea name="comment" rows="3" placeholder="Комментарий (необязательно)"></textarea>
            </div>

            <button type="submit" class="btn-premium btn-full btn-gold">
                <span class="spinner"></span>
                <span class="btn-text">Отправить заявку</span>
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