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
<footer class="ss-footer" id="site-footer">
    <div class="ss-container ss-footer__grid">
        <div class="ss-footer__about">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="ss-footer__logo">
                <img src="<?php echo esc_url(assets('/images/logo-transparent-216.png')); ?>"
                     alt="Сервис Септик21" width="108" height="49" loading="lazy" decoding="async">
            </a>
            <p>Подбор, монтаж и обслуживание автономной канализации в Чебоксарах и по Чувашии. Станции ТОПАС и других марок.</p>
        </div>
        <nav class="ss-footer__col" aria-label="Разделы сайта">
            <p class="ss-footer__head">Разделы</p>
            <ul>
                <li><a href="/stations/">Каталог</a></li>
                <li><a href="/podbor-septika/">Подбор септика</a></li>
                <li><a href="<?php echo esc_url(izex_prices_page_url()); ?>">Цены</a></li>
                <?php if (izex_has_published_works()) : ?>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('works')); ?>">Наши работы</a></li>
                <?php endif; ?>
                <li><a href="/reviews/">Отзывы</a></li>
                <li><a href="/about/">О компании</a></li>
                <li><a href="/contacts/">Контакты</a></li>
            </ul>
        </nav>
        <nav class="ss-footer__col" aria-label="Решения">
            <p class="ss-footer__head">Решения</p>
            <ul>
                <li><a href="/montazh-septika-lyuboj-marki/">Монтаж септика любой марки</a></li>
                <li><a href="/obsluzhivanie-septikov-lyubyh-marok/">Обслуживание септиков</a></li>
                <li><a href="/septik-dlya-chastnogo-doma/">Септик для частного дома</a></li>
                <li><a href="/septik-dlya-dachi/">Септик для дачи</a></li>
                <li><a href="/septik-dlya-kottedzha/">Септик для коттеджа</a></li>
                <li><a href="/septik-pri-vysokih-gruntovyh-vodah/">При высоких грунтовых водах</a></li>
                <li><a href="/konservaciya-septika-topas/">Консервация на зиму</a></li>
                <li><a href="/septik-novocheboksarsk/">Септик в Новочебоксарске</a></li>
            </ul>
        </nav>
        <div class="ss-footer__col">
            <p class="ss-footer__head">Связаться</p>
            <a class="ss-footer__phone" href="tel:<?php echo esc_attr($company['phone_clean']); ?>"><?php echo esc_html($company['phone']); ?></a>
            <p class="ss-muted"><?php echo esc_html($company['work_time']); ?></p>
            <p><a href="mailto:<?php echo antispambot($company['email']); ?>"><?php echo antispambot($company['email']); ?></a></p>
            <p><a href="https://max.ru/u/f9LHodD0cOI_AGyWf9AKcrl72RIFsKRL7vOApMiqwT37En8F81IprazW1ro">Написать в MAX</a></p>
        </div>
    </div>
    <div class="ss-container ss-footer__legal">
        <p>&copy;<?php echo date('Y'); ?> <?php echo esc_html($op_name); ?><?php if ($op_inn) : ?> · ИНН <?php echo esc_html($op_inn); ?><?php endif; ?><?php if ($op_ogrn) : ?> · ОГРНИП <?php echo esc_html($op_ogrn); ?><?php endif; ?></p>
        <a href="/privacy/">Политика обработки персональных данных</a>
    </div>
</footer>

<?php
if (function_exists('izex_pro_render_mobile_bar')) {
    izex_pro_render_mobile_bar();
}
?>

