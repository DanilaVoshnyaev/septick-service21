<?php
/**
 * Template Name: Страница услуг
 * Template Post Type: page
 */

get_header(); ?>

    <main id="primary" class="site-content">

        <section class="page-hero">
            <div class="container">
                <h1 class="page-hero__title"><?php the_title(); ?></h1>
                <p class="page-hero__subtitle">
                    Комплексные решения для автономного водоснабжения и канализации
                </p>
            </div>
        </section>

        <section class="page-content-section">
            <div class="container">
                <div class="wp-block-post-content">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        the_content();
                    endwhile;
                    ?>
                </div>
            </div>
        </section>

        <section class="cta-bottom">
            <div class="container">
                <div class="cta-box">
                    <div class="cta-box__info">
                        <h2>Нужна помощь в выборе?</h2>
                        <p>Наши инженеры проконсультируют вас бесплатно</p>
                    </div>
                    <div class="cta-box__action">
                        <a href="tel:+74951234567" class="btn btn--primary">+7 (495) 123-45-67</a>
                    </div>
                </div>
            </div>
        </section>

    </main>

<?php get_footer(); ?>