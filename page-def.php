<?php
// Получаем все записи типа post
$args = array(
'post_type' => 'post',
'post_status' => 'publish',
'posts_per_page' => -1,
);

$query = new WP_Query($args);

if ($query->have_posts()) {
while ($query->have_posts()) {
$query->the_post();

// Получаем данные записи типа post
$post_title = get_the_title();
$post_content = get_the_content();
// И другие поля, которые вам необходимо скопировать

// Создаем новую запись типа staty
$staty_post_data = array(
'post_title' => $post_title,
'post_content' => $post_content,
'post_type' => 'staty',
'post_status' => 'publish',
'slug'=>get_the_permalink()
// Добавьте другие поля, если необходимо
);

$staty_post_id = wp_insert_post($staty_post_data);

// Если вам необходимо скопировать мета-данные, сделайте это здесь
// Например: $meta_value = get_post_meta(get_the_ID(), 'meta_key', true);
// update_post_meta($staty_post_id, 'meta_key', $meta_value);
}
}

// Сбрасываем запрос
wp_reset_postdata();
