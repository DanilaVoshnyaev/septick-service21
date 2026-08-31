<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package izex
 */

get_header();
$id = get_the_ID();
$img = !empty(get_the_post_thumbnail_url()) ? get_the_post_thumbnail_url() : get_template_directory_uri() . '/assets/images/no-photo.png';
$date = get_the_date('', $id);
?>

    <main id="primary" class="site-main">
        <div class="inner staty">
            <?php
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 10,
                'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
                'category_name' => 'staty', // Используйте slug (часть URL) рубрики "staty"
            );

            $articles_query = new WP_Query($args);

            if ($articles_query->have_posts()) :
                while ($articles_query->have_posts()) :
                    $articles_query->the_post();
                    ?>
                    <article class="articles__item" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <a class="articles_item_title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <div class="entry-articles_item_text">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="articles_item_more">Подробнее</a>
                    </article>
                <?php
                endwhile;

                // Вывод пагинации
                echo '<div class="pagination" style="margin-bottom: 20px;">';
                echo paginate_links(array(
                    'total' => $articles_query->max_num_pages,
                    'current' => max(1, get_query_var('paged')),
                    'show_all' => true,
                    'prev_next' => false,
                    'end_size' => 1,
                    'before_page_number' => '<span class="pagination_number">',
                    'after_page_number' => '</span>',
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