<?php
$ss_modal = function ($id, $title, $subtitle, $type, $button, $extra = '') {
    ?>
    <div class="modal-premium ss-modal" id="modal-<?php echo esc_attr($id); ?>">
        <div class="modal-backdrop"></div>
        <div class="modal-panel" role="dialog" aria-modal="true" aria-labelledby="modal-<?php echo esc_attr($id); ?>-title">
            <button class="modal-close-btn" aria-label="Закрыть">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
            <h2 class="ss-modal__title" id="modal-<?php echo esc_attr($id); ?>-title"><?php echo esc_html($title); ?></h2>
            <p class="ss-modal__sub"><?php echo esc_html($subtitle); ?></p>
            <p class="ss-modal__product js-order-product" hidden></p>
            <form class="modal-form-premium ss-form" data-form-type="<?php echo esc_attr($type); ?>" novalidate>
                <input type="text" name="hp_email" class="form-hp" tabindex="-1" autocomplete="off" aria-hidden="true">
                <input type="hidden" name="product_name" class="js-order-product-field" value="">
                <input type="hidden" name="product_id" class="js-order-product-id" value="">
                <div class="form-group">
                    <label class="ss-field__label" for="<?php echo esc_attr($id); ?>-phone">Телефон</label>
                    <input class="ss-input" id="<?php echo esc_attr($id); ?>-phone" type="tel" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel" inputmode="tel" required>
                    <span class="form-error" role="alert"></span>
                </div>
                <div class="form-group">
                    <label class="ss-field__label" for="<?php echo esc_attr($id); ?>-name">Имя <span class="ss-field__opt">— необязательно</span></label>
                    <input class="ss-input" id="<?php echo esc_attr($id); ?>-name" type="text" name="name" autocomplete="given-name">
                    <span class="form-error" role="alert"></span>
                </div>
                <?php echo $extra; ?>
                <?php echo ss_consent_field(); ?>
                <button type="submit" class="ss-btn ss-btn--primary ss-btn--block"><?php echo esc_html($button); ?></button>
            </form>
        </div>
    </div>
    <?php
};
$ss_comment = '<div class="form-group"><label class="ss-field__label" for="order-comment">Что важно учесть <span class="ss-field__opt">— необязательно</span></label><textarea class="ss-input" id="order-comment" name="comment" rows="3" placeholder="Населённый пункт, сколько человек живёт, сроки"></textarea></div>';
$ss_modal('order', 'Получить расчёт', 'Перезвоним, уточним условия участка и рассчитаем оборудование, монтаж и доставку.', 'raschet', 'Получить расчёт', $ss_comment);
$ss_modal('callback', 'Заказать звонок', 'Оставьте номер — перезвоним в рабочее время.', 'callback', 'Жду звонка');
$ss_modal('engineer', 'Вызвать инженера', 'Инженер приедет на участок, проверит условия и составит смету.', 'engineer', 'Вызвать инженера', '<div class="form-group"><label class="ss-field__label" for="engineer-address">Населённый пункт <span class="ss-field__opt">— необязательно</span></label><input class="ss-input" id="engineer-address" type="text" name="address"></div>');
?>

<div class="cookie-consent" id="cookieConsent" role="dialog" aria-live="polite" aria-label="Уведомление об использовании cookie" hidden>
    <div class="cookie-consent__inner">
        <?php // Текст сокращён до одной строки: на 375 px прежние четыре строки
              // занимали 239 px — треть первого экрана (задача #7). Полное описание
              // обработки данных — на странице политики. ?>
        <p class="cookie-consent__text">
            Сайт использует cookie. Продолжая, вы принимаете
            <a href="/privacy/">политику обработки данных</a>.
        </p>
        <button type="button" class="cookie-consent__btn" id="cookieConsentAccept">Принять</button>
    </div>
</div>

<!--
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
-->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=<?php echo (int) izex_metrika_id(); ?>', 'ym');

    ym(<?php echo (int) izex_metrika_id(); ?>, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/<?php echo (int) izex_metrika_id(); ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<?php
// printSocialFloat() отключён: прежний блок соцсетей висел по центру справа и
// дублировал ссылки, которые теперь выводит .pro-dock (см. izex_pro_render_dock()).
// Сами ссылки по-прежнему берутся из настроек «Соцсети» — ничего не потеряно.
?>
<?php wp_footer(); ?>
</body>
</html>