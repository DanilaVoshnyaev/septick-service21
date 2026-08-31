<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package izex
 */

get_header();
?>

    <main id="primary" class="site-main">
        <div class="inner">

            <?php if (have_posts()) : ?>
                <h1 class="page-title">
                    <?php
                    /* translators: %s: search query. */
                    printf(esc_html__('Результаты поиска: %s', 'izex'), '<span>' . get_search_query() . '</span>');
                    ?>
                </h1>
                <?php
                /* Start the Loop */
                while (have_posts()) :
                    the_post();

                    /**
                     * Run the loop for the search to output the results.
                     * If you want to overload this in a child theme then include a file
                     * called content-search.php and that will be used instead.
                     */
                    get_template_part('template-parts/content', 'search');

                endwhile;

                the_posts_navigation();

            else :
                get_template_part('template-parts/content', 'none');
            endif;
            ?>
        </div>
    </main><!-- #main -->

<?php
get_footer();
