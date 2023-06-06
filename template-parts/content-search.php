<?php
/**
 * Template Name: Страница со статьями из рубрики "staty"
 * Description: Шаблон страницы для отображения всех статей из рубрики "staty"
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
            'category_name' => 'staty', // Замените 'staty' на актуальный слаг вашей рубрики
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
                    <a href="<?php the_permalink();?>" class="articles_item_more">Подробнее</a>
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
</main>
<style>
    article {
        box-shadow: 0px 4px 25px rgba(114, 115, 119, 0.1);
        margin-bottom: 30px;
        padding: 25px;
        transition: .2s all;
        border: 2px solid transparent;
        position: relative;
    }
    article:hover {
        border-color: #ff6600;
        transition: .2s all;
    }
    .articles__title {
        font-width: bold;
        text-decoration: none;
        color: inherit;
    }
</style>

<?php
get_footer();
?>
