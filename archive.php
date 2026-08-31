<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package izex
 */

get_header();


// Continue with the default archive template if 'staty' category does not exist
?>

    <main id="primary" class="site-main">
        <div class="inner">
            <?php
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            echo '<div class="archive__block">';

            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    // Include the Post-Type-specific template for the content.
                    get_template_part('template-parts/content', get_post_type());
                endwhile;
                the_posts_navigation();
            else :
                get_template_part('template-parts/content', 'none');
            endif;

            echo '</div>';
            ?>
        </div>
    </main><!-- #main -->

<?php
//get_sidebar();
get_footer();
?>