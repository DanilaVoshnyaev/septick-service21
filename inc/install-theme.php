<?php
//// Добавление категорий при установке шаблона
//function create_custom_categories()
//{
//    $categories = array(
//        array(
//            'name' => 'Новости',
//            'slug' => 'news'
//        ),
//        array(
//            'name' => 'Вакансии',
//            'slug' => 'vacancy
//'
//        ),
//        array(
//            'name' => 'Услуги',
//            'slug' => 'services
//'
//        ),
//        // Добавьте любое количество нужных вам рубрик в формате array( 'name' => 'Название', 'slug' => 'слаг' )
//    );
//
//    foreach ($categories as $category) {
//        $cat_exists = get_term_by('slug', $category['slug'], 'category');
//
//        if (!$cat_exists) {
//            wp_insert_term($category['name'], 'category', array(
//                'slug' => $category['slug']
//            ));
//        }
//    }
//}
//add_action('after_switch_theme', 'create_custom_categories');

////Установка шаблона по умолчанию
//function set_default_category()
//{
//    $default_category = 'news'; // Замените 'news' на слаг рубрики, которую вы хотите установить по умолчанию
//
//    $default_category_id = get_term_by('slug', $default_category, 'category');
//
//    if ($default_category_id) {
//        update_option('default_category', $default_category_id->term_id);
//    }
//}
//
//add_action('after_switch_theme', 'set_default_category');


function artabr_remove_archives_from_title($title)
{
    if (is_post_type_archive('news')) {
        $title['title'] = str_replace('Архив ', '', $title['title']);
    }
    return $title;
}

function artabr_remove_yoast_archives_prefix()
{
    if (is_post_type_archive('news')) {
        add_filter('wpseo_title', '__return_empty_string');
    }
}

add_filter('get_the_archive_title', 'artabr_remove_archives_from_title');
add_action('wpseo_head', 'artabr_remove_yoast_archives_prefix');


add_filter('wpcf7_form_elements', function ($content) {
    $content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);
    return $content;
});

function rankya_remove_global_styles()
{
    remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
    remove_action('in_admin_header', 'wp_global_styles_render_svg_filters');
}

add_action('after_setup_theme', 'rankya_remove_global_styles', 10, 0);


add_filter('upload_mimes', 'new_upload_allow');

# Добавляет SVG в список разрешенных для загрузки файлов.
function new_upload_allow($mimes)
{
    // $mimes['svg']  = 'image/svg+xml';
    return $mimes;
}
//модификация сепаратора YOAST SEO
function filter_wpseo_breadcrumb_separator($this_options_breadcrumbs_sep)
{
    return '<span class="separator">></span>';
}
add_filter('wpseo_breadcrumb_separator', 'filter_wpseo_breadcrumb_separator', 10, 1);

//Модификация короткого описания
function lt_html_excerpt($text)
{
    global $post;
    if ('' == $text) {
        $text = get_the_content('');
        $text = apply_filters('the_content', $text);
        $text = str_replace('\]\]\>', ']]>', $text);
        $text = strip_tags($text, '<p><br><b><a><em><strong><ul><li><br>');
        $excerpt_length = 40;

        $words = explode(' ', $text, $excerpt_length + 1);
        if (count($words) > $excerpt_length) {
            array_pop($words);
            array_push($words, '...');
            $text = implode(' ', $words);
        }
    }
    return $text;
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'lt_html_excerpt');

