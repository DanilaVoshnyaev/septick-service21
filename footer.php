<?php
/**
 * The template for displaying the footer - Premium Light
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

// Контакты компании (из Carbon Fields, с запасными значениями)
$company = getCompanyContacts();

// Реквизиты оператора (из Carbon Fields → Настройки темы → Реквизиты)
$cf_opt = function ($key, $fallback) {
    if (function_exists('carbon_get_theme_option')) {
        $v = carbon_get_theme_option($key);
        if ($v !== null && $v !== '') {
            return $v;
        }
    }
    return $fallback;
};
$op_name = $cf_opt('operator_name', 'ИП Белков Сергей Валерьевич');
$op_inn  = $cf_opt('operator_inn', '210403597536');
$op_ogrn = $cf_opt('operator_ogrn', '323210000047416');
?>

</main>
<footer class="site-footer" id="site-footer">

    <!-- Footer Top -->
    <div class="footer-top">
        <div class="container">
            <div class="footer-grid">

                <!-- Company Column -->
                <div class="footer-col footer-col-company">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
                        <?php if (true) : ?>
                            <img src="<?=assets('/images/logo-transparent.png')?>" alt="" width="108">
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
                                echo '<li><a href="/stations/">Каталог</a></li>';
                                echo '<li><a href="' . esc_url(izex_prices_page_url()) . '">Цены</a></li>';
                                echo '<li><a href="/services/">Услуги</a></li>';
                                echo '<li><a href="' . esc_url(get_post_type_archive_link('works')) . '">Наши работы</a></li>';
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

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <div class="footer-company-info">
                    <p class="footer-legal-line">
                        &copy;<?php echo date('Y'); ?> <?php echo esc_html($op_name); ?>
                        <?php if ($op_inn) : ?> / ИНН: <?php echo esc_html($op_inn); ?><?php endif; ?>
                        <?php if ($op_ogrn) : ?> / ОГРНИП: <?php echo esc_html($op_ogrn); ?><?php endif; ?>
                    </p>
                    <p class="footer-tagline">
                        Септики ТОПАС &middot; Продажа &mdash; Монтаж &mdash; Обслуживание
                    </p>
                    <p class="footer-contacts-line">
                        Контактный телефон:
                        <a href="tel:<?php echo esc_attr($company['phone_clean']); ?>"><?php echo esc_html($company['phone']); ?></a>
                        <?php if (!empty($company['work_time'])) : ?>
                            <span class="footer-worktime">(<?php echo esc_html($company['work_time']); ?>)</span>
                        <?php endif; ?>
                    </p>
                    <p class="footer-contacts-line">
                        Электронная почта:
                        <a href="mailto:<?php echo antispambot($company['email']); ?>"><?php echo antispambot($company['email']); ?></a>
                    </p>
                </div>

                <div class="footer-legal">
                    <a href="/privacy/">Политика конфиденциальности</a>
                </div>

            </div>
        </div>
    </div>

    <button class="back-to-top" id="backToTop" aria-label="Наверх">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="m18 15-6-6-6 6"/>
        </svg>
    </button>

</footer>

<?php
// Плавающая панель действий на мобильных (ТЗ 5.3) + мессенджеры (ТЗ 4.6).
$mobile_bar = getCompanyContacts();
?>
<nav class="mobile-action-bar" aria-label="Быстрые действия">
    <a class="mobile-action-bar__btn mobile-action-bar__btn--call" href="tel:<?php echo esc_attr($mobile_bar['phone_clean']); ?>">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        <span>Позвонить</span>
    </a>
    <?php if (!empty($mobile_bar['whatsapp'])) : ?>
        <a href="https://max.ru/u/f9LHodD0cOI_AGyWf9AKcrl72RIFsKRL7vOApMiqwT37En8F81IprazW1ro" class="mobile-action-bar__btn mobile-action-bar__btn--wa" target="_blank" rel="noopener nofollow" aria-label="MAX">
            <img src="https://maxicons.ru/icons/MAX.svg" alt="Иконка MAX" width="32" height="32">
        </a>
    <?php endif; ?>
    <button type="button" class="mobile-action-bar__btn mobile-action-bar__btn--order open-modal" data-modal="order">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 15h6M9 11h2"/></svg>
        <span>Заявка</span>
    </button>
</nav>

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
            <label class="form-consent"><input type="checkbox" name="consent" required checked> Согласен на обработку персональных данных</label>

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
            <label class="form-consent"><input type="checkbox" name="consent" required checked> Согласен на обработку персональных данных</label>
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

            <label class="form-consent"><input type="checkbox" name="consent" required checked> Согласен на обработку персональных данных</label>


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
</div>

<div class="cookie-consent" id="cookieConsent" role="dialog" aria-live="polite" aria-label="Уведомление об использовании cookie" hidden>
    <div class="cookie-consent__inner">
        <p class="cookie-consent__text">
            Мы используем файлы cookie и обрабатываем пользовательские данные (IP-адрес, сведения о действиях
            на сайте) для работы сайта, аналитики и улучшения сервиса. Продолжая пользоваться сайтом, вы
            соглашаетесь с этим в соответствии с
            <a href="/privacy/">Политикой конфиденциальности</a>.
        </p>
        <button type="button" class="cookie-consent__btn" id="cookieConsentAccept">Принять</button>
    </div>
</div>

<script>
    (function () {
        var KEY = 'cookie_consent_accepted';
        var box = document.getElementById('cookieConsent');
        if (!box) return;
        var accepted;
        try { accepted = localStorage.getItem(KEY); } catch (e) { accepted = null; }
        if (!accepted) {
            box.hidden = false;
            requestAnimationFrame(function () { box.classList.add('is-visible'); });
        }
        var btn = document.getElementById('cookieConsentAccept');
        if (btn) {
            btn.addEventListener('click', function () {
                try { localStorage.setItem(KEY, '1'); } catch (e) {}
                box.classList.remove('is-visible');
                setTimeout(function () { box.hidden = true; }, 300);
            });
        }
    })();
</script>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=109479860', 'ym');

    ym(109479860, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/109479860" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<?php if (function_exists('printSocialFloat')) { printSocialFloat(); } ?>
<?php wp_footer(); ?>
</body>
</html>