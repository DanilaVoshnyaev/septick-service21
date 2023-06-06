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

// Получение ID рубрики "sitemap"


?>
<main id="primary" class="site-main">
    <div class="inner post-navigation">

        <?php
        $sitemap_category = get_term_by('slug', 'sitemap', 'category');
        $sitemap_category_id = $sitemap_category->term_id;
        $current_sitemap_link = get_permalink();

        //  массив для хранения ссылок на страницы главной, sitemap и страницу 404
        $additional_pages = array(
            'Главная' => get_home_url(),
            'Карта сайта' => $current_sitemap_link,
            '404' => get_permalink(get_option('page_not_found')),
        );

        // Определите порядок рубрик staty, production, info
        $custom_category_order = array('staty', 'production', 'info');

        // Получение всех дочерних рубрик "sitemap"
        $child_categories = get_categories(array('parent' => $sitemap_category_id));

        // Сортировка дочерних рубрик в соответствии с порядком staty, production, info
        usort($child_categories, function ($a, $b) use ($custom_category_order) {
            $a_index = array_search($a->slug, $custom_category_order);
            $b_index = array_search($b->slug, $custom_category_order);
            return $a_index - $b_index;
        });

        if (!empty($child_categories) || !empty($additional_pages)) {
            echo '<ul>';

            // Вывод ссылок на страницы главной, sitemap и страницу 404
            foreach ($additional_pages as $page_title => $page_link) {
                echo '<li><a href="' . esc_url($page_link) . '">' . $page_title . '</a></li>';
            }

            // Вывод рубрики staty и ее записей
            foreach ($child_categories as $child_category) {
                if ($child_category->slug === 'staty') {
                    // Получение статей из текущей дочерней рубрики staty
                    $args_staty = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'category__in' => array($child_category->term_id),
                    );

                    $custom_query_staty = new WP_Query($args_staty);
                    $category_link_staty = get_category_link($child_category->term_id);

                    if ($custom_query_staty->have_posts()) {
                        echo '<li><a href="' . esc_url($category_link_staty) . '">' . $child_category->name . '</a></li>';
                        echo '<ul>';
                        while ($custom_query_staty->have_posts()) {
                            $custom_query_staty->the_post();
                            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                        }
                        echo '</ul>';
                    }

                    wp_reset_postdata();
                    break;
                }
            }

            // Вывод рубрики production и ее записей


            foreach ($child_categories as $child_category) {
                if ($child_category->slug === 'produkciy') {
                    // Получение статей из текущей дочерней рубрики production
                    $args_production = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'category__in' => array($child_category->term_id),
                    );

                    $custom_query_production = new WP_Query($args_production);
                    $category_link_production = get_category_link($child_category->term_id);

                    if ($custom_query_production->have_posts()) {
                        echo '<li><a href="' . esc_url($category_link_production) . '">' . $child_category->name . '</a></li>';
                        echo '<ul>';
                        while ($custom_query_production->have_posts()) {
                            $custom_query_production->the_post();
                            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                        }
                        echo '</ul>';
                    }

                    wp_reset_postdata();
                    break;
                }
            }

            // Вывод ссылок на страницы "О компании", "Доставка и оплата" и "Контакты"
            echo '<li><a href="' . get_permalink(24) . '">О компании</a></li>';
            echo '<li><a href="' . get_permalink(20) . '">Доставка и оплата</a></li>';
            echo '<li><a href="' . get_permalink(18) . '">Контакты</a></li>';

            // Вывод рубрики info и ее записей
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
            }

            echo '</ul>';
        }
        ?>


    </div>
</main>
<?php
get_footer();
?>
