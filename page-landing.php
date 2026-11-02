<?php
/**
 * Посадочная страница под поисковый запрос: H1 и анонс, текст из редактора
 * (вместе с шорткодами блоков темы) и заявка внизу.
 * Template Name: Посадочная страница
 */

get_header();
$company = getCompanyContacts();

while (have_posts()) : the_post(); ?>

    <main class="landing-page">
        <div class="container">

            <section class="landing-hero">
                <h1 class="landing-hero__title"><?php the_title(); ?></h1>
                <?php if (has_excerpt()) : ?>
                    <p class="landing-hero__lead"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
                <div class="landing-hero__actions">
                    <button type="button" class="btn-premium btn-gold open-modal" data-modal="engineer">Вызвать инженера бесплатно</button>
                    <a href="tel:<?php echo esc_attr($company['phone_clean']); ?>" class="landing-hero__phone"><?php echo esc_html($company['phone']); ?></a>
                </div>
            </section>

            <div class="landing-content">
                <?php the_content(); ?>
            </div>

            <section class="cta-bottom">
                <h2>Подберём станцию и посчитаем смету бесплатно</h2>
                <p>Оставьте заявку — и вот что мы сделаем:</p>
                <ul class="cta-bottom__steps">
                    <li>Перезвоним в течение 15 минут</li>
                    <li>Инженер бесплатно приедет на участок и подберёт модель</li>
                    <li>Зафиксируем стоимость в договоре до начала работ</li>
                </ul>
                <div class="cta-bottom__actions">
                    <button type="button" class="btn-call open-modal" data-modal="order" data-product="<?php the_title_attribute(); ?>">Заказать звонок</button>
                    <a href="tel:<?php echo esc_attr($company['phone_clean']); ?>" class="cta-bottom__phone">Позвонить: <?php echo esc_html($company['phone']); ?></a>
                </div>
                <p class="cta-note">Бесплатно и без обязательств • Конфиденциально • Без спама</p>
            </section>

        </div>
    </main>

<?php endwhile;
get_footer();