//Создания кастомных типов записей
function create_custom_post_types()
{
    // Post type для новостей
    $labels_news = array(
        'name' => 'Статьи',
        'singular_name' => 'Статья',
        'menu_name' => 'Статьи',
        'add_new' => 'Добавить новую',
        'add_new_item' => 'Добавить новую сатью',
        'edit' => 'Редактировать',
        'edit_item' => 'Редактировать статью',
        'new_item' => 'Новая статья',
        'view' => 'Просмотреть',
        'view_item' => 'Просмотреть статью',
        'search_items' => 'Искать статью',
        'not_found' => 'Статьи не найдены',
        'not_found_in_trash' => 'Статьи в корзине не найдены',
        'parent' => 'Родительская статья'
    );

    $args_news = array(
        'labels' => $labels_news,
        'public' => true,
        'has_archive' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => array('title', 'editor', 'thumbnail', 'author', 'excerpt', 'comments', 'revisions'),
        'show_in_rest' => true,
        'taxonomies' => array('category','post_tag'),
        'rewrite' => array('slug' => 'staty'),
    );

    register_post_type('staty', $args_news);


//    // Post type для вакансий
//    $labels_vacancies = array(
//        'name' => 'Вакансии',
//        'singular_name' => 'Вакансия',
//        'menu_name' => 'Вакансии',
//        'add_new' => 'Добавить новую',
//        'add_new_item' => 'Добавить новую вакансию',
//        'edit' => 'Редактировать',
//        'edit_item' => 'Редактировать вакансию',
//        'new_item' => 'Новая вакансия',
//        'view' => 'Просмотреть',
//        'view_item' => 'Просмотреть вакансию',
//        'search_items' => 'Искать вакансии',
//        'not_found' => 'Вакансии не найдены',
//        'not_found_in_trash' => 'Вакансии в корзине не найдены',
//        'parent' => 'Родительская вакансия'
//    );
//
//    $args_vacancies = array(
//        'labels' => $labels_vacancies,
//        'public' => true,
//        'has_archive' => true,
//        'menu_position' => 6,
//        'menu_icon' => 'dashicons-businessman',
//        'supports' => array('title', 'editor'),
//        'show_in_rest' => true,
//        'rewrite' => array('slug' => 'vacancies'),
//    );
//
//    register_post_type('vacancies', $args_vacancies);


//    // Post type для услуг
//    $labels_services = array(
//        'name' => 'Услуги',
//        'singular_name' => 'Услуга',
//        'menu_name' => 'Услуги',
//        'add_new' => 'Добавить новую',
//        'add_new_item' => 'Добавить новую услугу',
//        'edit' => 'Редактировать',
//        'edit_item' => 'Редактировать услугу',
//        'new_item' => 'Новая услуга',
//        'view' => 'Просмотреть',
//        'view_item' => 'Просмотреть услугу',
//        'search_items' => 'Искать услуги',
//        'not_found' => 'Услуги не найдены',
//        'not_found_in_trash' => 'Услуги в корзине не найдены',
//        'parent' => 'Родительская услуга'
//    );
//
//    $args_services = array(
//        'labels' => $labels_services,
//        'public' => true,
//        'has_archive' => true,
//        'menu_position' => 7,
//        'menu_icon' => 'dashicons-admin-tools',
//        'supports' => array('title', 'editor', 'thumbnail', 'author', 'excerpt'),
//        'show_in_rest' => true,
//        'taxonomies' => array('post_tag'),
//        'rewrite' => array('slug' => 'services'),
//    );
//
//    register_post_type('services', $args_services);

    $labels_slider = array(
        'name'                  => 'Слайдер',
        'singular_name'         => 'Слайд',
        'menu_name'             => 'Слайдер',
        'add_new'               => 'Добавить слайд',
        'add_new_item'          => 'Добавить новый слайд',
        'edit'                  => 'Редактировать',
        'edit_item'             => 'Редактировать слайд',
        'new_item'              => 'Новый слайд',
        'view'                  => 'Просмотреть',
        'view_item'             => 'Просмотреть слайд',
        'search_items'          => 'Искать слайды',
        'not_found'             => 'Слайды не найдены',
        'not_found_in_trash'    => 'Слайды в корзине не найдены',
        'parent'                => 'Родительский слайд'
    );
    $args_slider = array(
        'labels'                => $labels_slider,
        'public'                => true,
        'has_archive'           => false,
        'menu_position'         => 8,
        'menu_icon'             => 'dashicons-images-alt2',
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest'          => true,
        'rewrite'               => array( 'slug' => 'slider' ),
    );
    register_post_type('slider', $args_slider);
}

add_action('init', 'create_custom_post_types');

//Удалиние признака категории из заголовка
function artabr_remove_name_cat($title)
{
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_post_type_archive('news') || is_post_type_archive('services') || is_post_type_archive('vacancies')) {
        $title = str_replace('Архивы:', '', $title);
    }
    return $title;
}
add_filter('get_the_archive_title', 'artabr_remove_name_cat');

////Миграция данных из обычных категорий в кастомные
//function migrate_news_posts() {
//    // Получите все записи из рубрики "Статьи" (slug: 'news') и типа записи 'post'
//    $posts = get_posts( array(
//        'category_name' => 'services', // Замените 'news' на слаг рубрики "Новости"
//        'post_type'     => 'post',
//        'posts_per_page' => -1,
//    ) );
//
//    foreach ( $posts as $post ) {
//        // Создайте новую запись типа "Новости"
//        $new_post = array(
//            'post_title'   => $post->post_title,
//            'post_content' => $post->post_content,
//            'post_status'  => $post->post_status,
//            'post_date'    => $post->post_date,
//            'post_author'  => $post->post_author,
//            'post_type'    => 'services', // Укажите тип записи "Новости"
//        );
//
//        // Вставьте новую запись
//        $new_post_id = wp_insert_post( $new_post );
//
//        // Если вставка прошла успешно, удалите оригинальную запись
//        if ( $new_post_id ) {
//            wp_delete_post( $post->ID, true );
//        }
//    }
//}
//add_action( 'init', 'migrate_news_posts' );