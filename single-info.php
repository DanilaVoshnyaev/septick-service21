<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package izex
 */

get_header();
?>
    <main id="primary" class="site-main">
        <div class="inner">

            <?php
            while (have_posts()) :
                the_post();

                get_template_part('template-parts/content', get_post_type());

            endwhile; // End of the loop.
            ?>
        </div>
    </main><!-- #main -->
    <div class="form-content form-content__wrapper" data-form="vacancies">
        <?= do_shortcode('[contact-form-7 id="370" title="Отклик на вакансию"]') ?>
    </div>
<?php
//get_sidebar();
get_footer();
