<?php
/*/**
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

//get_header();
?><!--

    <main id="primary" class="site-main">
        <div class="inner">
            <?php
/*            $sitemap_category = get_term_by('slug', 'sitemap', 'category');
            $sitemap_category_id = $sitemap_category->term_id;
            $current_sitemap_link = get_permalink();
            $child_categories = get_categories(array('parent' => $sitemap_category_id));

            foreach ($child_categories as $child_category) {
                if ($child_category->slug === 'info') {
                    // Получение статей из текущей дочерней рубрики info
                    $args_info = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'category__in' => array($child_category->term_id),
                    );
                    $custom_query_info = new WP_Query($args_info);
                    $category_link_info = get_category_link($child_category->term_id);
                    if ($custom_query_info->have_posts()) {
                        echo '<li><a href="' . esc_url($category_link_info) . '">' . $child_category->name . '</a></li>';
                        echo '<ul>';
                        while ($custom_query_info->have_posts()) {
                            $custom_query_info->the_post();
                            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                        }
                        echo '</ul>';
                    }

                    wp_reset_postdata();
                    break;
                }
            }*/?>
        </div>
    </main>< #main -->

--><?//php
/*//get_sidebar();
get_footer();*/
