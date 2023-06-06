<?php
// Запрос на получение записей из категории "Новости"
$args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,  // -1 для вывода всех записей
    'category_name' => 'news',  // Используйте slug категории
);

$news_query = new WP_Query($args);

if ($news_query->have_posts()) :
    while ($news_query->have_posts()) : $news_query->the_post();
        ?>
        <article <?php post_class(); ?>>
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="entry-content">
                <?php the_excerpt(); ?>
            </div>
        </article>
    <?php
    endwhile;
else :
    echo '<p>' . __('No posts found.', 'your-text-domain') . '</p>';
endif;

// Сбрасываем данные запроса
wp_reset_postdata();
?>