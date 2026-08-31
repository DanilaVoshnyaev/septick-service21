<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package izex
 */

get_header();
$post_id = get_the_ID();
$post_type = get_post_type($post_id);
?>
    <main id="primary" class="site-main">
        <div class="inner <?= $post_type ?>">
            <?php get_post_type() ?>
            <?php if (have_posts()) : ?>
            <?php
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
            <div class="<?= $post_type ?>__block">
                <?php
                /* Start the Loop */
                while (have_posts()) :
                    the_post();
                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part('template-parts/content', get_post_type());
                endwhile;
                the_posts_navigation();
                else :
                    get_template_part('template-parts/content', 'none');
                endif;
                ?>
            </div>
        </div>
    </main><!-- #main -->


    <div class="form-content form-content__wrapper" data-fomr="vacancies">
        <?=do_shortcode('[contact-form-7 id="370" title="Отклик на вакансию"]')?>
    </div>

<?php
//get_sidebar();
get_footer();
