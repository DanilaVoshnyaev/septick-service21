<?php
/**
 * Шаблон страницы «Контакты» — премиум-стиль, данные из Carbon Fields.
 *
 * @package izex
 */

get_header();

$company   = getCompanyContacts();
$phones    = getCarbonPhones();
$email     = getCarbonEmail();
$work_time = getCarbonFields('work_time');
$address   = getCarbonAddress();
$map_link  = getCarbonAddressLink();
?>

    <main id="primary" class="site-main contacts-page">
        <div class="container">

            <header class="contacts-page__header">
                <h1 class="contacts-page__title"><?php echo esc_html(get_the_title()); ?></h1>
                <p class="contacts-page__subtitle">Свяжитесь с нами удобным способом — ответим на все вопросы и поможем с выбором.</p>
            </header>

            <div class="contacts-grid">

                <!-- Телефоны -->
                <?php if (!empty($phones)) : ?>
                    <div class="contact-card">
                        <div class="contact-card__icon">
                            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </div>
                        <h2 class="contact-card__title">Телефоны</h2>
                        <div class="contact-card__body">
                            <?php foreach ($phones as $group) : ?>
                                <div class="contact-phone-group">
                                    <?php if (!empty($group['phone_caption'])) : ?>
                                        <span class="contact-phone-group__caption"><?php echo esc_html($group['phone_caption']); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($group['phone_numbers'])) : ?>
                                        <?php foreach ($group['phone_numbers'] as $number) : ?>
                                            <a class="contact-phone-group__number" href="tel:<?php echo esc_attr($number['clear_number']); ?>"><?php echo esc_html($number['phone_number']); ?></a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Email -->
                <?php if (!empty($email)) : ?>
                    <div class="contact-card">
                        <div class="contact-card__icon">
                            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22 6-10 7L2 6"/>
                            </svg>
                        </div>
                        <h2 class="contact-card__title">Email</h2>
                        <div class="contact-card__body">
                            <a class="contact-card__link" href="mailto:<?php echo esc_attr(antispambot($email)); ?>"><?php echo esc_html(antispambot($email)); ?></a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                // Адрес офиса или склада берём из настроек «Доверие и гарантия»:
                // это отдельное поле от юридического адреса в реквизитах (задача #20).
                $trust = izex_trust();
                $office = $trust['office_address'] ?: $address;
                $no_office = $trust['office_none'];
                ?>

                <!-- Адрес и режим работы -->
                <?php if (!empty($office) || !empty($work_time) || $no_office) : ?>
                    <div class="contact-card">
                        <div class="contact-card__icon">
                            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <h2 class="contact-card__title">
                            <?php echo $no_office && !$office ? 'Как мы работаем' : 'Адрес и режим работы'; ?>
                        </h2>
                        <div class="contact-card__body">
                            <?php if (!empty($office)) : ?>
                                <p class="contact-card__text"><?php echo nl2br(esc_html($office)); ?></p>
                            <?php elseif ($no_office) : ?>
                                <p class="contact-card__text">
                                    Офиса для приёма нет: работаем выездом по Чувашии
                                    и соседним регионам Поволжья.
                                </p>
                            <?php endif; ?>
                            <?php if (!empty($work_time)) : ?>
                                <p class="contact-card__text contact-card__text--muted"><?php echo esc_html($work_time); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($map_link)) : ?>
                                <a class="contact-card__link" href="<?php echo esc_url($map_link); ?>" target="_blank" rel="noopener">Открыть на карте</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Дополнительный текст из редактора страницы -->
            <?php
            while (have_posts()) :
                the_post();
                if (trim(get_the_content()) !== '') :
                    ?>
                    <div class="contacts-page__content"><?php the_content(); ?></div>
                <?php
                endif;
            endwhile;
            ?>

            <!-- Карта -->
            <?php if (!empty($map_link)) : ?>
                <div class="contacts-map">
                    <iframe src="<?php echo esc_url($map_link); ?>" width="100%" height="420" frameborder="0" allowfullscreen="true" loading="lazy" title="Карта проезда"></iframe>
                </div>
            <?php endif; ?>

        </div>
    </main><!-- #primary -->

<?php
get_footer();
