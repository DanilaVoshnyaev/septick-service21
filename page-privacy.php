<?php
/**
 * Шаблон страницы «Политика конфиденциальности» (152-ФЗ)
 *
 * Автоматически применяется к странице со слагом «privacy» (URL /privacy/).
 * Реквизиты оператора (ИП/ООО, ИНН, ОГРН/ОГРНИП) нужно заполнить ниже —
 * см. блоки, отмеченные {{ ... }}.
 */

get_header();

$company = function_exists('getCompanyContacts') ? getCompanyContacts() : array();
$phone   = $company['phone'] ?? '';
$email   = $company['email'] ?? '';
$address = $company['address'] ?? '';
$site    = wp_parse_url(home_url(), PHP_URL_HOST);
$updated = date_i18n('d.m.Y');

// Реквизиты оператора (из Carbon Fields → Настройки темы → Реквизиты)
$cf_option = function ($key, $fallback) {
    if (function_exists('carbon_get_theme_option')) {
        $v = carbon_get_theme_option($key);
        if ($v !== null && $v !== '') {
            return $v;
        }
    }
    return $fallback;
};

$operator_name = $cf_option('operator_name', 'ИП Белков Сергей Валерьевич ');
$operator_inn  = $cf_option('operator_inn', 'ИНН 210403597536');
$operator_ogrn = $cf_option('operator_ogrn', 'ОГРНИП 323210000047416
');
?>

    <main class="legal-page">
        <div class="container">
            <article class="legal-doc">
                <header class="legal-doc__head">
                    <h1 class="legal-doc__title">Политика конфиденциальности</h1>
                    <p class="legal-doc__meta">Редакция от <?php echo esc_html($updated); ?></p>
                </header>

                <section class="legal-section">
                    <h2>1. Общие положения</h2>
                    <p>Настоящая Политика конфиденциальности (далее — «Политика») действует в отношении всей
                        информации, которую оператор может получить о пользователе во время использования им
                        сайта <strong><?php echo esc_html($site); ?></strong> (далее — «Сайт»), его сервисов и
                        форм обратной связи.</p>
                    <p>Политика разработана в соответствии с Федеральным законом от 27.07.2006 № 152-ФЗ
                        «О персональных данных» и иными нормативными актами Российской Федерации в области
                        защиты персональных данных.</p>
                    <p>Используя Сайт и направляя свои данные через формы, пользователь выражает согласие
                        с условиями настоящей Политики. В случае несогласия пользователь должен воздержаться
                        от использования Сайта.</p>
                </section>

                <section class="legal-section">
                    <h2>2. Оператор персональных данных</h2>
                    <ul class="legal-list">
                        <li><strong>Оператор:</strong> <?php echo esc_html($operator_name); ?></li>
                        <li><strong>ИНН:</strong> <?php echo esc_html($operator_inn); ?></li>
                        <li><strong>ОГРН/ОГРНИП:</strong> <?php echo esc_html($operator_ogrn); ?></li>
                        <?php if ($address) : ?>
                            <li><strong>Адрес:</strong> <?php echo esc_html($address); ?></li>
                        <?php endif; ?>
                        <?php if ($phone) : ?>
                            <li><strong>Телефон:</strong> <?php echo esc_html($phone); ?></li>
                        <?php endif; ?>
                        <?php if ($email) : ?>
                            <li><strong>E-mail:</strong> <?php echo esc_html($email); ?></li>
                        <?php endif; ?>
                    </ul>
                </section>

                <section class="legal-section">
                    <h2>3. Основные термины</h2>
                    <p><strong>Персональные данные</strong> — любая информация, относящаяся к прямо или косвенно
                        определённому физическому лицу (субъекту персональных данных).</p>
                    <p><strong>Обработка персональных данных</strong> — любое действие (операция) с персональными
                        данными: сбор, запись, систематизация, хранение, уточнение, использование, передача,
                        обезличивание, блокирование, удаление, уничтожение.</p>
                    <p><strong>Cookie</strong> — небольшой фрагмент данных, отправляемый сайтом и хранимый на
                        устройстве пользователя.</p>
                </section>

                <section class="legal-section">
                    <h2>4. Какие данные обрабатываются</h2>
                    <p>Оператор может обрабатывать следующие данные:</p>
                    <ul class="legal-list">
                        <li>фамилия, имя (если указаны пользователем в форме);</li>
                        <li>номер телефона;</li>
                        <li>адрес электронной почты;</li>
                        <li>адрес объекта/участка (если указан);</li>
                        <li>содержание сообщения/комментария в форме;</li>
                        <li>технические данные: IP-адрес, тип и версия браузера, данные cookie, сведения о
                            действиях на Сайте, источник перехода (собираются автоматически системами
                            веб-аналитики).</li>
                    </ul>
                </section>

                <section class="legal-section">
                    <h2>5. Цели обработки</h2>
                    <ul class="legal-list">
                        <li>обработка заявок и обратная связь с пользователем (звонок, консультация, расчёт);</li>
                        <li>заключение и исполнение договоров на поставку и монтаж оборудования;</li>
                        <li>информирование о статусе заявки;</li>
                        <li>улучшение работы Сайта и качества сервиса, веб-аналитика;</li>
                        <li>исполнение требований законодательства РФ.</li>
                    </ul>
                </section>

                <section class="legal-section">
                    <h2>6. Правовые основания обработки</h2>
                    <p>Обработка персональных данных осуществляется на основании согласия субъекта персональных
                        данных, а также в случаях, предусмотренных Федеральным законом № 152-ФЗ и иными
                        федеральными законами. Согласие предоставляется пользователем при отправке любой формы
                        на Сайте.</p>
                </section>

                <section class="legal-section">
                    <h2>7. Файлы cookie и веб-аналитика</h2>
                    <p>Сайт использует файлы cookie для обеспечения работы интерфейса, запоминания настроек и
                        сбора обезличенной статистики. Для анализа посещаемости могут применяться сервисы
                        Яндекс.Метрика и/или Google Analytics.</p>
                    <p>Пользователь может отключить cookie в настройках браузера, однако это может повлиять на
                        работоспособность отдельных функций Сайта.</p>
                </section>

                <section class="legal-section">
                    <h2>8. Передача данных третьим лицам</h2>
                    <p>Оператор не передаёт персональные данные третьим лицам, за исключением случаев:</p>
                    <ul class="legal-list">
                        <li>получения согласия пользователя;</li>
                        <li>привлечения подрядчиков для исполнения заявки (доставка, монтаж) — в минимально
                            необходимом объёме;</li>
                        <li>требований уполномоченных государственных органов в соответствии с
                            законодательством РФ.</li>
                    </ul>
                </section>

                <section class="legal-section">
                    <h2>9. Сроки и место хранения</h2>
                    <p>Персональные данные хранятся не дольше, чем этого требуют цели их обработки, либо до
                        отзыва согласия пользователем. Хранение и обработка данных граждан РФ осуществляется
                        с использованием баз данных, расположенных на территории Российской Федерации
                        (242-ФЗ).</p>
                </section>

                <section class="legal-section">
                    <h2>10. Права субъекта персональных данных</h2>
                    <p>Пользователь имеет право:</p>
                    <ul class="legal-list">
                        <li>получать сведения об обработке своих персональных данных;</li>
                        <li>требовать уточнения, блокирования или уничтожения данных, если они неполны,
                            неактуальны, незаконно получены или не нужны для целей обработки;</li>
                        <li>отозвать согласие на обработку персональных данных;</li>
                        <li>обжаловать действия оператора в Роскомнадзоре или в судебном порядке.</li>
                    </ul>
                    <p>Для реализации своих прав пользователь может направить обращение по контактам,
                        указанным в разделе 2.</p>
                </section>

                <section class="legal-section">
                    <h2>11. Защита персональных данных</h2>
                    <p>Оператор принимает необходимые правовые, организационные и технические меры для защиты
                        персональных данных от неправомерного доступа, уничтожения, изменения, блокирования,
                        копирования и распространения.</p>
                </section>

                <section class="legal-section">
                    <h2>12. Изменение Политики</h2>
                    <p>Оператор вправе вносить изменения в настоящую Политику. Новая редакция вступает в силу
                        с момента её размещения на Сайте, если иное не предусмотрено новой редакцией.</p>
                </section>

                <section class="legal-section">
                    <h2>13. Контакты</h2>
                    <p>По всем вопросам, связанным с обработкой персональных данных, обращайтесь к оператору:</p>
                    <ul class="legal-list">
                        <?php if ($phone) : ?>
                            <li>Телефон: <a href="tel:<?php echo esc_attr($company['phone_clean'] ?? ''); ?>"><?php echo esc_html($phone); ?></a></li>
                        <?php endif; ?>
                        <?php if ($email) : ?>
                            <li>E-mail: <a href="mailto:<?php echo antispambot($email); ?>"><?php echo antispambot($email); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </section>
            </article>
        </div>
    </main>

    <style>
        .legal-page { padding: 2.5rem 0 4rem; }
        .legal-doc { max-width: 1000px; margin: 0 auto; color: var(--text, #1e293b); }
        .legal-doc__head { margin-bottom: 2rem; }
        .legal-doc__title { font-size: clamp(1.6rem, 4vw, 2.25rem); font-weight: 700; margin: 0 0 .5rem; }
        .legal-doc__meta { color: var(--text-muted, #64748b); font-size: .9rem; margin: 0; }
        .legal-section { margin-bottom: 1.75rem; }
        .legal-section h2 { font-size: 1.15rem; font-weight: 600; margin: 0 0 .6rem; color: var(--text, #1e293b); }
        .legal-section p { line-height: 1.7; margin: 0 0 .75rem; }
        .legal-list { margin: 0 0 .75rem; padding-left: 1.25rem; line-height: 1.7; }
        .legal-list li { margin-bottom: .35rem; }
        .legal-doc a { color: var(--brand-ink, #157f1c); text-decoration: underline; }
    </style>

<?php get_footer(); ?>
