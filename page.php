<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package izex
 */

get_header();
?>

    <main id="primary" class="site-main">
        <div class="inner">

            <?php
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 10,
                'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
            );

            $articles_query = new WP_Query($args);

            if ($articles_query->have_posts()) :
                while ($articles_query->have_posts()) :
                    $articles_query->the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="entry-content">
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php
                endwhile;

                // Вывод пагинации
                echo '<div class="pagination">';
                echo paginate_links(array(
                    'total' => $articles_query->max_num_pages,
                    'current' => max(1, get_query_var('paged')),
                ));
                echo '</div>';

                wp_reset_postdata();
            else :
                echo '<p>Статей не найдено.</p>';
            endif;
            ?>
        </div>
    </main><!-- #main -->

<?php
//get_sidebar();
get_footer();
