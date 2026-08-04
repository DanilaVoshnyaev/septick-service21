<?php
/**
 * izex functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package izex
 */

if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function izex_setup()
{
    /*
        * Make theme available for translation.
        * Translations can be filed in the /languages/ directory.
        * If you're building a theme based on izex, use a find and replace
        * to change 'izex' to the name of your theme in all the template files.
        */
    load_theme_textdomain('izex', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
        * Let WordPress manage the document title.
        * By adding theme support, we declare that this theme does not use a
        * hard-coded <title> tag in the document head, and expect WordPress to
        * provide it for us.
        */
    add_theme_support('title-tag');

    /*
        * Enable support for Post Thumbnails on posts and pages.
        *
        * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        */
    add_theme_support('post-thumbnails');

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(
        array(
            'menu-1' => esc_html__('Primary', 'izex'),
            'footer-1' => esc_html__('Footer', 'izex'),
            'catalog-1' => esc_html__('Catalog', 'izex'),
        )
    );

    /*
        * Switch default core markup for search form, comment form, and comments
        * to output valid HTML5.
        */
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    // Set up the WordPress core custom background feature.
    add_theme_support(
        'custom-background',
        apply_filters(
            'izex_custom_background_args',
            array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support(
        'custom-logo',
        array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        )
    );
}

add_action('after_setup_theme', 'izex_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function izex_content_width()
{
    $GLOBALS['content_width'] = apply_filters('izex_content_width', 640);
}

add_action('after_setup_theme', 'izex_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function izex_widgets_init()
{
    register_sidebar(
        array(
            'name' => esc_html__('Sidebar', 'izex'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here.', 'izex'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );
}

add_action('widgets_init', 'izex_widgets_init');

/**
 * Запоминаем имя выбранного шаблона страницы, чтобы подключать
 * постраничные стили в izex_scripts() (template_include срабатывает
 * до get_header()/wp_head(), где запускается wp_enqueue_scripts).
 */
function izex_capture_template($template)
{
    $GLOBALS['izex_current_template'] = basename($template);
    return $template;
}

add_filter('template_include', 'izex_capture_template', 99);

/**
 * Подключение стиля темы с автоматической версией по mtime.
 */
function izex_enqueue_theme_style($handle, $rel_path, $deps = array('global-style'))
{
    $abs = get_template_directory() . $rel_path;
    if (!file_exists($abs)) {
        return;
    }
    wp_enqueue_style($handle, get_template_directory_uri() . $rel_path, $deps, filemtime($abs));
}

/**
 * Enqueue scripts and styles.
 */
function izex_scripts()
{
//    wp_enqueue_style('izex-style', get_stylesheet_uri(), array(), _S_VERSION);
//    wp_style_add_data('izex-style', 'rtl', 'replace');
    wp_enqueue_style('Montserrat-font', get_template_directory_uri() . '/assets/fonts/montserrat.css');
    wp_enqueue_style('normalize', get_template_directory_uri() . '/assets/styles/normalize.css');
    wp_enqueue_style('swiper-css', get_template_directory_uri() . '/assets/styles/swiper-bundle.min.css');
    // enqueue_versioned_style('global-style', '/assets/styles/styles.min.css');
    wp_enqueue_style('global-style', get_template_directory_uri() . '/assets/styles/styles.css');
    wp_enqueue_style('staty-style', get_template_directory_uri() . '/assets/styles/page-staty.css');
    wp_enqueue_style('mobile-style', get_template_directory_uri() . '/assets/styles/mobile.css');
    wp_enqueue_style('sidebar-style', get_template_directory_uri() . '/assets/styles/sidebar.css');
    wp_enqueue_style('catalog-style', get_template_directory_uri() . '/assets/styles/catalog.css', array('global-style'), filemtime(get_template_directory() . '/assets/styles/catalog.css'));
    wp_enqueue_style('premium-components', get_template_directory_uri() . '/assets/styles/premium-components.css', array('global-style'), filemtime(get_template_directory() . '/assets/styles/premium-components.css'));

    wp_deregister_script('jquery');
    wp_register_script('jquery', get_template_directory_uri() . '/assets/js/jquery-3.7.0.min.js');
    wp_enqueue_script('jquery');
    wp_enqueue_script('swiper', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', array('jquery'), '', true);
    wp_enqueue_script('ya-map', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=b14c454d-b28c-418a-8f62-e7f2244905fc&amp;ver=6.2.2', array('jquery'), '', true);
    //wp_enqueue_script('global',get_template_direcory_uri(). '/assets(/js/globaljs)',array('jquery'),'',true);
    //enqueue_versioned_script('global-scripts', '/assets/js/scripts.js');
    wp_enqueue_script('global-scripts', get_template_directory_uri() . '/assets/js/global.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/global.js'), true);
    wp_enqueue_script('premium-ui', get_template_directory_uri() . '/assets/js/premium-ui.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/premium-ui.js'), true);
    wp_enqueue_script('station-single', get_template_directory_uri() . '/assets/js/station-single.js', array(), filemtime(get_template_directory() . '/assets/js/station-single.js'), true);
    wp_enqueue_script('station-1-data', get_template_directory_uri() . '/assets/js/1-id.js', array('jquery'), '', true);
    wp_enqueue_script('station-112-data', get_template_directory_uri() . '/assets/js/112-id.js', array('jquery'), '', true);
    //wp_enqueue_script('izex-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

    // ===== Калькулятор подбора (4.1) =====
    izex_enqueue_theme_style('calculator', '/assets/styles/calculator.css');
    wp_enqueue_script('topas-calculator', get_template_directory_uri() . '/assets/js/calculator.js', array(), filemtime(get_template_directory() . '/assets/js/calculator.js'), true);
    wp_localize_script('topas-calculator', 'topasCalc', array(
        'stations' => izex_get_calculator_stations(),
        'settings' => izex_get_calculator_settings(),
    ));

    // ===== Сравнение моделей (4.2) =====
    izex_enqueue_theme_style('compare', '/assets/styles/compare.css');
    wp_enqueue_script('topas-compare', get_template_directory_uri() . '/assets/js/compare.js', array(), filemtime(get_template_directory() . '/assets/js/compare.js'), true);
    wp_localize_script('topas-compare', 'topasCompare', array(
        'stations' => izex_get_compare_stations(),
    ));

    // ===== Постраничные стили (вынесены из <style> в шаблонах) =====
    $template = $GLOBALS['izex_current_template'] ?? '';

    if (is_front_page()) {
        izex_enqueue_theme_style('page-front', '/assets/styles/front-page.css');
    }
    if ($template === 'about-page.php') {
        izex_enqueue_theme_style('page-about', '/assets/styles/about-page.css');
    }
    if (is_post_type_archive('reviews') || $template === 'archive-reviews.php') {
        izex_enqueue_theme_style('page-reviews', '/assets/styles/archive-reviews.css');
    }
    if ($template === 'page-contacts.php') {
        izex_enqueue_theme_style('page-contacts', '/assets/styles/contacts.css');
    }
    if ($template === 'single-s1.php' || $template === 'content-search.php') {
        izex_enqueue_theme_style('articles-list', '/assets/styles/articles-list.css');
    }
    if (is_single(4108)) {
        izex_enqueue_theme_style('legacy-product', '/assets/styles/legacy-product.css');
    }
    // Галерея работ (4.3): архив, страница работы и блок на главной.
    if (is_post_type_archive('works') || is_singular('works') || is_front_page()) {
        izex_enqueue_theme_style('works', '/assets/styles/works.css');
    }
    // Страница «Цены» (4.4).
    if ($template === 'page-prices.php') {
        izex_enqueue_theme_style('prices', '/assets/styles/prices.css');
    }

//    if (is_singular() && comments_open() && get_option('thread_comments')) {
//        wp_enqueue_script('comment-reply');
//    }
}


add_action('wp_enqueue_scripts', 'izex_scripts');

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
    require get_template_directory() . '/inc/woocommerce.php';
   add_theme_support('woocommerce');

}

/**
 * Custom function.
 */
require get_template_directory() . '/inc/custom-functions.php';

/**
 * required plugins.
 */
require get_template_directory() . '/inc/required-plugins.php';

/**
 * carbon fields.
 */
require get_template_directory() . '/inc/carbon-fields.php';

/**
 * carbon fields.
 */
require get_template_directory() . '/inc/install-theme.php';

/**
 * SEO: meta-теги, Open Graph, canonical, Schema.org, robots.txt.
 * При установке SEO-плагина (Yoast/Rank Math) — закомментировать строку ниже.
 */
// Отключено: используется плагин Yoast SEO (модуль темы дублировал robots.txt и мета-теги).
// if (file_exists(get_template_directory() . '/inc/seo.php')) {
//     require get_template_directory() . '/inc/seo.php';
// }

add_action('init', function () {
    remove_action('wp_head', 'wp_generator');
});
// Убираем маркер версии WordPress из RSS/Atom-лент и из ссылок на ассеты.
// Версию срезаем только у core-ассетов (ver == версия WP), чтобы не сломать
// cache-busting темы, где ver формируется через filemtime().
add_filter('the_generator', '__return_empty_string');
add_filter('style_loader_src', 'izex_remove_wp_version_query', 15);
add_filter('script_loader_src', 'izex_remove_wp_version_query', 15);
function izex_remove_wp_version_query($src)
{
    global $wp_version;
    if ($src && $wp_version && strpos($src, 'ver=' . $wp_version) !== false) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

// ===== SEO для архива услуг (P0): человекочитаемый Title + meta description =====
// Активируем только если SEO-плагин (Yoast) не управляет мета-тегами.
if (!defined('WPSEO_VERSION')) {
    add_filter('document_title_parts', 'izex_services_archive_title');
    add_action('wp_head', 'izex_services_archive_meta_description', 1);
}

function izex_services_archive_title($parts)
{
    if (is_post_type_archive('services')) {
        $parts['title'] = 'Услуги: монтаж и обслуживание септиков ТОПАС';
    }
    return $parts;
}

function izex_services_archive_meta_description()
{
    if (!is_post_type_archive('services')) {
        return;
    }
    $desc = 'Услуги по установке, монтажу «под ключ» и сервисному обслуживанию '
        . 'автономной канализации ТОПАС в Чебоксарах, Новочебоксарске и Чувашии. '
        . 'Бесплатный выезд инженера и расчёт стоимости.';
    echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
}

function my_pre_get_posts($query)
{
    if ($query->is_main_query() && $query->is_archive()) {
        $query->set('meta_query', array(
            array(
                'key' => 'sort_order',
                //   'compare' => 'EXISTS',
            ),
        ));
        $query->set('orderby', array('meta_value_num' => 'DESC', 'date' => 'DESC'));
    } else {
        return $query;
    }
}
add_action('pre_get_posts', 'my_pre_get_posts');

function custom_yoast_breadcrumb_output($output)
{
    if (is_front_page()) {
        return '';
    }
    return $output;
}
add_filter('wpseo_breadcrumb_output', 'custom_yoast_breadcrumb_output');

function crb_register_stations_cpt() {
    $labels = array(
        'name'               => 'Станции',
        'singular_name'      => 'Станция',
        'add_new'            => 'Добавить станцию',
        'add_new_item'       => 'Добавить новую станцию',
        'edit_item'          => 'Редактировать станцию',
        'view_item'          => 'Просмотреть станцию',
        'search_items'       => 'Найти станцию',
        'not_found'          => 'Станции не найдены',
        'menu_name'          => 'Каталог станций',
        'name_admin_bar'     => 'Станция',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,           // ✅ Важно!
        'publicly_queryable' => true,           // ✅ Важно!
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_admin_bar'  => true,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-admin-home',
        'has_archive'        => true,           // ✅ Важно!
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite'            => array('slug' => 'stations'), // URL: site.ru/stations/
        'show_in_rest'       => true,
    );

    register_post_type('stations', $args);
}
add_action('init', 'crb_register_stations_cpt');

/**
 * Разовая миграция: помечаем все существующие станции как «в наличии».
 * Заказчик попросил, чтобы по умолчанию все товары были в наличии.
 * После прогона админ может вручную снять галочку у отсутствующих товаров —
 * повторно миграция не запускается (флаг в опциях).
 */
function izex_migrate_stations_in_stock()
{
    if (get_option('izex_stations_instock_migrated')) {
        return;
    }
    if (!function_exists('carbon_set_post_meta')) {
        return; // Carbon ещё не загружен — попробуем на следующем заходе
    }

    $station_ids = get_posts(array(
        'post_type'      => 'stations',
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ));
    foreach ($station_ids as $station_id) {
        if (!station_is_in_stock($station_id)) {
            carbon_set_post_meta($station_id, 'crb_in_stock', true);
        }
    }

    update_option('izex_stations_instock_migrated', 1);
}
add_action('admin_init', 'izex_migrate_stations_in_stock');

// Регистрация типа записи "Услуги"
function register_services_cpt() {
    $labels = array(
        'name' => 'Услуги',
        'singular_name' => 'Услуга',
        'add_new' => 'Добавить услугу',
        'add_new_item' => 'Добавить новую услугу',
        'edit_item' => 'Редактировать услугу',
        'new_item' => 'Новая услуга',
        'view_item' => 'Просмотр услуги',
        'search_items' => 'Поиск услуг',
        'not_found' => 'Услуги не найдены',
        'not_found_in_trash' => 'В корзине не найдено',
        'menu_name' => 'Услуги',
        'name_admin_bar' => 'услугу',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'services', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 25,
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'show_in_rest' => true, // Для Gutenberg
    );

    register_post_type('services', $args);

    // Таксономия "Категории услуг"
    register_taxonomy('service_category', 'services', array(
        'labels' => array(
            'name' => 'Категории услуг',
            'singular_name' => 'Категория',
            'search_items' => 'Поиск категорий',
            'all_items' => 'Все категории',
            'edit_item' => 'Редактировать категорию',
            'add_new_item' => 'Добавить категорию',
        ),
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'services/category'),
    ));
}
add_action('init', 'register_services_cpt');


// Регистрация типа записи "Отзывы"
add_action('init', 'register_reviews_cpt');
function register_reviews_cpt()
{
    $labels = array(
        'name' => 'Отзывы',
        'singular_name' => 'Отзыв',
        'add_new' => 'Добавить отзыв',
        'add_new_item' => 'Добавить новый отзыв',
        'edit_item' => 'Редактировать отзыв',
        'new_item' => 'Новый отзыв',
        'view_item' => 'Просмотр отзыва',
        'search_items' => 'Поиск отзывов',
        'not_found' => 'Отзывы не найдены',
        'not_found_in_trash' => 'В корзине не найдено',
        'menu_name' => 'Отзывы клиентов',
        'name_admin_bar' => 'Отзыв',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'reviews', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 26,
        'menu_icon' => 'dashicons-format-quote',
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
    );

    register_post_type('reviews', $args);
}

/**
 * URL страницы «Цены»: ищем страницу с шаблоном page-prices.php.
 * Если не найдена — фолбэк на /ceny/. Используется в навигации.
 *
 * @return string
 */
function izex_prices_page_url()
{
    $pages = get_posts(array(
        'post_type'   => 'page',
        'post_status' => 'publish',
        'numberposts' => 1,
        'fields'      => 'ids',
        'meta_key'    => '_wp_page_template',
        'meta_value'  => 'page-prices.php',
    ));
    if (!empty($pages)) {
        return get_permalink($pages[0]);
    }
    return home_url('/ceny/');
}

// ===== CPT «Наши работы» (4.3) =====
add_action('init', 'register_works_cpt');
function register_works_cpt()
{
    $labels = array(
        'name' => 'Наши работы',
        'singular_name' => 'Работа',
        'add_new' => 'Добавить работу',
        'add_new_item' => 'Добавить работу',
        'edit_item' => 'Редактировать работу',
        'new_item' => 'Новая работа',
        'view_item' => 'Просмотр работы',
        'search_items' => 'Поиск работ',
        'not_found' => 'Работы не найдены',
        'not_found_in_trash' => 'В корзине не найдено',
        'menu_name' => 'Наши работы',
        'name_admin_bar' => 'Работа',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'works', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 27,
        'menu_icon' => 'dashicons-format-gallery',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    );

    register_post_type('works', $args);
}

/**
 * Одноразовый сброс правил перезаписи после появления новых типов записей
 * (CPT «works» → архив /works/). Без этого архив отдаёт 404, пока вручную
 * не сохранить «Настройки → Постоянные ссылки».
 *
 * Флаг-версия хранится в опции: чтобы форсировать повторный сброс при изменении
 * структуры URL — достаточно увеличить номер в $rewrite_version.
 */
add_action('init', 'izex_maybe_flush_rewrite_rules', 20);
function izex_maybe_flush_rewrite_rules()
{
    $rewrite_version = '3'; // ↑ увеличить при изменении slug'ов/новых CPT
    if (get_option('izex_rewrite_version') !== $rewrite_version) {
        flush_rewrite_rules(false);
        update_option('izex_rewrite_version', $rewrite_version);
    }
}

/**
 * Обработчик контактных форм — премиум-шаблоны
 * Добавь этот код в functions.php твоей темы
 */

// ===== РЕГИСТРАЦИЯ AJAX-ОБРАБОТЧИКОВ =====
add_action('wp_ajax_premium_form_submit', 'handle_premium_form_submit');
add_action('wp_ajax_nopriv_premium_form_submit', 'handle_premium_form_submit');

function handle_premium_form_submit() {
    // Проверка nonce (безопасность)
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'premium_form_nonce')) {
        wp_send_json_error(['message' => 'Ошибка безопасности'], 403);
    }

    // ===== Анти-спам (ТЗ 5.4) =====
    // 1) Honeypot: скрытое поле hp_email заполняют только боты.
    // 2) Временная ловушка: заявка быстрее 2 сек после загрузки — почти всегда бот.
    // В обоих случаях возвращаем «успех» без отправки письма, чтобы не подсказывать боту.
    $hp = isset($_POST['hp_email']) ? trim((string) $_POST['hp_email']) : '';
    $elapsed = isset($_POST['elapsed']) ? (int) $_POST['elapsed'] : 0;
    if ($hp !== '' || ($elapsed > 0 && $elapsed < 2000)) {
        wp_send_json_success(['message' => 'Спасибо! Мы свяжемся с вами в течение 15 минут.']);
    }

    // Санитизация входных данных
    $form_type = sanitize_text_field($_POST['form_type'] ?? 'callback');
    $name = sanitize_text_field($_POST['name'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $address = sanitize_text_field($_POST['address'] ?? '');
    $comment = sanitize_textarea_field($_POST['comment'] ?? '');
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $product_name = sanitize_text_field($_POST['product_name'] ?? '');

    // Валидация
    $errors = [];
    if (empty($name) || mb_strlen($name) < 2) {
        $errors[] = 'Введите корректное имя';
    }
    // Простая валидация телефона РФ
    $phone_clean = preg_replace('/[^\d+]/', '', $phone);
    if (empty($phone_clean) || strlen($phone_clean) < 11) {
        $errors[] = 'Введите корректный телефон';
    }
    // Согласие на обработку ПД (152-ФЗ). Блокируем только явный отказ,
    // чтобы устаревший кешированный JS (без поля consent) не терял заявки.
    $consent = isset($_POST['consent']) ? sanitize_text_field($_POST['consent']) : '';
    if ($consent === '0') {
        $errors[] = 'Необходимо согласие на обработку персональных данных';
    }

    if (!empty($errors)) {
        wp_send_json_error(['errors' => $errors], 400);
    }

    // ===== СБОР ДОПОЛНИТЕЛЬНОЙ ИНФОРМАЦИИ =====
    $site_name   = get_bloginfo('name');
    $email_lead  = sanitize_email($_POST['email'] ?? ''); // если форма собирает email
    $page_url    = esc_url_raw($_POST['page_url'] ?? ($_SERVER['HTTP_REFERER'] ?? ''));
    $referer     = esc_url_raw($_SERVER['HTTP_REFERER'] ?? '');
    $ip          = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? '');
    $user_agent  = sanitize_text_field($_SERVER['HTTP_USER_AGENT'] ?? '');
    $datetime    = current_time('d.m.Y H:i');
    $phone_tel   = preg_replace('/[^\d+]/', '', $phone);

    // Человекочитаемое название формы
    $form_labels = array(
        'callback'      => 'Заказать звонок',
        'engineer'      => 'Вызов инженера',
        'order'         => 'Заказ',
        'catalog_order' => 'Заказ из каталога',
        'station_order' => 'Заказ станции',
        'consultation'  => 'Консультация (с главной)',
    );
    $form_label = $form_labels[$form_type] ?? $form_type;

    // ===== ФОРМИРОВАНИЕ ПИСЬМА =====
    $subject = 'Заявка с сайта: ' . $form_label . ' — ' . $name;

    $row = function ($label, $value) {
        if ($value === '' || $value === null) {
            return '';
        }
        return "<tr>"
            . "<td style='padding:8px 12px;border-bottom:1px solid #eee;background:#f8fafc;font-weight:600;white-space:nowrap;'>{$label}</td>"
            . "<td style='padding:8px 12px;border-bottom:1px solid #eee;'>{$value}</td>"
            . "</tr>";
    };

    $message  = "<div style='font-family:Arial,sans-serif;color:#1e293b;'>";
    $message .= "<h2 style='margin:0 0 12px;'>Новая заявка с сайта «" . esc_html($site_name) . "»</h2>";
    $message .= "<table style='border-collapse:collapse;width:100%;max-width:640px;border:1px solid #eee;'>";
    $message .= $row('Тип заявки', esc_html($form_label));
    $message .= $row('Имя', esc_html($name));
    $message .= $row('Телефон', "<a href='tel:{$phone_tel}' style='color:#21b224;text-decoration:none;'>" . esc_html($phone) . "</a>");
    $message .= $row('Email', $email_lead ? "<a href='mailto:{$email_lead}'>" . esc_html($email_lead) . "</a>" : '');
    $message .= $row('Адрес', esc_html($address));
    $message .= $row('Комментарий', nl2br(esc_html($comment)));
    if ($product_id || $product_name) {
        $product_line = esc_html($product_name);
        if ($product_id) {
            $product_line .= ' <span style="color:#94a3b8;">(ID: ' . $product_id . ')</span>';
        }
        $message .= $row('Товар/Услуга', $product_line);
    }
    $message .= $row('Страница заявки', $page_url ? "<a href='" . esc_url($page_url) . "'>" . esc_html($page_url) . "</a>" : '');
    if ($referer && $referer !== $page_url) {
        $message .= $row('Источник перехода', "<a href='" . esc_url($referer) . "'>" . esc_html($referer) . "</a>");
    }
    $message .= $row('Дата и время', esc_html($datetime));
    $message .= $row('Согласие на обработку ПД', $consent === '0' ? 'Нет' : 'Да (' . esc_html($datetime) . ')');
    $message .= $row('IP-адрес', esc_html($ip));
    $message .= $row('Устройство', esc_html($user_agent));
    $message .= "</table>";
    $message .= "<p style='color:#94a3b8;font-size:12px;margin-top:16px;'>Письмо отправлено автоматически с сайта " . esc_url(home_url('/')) . "</p>";
    $message .= "</div>";

    // ===== ПОЛУЧАТЕЛИ =====
    // Адреса заказчика берём из настроек темы (поле «Email(ы) для заявок»).
    $recipients = function_exists('getLeadEmails') ? getLeadEmails() : array();
    // Резервный (служебный) адрес — чтобы заявки не потерялись, если поле не заполнено.
    $recipients[] = 'gogle20023202@mail.ru';
    $recipients[] = 'servis.septik.pro@yandex.ru';
    $recipients = array_values(array_unique(array_filter($recipients, 'is_email')));

    // ===== ЗАГОЛОВКИ =====
    $domain = preg_replace('#^www\.#', '', parse_url(home_url(), PHP_URL_HOST));
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $site_name . ' <noreply@' . $domain . '>',
    );
    // Reply-To на основной email компании (чтобы ответ уходил владельцу).
    $owner_email = function_exists('getCarbonEmail') ? getCarbonEmail() : '';
    if ($owner_email && is_email($owner_email)) {
        $headers[] = 'Reply-To: ' . $owner_email;
    }

    // ===== ОТПРАВКА ПИСЬМА =====

    $sent = wp_mail($recipients, $subject, $message, $headers);
    // ===== ДОПОЛНИТЕЛЬНО: Отправка в Telegram (опционально) =====
    // Раскомментируй и настрой, если нужно
    /*
    $telegram_token = 'YOUR_BOT_TOKEN';
    $telegram_chat_id = 'YOUR_CHAT_ID';
    $telegram_text = "📩 *Новая заявка*\n" .
                     "👤 *Имя:* $name\n" .
                     "📱 *Телефон:* $phone\n" .
                     "📄 *Тип:* $form_type";
    if (!empty($product_name)) {
        $telegram_text .= "\n🛒 *Товар:* $product_name";
    }

    wp_remote_post("https://api.telegram.org/bot$telegram_token/sendMessage", [
        'body' => [
            'chat_id' => $telegram_chat_id,
            'text' => $telegram_text,
            'parse_mode' => 'Markdown'
        ]
    ]);
    */

    // ===== ЛОГИРОВАНИЕ (опционально) =====
    // error_log("Premium Form [$form_type]: $name, $phone");
    if ($sent) {
        wp_send_json_success([
            'message' => 'Спасибо! Мы свяжемся с вами в течение 15 минут.',
            'redirect' => get_permalink($product_id) // опционально: редирект после отправки
        ]);
    } else {
        wp_send_json_error(['message' => 'Ошибка отправки. Попробуйте позвонить нам.'], 500);
    }
}

// ===== НАСТРОЙКА ОТПРАВКИ ПОЧТЫ (борьба со спамом) =====
/*add_action('phpmailer_init', 'topas_configure_phpmailer');
function topas_configure_phpmailer($phpmailer) {
    // Выравниваем конверт-отправителя (Return-Path) с адресом From —
    // без этого почтовые сервисы (mail.ru, yandex) чаще кидают письмо в спам.
    if (!empty($phpmailer->From)) {
        $phpmailer->Sender = $phpmailer->From;
    }

    // Аутентифицированная отправка через SMTP — самый надёжный способ
    // доставлять письма во «Входящие». Включается, если в wp-config.php
    // заданы константы (логин/пароль ящика заказчика или транзакционного сервиса):
    //
    //   define('TOPAS_SMTP_HOST', 'smtp.yandex.ru');
    //   define('TOPAS_SMTP_USER', 'box@domain.ru');
    //   define('TOPAS_SMTP_PASS', 'app-password');
    //   define('TOPAS_SMTP_PORT', 465);
    //   define('TOPAS_SMTP_SECURE', 'ssl');      // ssl | tls
    //   define('TOPAS_SMTP_FROM', 'box@domain.ru');
    //   define('TOPAS_SMTP_FROM_NAME', 'Сервис Септик');
    if (defined('TOPAS_SMTP_HOST') && TOPAS_SMTP_HOST) {
        $phpmailer->isSMTP();
        $phpmailer->Host       = TOPAS_SMTP_HOST;
        $phpmailer->SMTPAuth   = true;
        $phpmailer->Username   = defined('TOPAS_SMTP_USER') ? TOPAS_SMTP_USER : '';
        $phpmailer->Password   = defined('TOPAS_SMTP_PASS') ? TOPAS_SMTP_PASS : '';
        $phpmailer->Port       = defined('TOPAS_SMTP_PORT') ? (int) TOPAS_SMTP_PORT : 465;
        $phpmailer->SMTPSecure = defined('TOPAS_SMTP_SECURE') ? TOPAS_SMTP_SECURE : 'ssl';

        // From обязан совпадать с авторизованным ящиком, иначе SMTP отклонит письмо.
        if (defined('TOPAS_SMTP_FROM') && TOPAS_SMTP_FROM) {
            $phpmailer->From   = TOPAS_SMTP_FROM;
            $phpmailer->Sender = TOPAS_SMTP_FROM;
        }
        if (defined('TOPAS_SMTP_FROM_NAME') && TOPAS_SMTP_FROM_NAME) {
            $phpmailer->FromName = TOPAS_SMTP_FROM_NAME;
        }
    }
}*/

// ===== ШОРТКОД ДЛЯ ФОРМЫ (опционально) =====
add_shortcode('premium_contact_form', 'render_premium_contact_form');
function render_premium_contact_form($atts) {
    $atts = shortcode_atts([
        'type' => 'callback',
        'title' => 'Заказать звонок',
        'button' => 'Отправить',
    ], $atts);

    ob_start();
    ?>
    <form class="premium-contact-form" data-form-type="<?php echo esc_attr($atts['type']); ?>">
        <input type="text" name="hp_email" class="form-hp" tabindex="-1" autocomplete="off" aria-hidden="true">
        <h4><?php echo esc_html($atts['title']); ?></h4>
        <div class="form-group">
            <input type="text" name="name" placeholder="Ваше имя *" required>
        </div>
        <div class="form-group">
            <input type="tel" name="phone" placeholder="+7 (___) ___-__-__ *" required>
        </div>
        <label class="form-consent"><input type="checkbox" name="consent" required checked> Согласен на обработку персональных данных</label>
        <button type="submit" class="btn-premium btn-gold"><?php echo esc_html($atts['button']); ?></button>
        <p class="form-privacy">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
    </form>
    <?php
    return ob_get_clean();
}

/**
 * ================= КАЛЬКУЛЯТОР ПОДБОРА И РАСЧЁТА (4.1) =================
 */

/**
 * Данные станций для калькулятора: реальные посты каталога.
 * Модель подбирается по «номеру» (ёмкости) = crb_model_number.
 *
 * @return array<int,array{number:int,title:string,url:string,price:int,img:string}>
 */
function izex_get_calculator_stations()
{
    $query = new WP_Query(array(
        'post_type'      => 'stations',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'no_found_rows'  => true,
    ));

    $stations = array();
    foreach ($query->posts as $post) {
        $id = $post->ID;
        $number = (int) carbon_get_post_meta($id, 'crb_model_number');
        if ($number <= 0) {
            continue; // без номера модель в подбор не участвует
        }
        // Базовая цена оборудования — линейка ТОПАС-С, с фолбэком на ТОПАС.
        $price = (int) preg_replace('/[^\d]/', '', (string) carbon_get_post_meta($id, 'crb_price_topas_s'));
        if ($price <= 0) {
            $price = (int) preg_replace('/[^\d]/', '', (string) carbon_get_post_meta($id, 'crb_price'));
        }
        $stations[] = array(
            'number' => $number,
            'title'  => get_the_title($id),
            'url'    => get_permalink($id),
            'price'  => $price,
            'img'    => get_the_post_thumbnail_url($id, 'medium') ?: '',
        );
    }
    wp_reset_postdata();

    // Сортируем по возрастанию ёмкости — для корректного подбора «ближайшей сверху».
    usort($stations, function ($a, $b) {
        return $a['number'] <=> $b['number'];
    });

    return $stations;
}

/**
 * Настройки калькулятора из Carbon Fields с безопасными значениями по умолчанию.
 *
 * @return array<string,mixed>
 */
function izex_get_calculator_settings()
{
    $num = function ($key, $default) {
        $val = carbon_get_theme_option($key);
        return ($val === '' || $val === null) ? $default : (float) $val;
    };

    return array(
        'installBase'     => $num('crb_calc_install_base', 35000),
        'delivery'        => $num('crb_calc_delivery', 0),
        'surchargeForced' => $num('crb_calc_surcharge_forced', 15000),
        'surchargeUgv'    => $num('crb_calc_surcharge_ugv', 10000),
        'surchargeLong'   => $num('crb_calc_surcharge_long', 6000),
        'surchargeLongUs' => $num('crb_calc_surcharge_longus', 12000),
        'soilCoeff'       => $num('crb_calc_soil_coeff', 10),
        'remotenessCoeff' => $num('crb_calc_remoteness_coeff', 10),
        'note'            => carbon_get_theme_option('crb_calc_note')
            ?: 'Это ориентировочный расчёт. Точная смета — после бесплатного выезда инженера.',
    );
}

/**
 * Шорткод калькулятора: [topas_calculator]
 */
add_shortcode('topas_calculator', 'render_topas_calculator');
function render_topas_calculator($atts)
{
    ob_start(); ?>
    <section class="calc" id="calc" aria-labelledby="calc-heading">
        <div class="calc__head">
            <h2 class="calc__title" id="calc-heading">Калькулятор подбора и расчёта</h2>
            <p class="calc__subtitle">Ответьте на 4 вопроса — подберём модель ТОПАС и покажем ориентировочную стоимость «под ключ».</p>
        </div>

        <div class="calc__progress" aria-hidden="true">
            <span class="calc__progress-bar" data-calc-progress></span>
        </div>

        <form class="calc__form" data-calc-form novalidate>
            <!-- Шаг 1 -->
            <div class="calc-step is-active" data-step="1">
                <p class="calc-step__q">Сколько человек будет проживать?</p>
                <div class="calc-options calc-options--people" role="group">
                    <?php for ($i = 1; $i <= 10; $i++) : ?>
                        <button type="button" class="calc-opt" data-name="people" data-value="<?php echo $i; ?>"><?php echo $i; ?></button>
                    <?php endfor; ?>
                    <button type="button" class="calc-opt" data-name="people" data-value="11">10+</button>
                </div>
            </div>

            <!-- Шаг 2 -->
            <div class="calc-step" data-step="2">
                <p class="calc-step__q">Способ водоотведения</p>
                <div class="calc-options" role="group">
                    <button type="button" class="calc-opt calc-opt--wide" data-name="disposal" data-value="gravity">
                        <span class="calc-opt__t">Самотёк</span>
                        <span class="calc-opt__d">Отвод очищенной воды в канаву/дренаж самотёком</span>
                    </button>
                    <button type="button" class="calc-opt calc-opt--wide" data-name="disposal" data-value="forced">
                        <span class="calc-opt__t">Принудительное</span>
                        <span class="calc-opt__d">С дренажным насосом — если самотёк невозможен</span>
                    </button>
                </div>
            </div>

            <!-- Шаг 3 -->
            <div class="calc-step" data-step="3">
                <p class="calc-step__q">Глубина подводящей трубы</p>
                <div class="calc-options" role="group">
                    <button type="button" class="calc-opt calc-opt--wide" data-name="depth" data-value="standard">
                        <span class="calc-opt__t">Стандарт</span>
                        <span class="calc-opt__d">Труба входит на глубине до ~0,6 м</span>
                    </button>
                    <button type="button" class="calc-opt calc-opt--wide" data-name="depth" data-value="long">
                        <span class="calc-opt__t">Лонг</span>
                        <span class="calc-opt__d">Удлинённая горловина для глубокого входа</span>
                    </button>
                    <button type="button" class="calc-opt calc-opt--wide" data-name="depth" data-value="longus">
                        <span class="calc-opt__t">Лонг Ус</span>
                        <span class="calc-opt__d">Максимально глубокий вход трубы</span>
                    </button>
                </div>
            </div>

            <!-- Шаг 4 -->
            <div class="calc-step" data-step="4">
                <p class="calc-step__q">Высокий уровень грунтовых вод (УГВ)?</p>
                <div class="calc-options" role="group">
                    <button type="button" class="calc-opt calc-opt--wide" data-name="ugv" data-value="no">
                        <span class="calc-opt__t">Нет</span>
                        <span class="calc-opt__d">Вода стоит ниже уровня установки</span>
                    </button>
                    <button type="button" class="calc-opt calc-opt--wide" data-name="ugv" data-value="yes">
                        <span class="calc-opt__t">Да / не знаю</span>
                        <span class="calc-opt__d">Потребуется пригруз/якорение станции</span>
                    </button>
                </div>
            </div>

            <!-- Результат -->
            <div class="calc-step calc-result" data-step="result" hidden>
                <div class="calc-result__inner" data-calc-result></div>
            </div>

            <div class="calc__nav">
                <button type="button" class="calc__back" data-calc-back hidden>← Назад</button>
                <button type="button" class="calc__restart" data-calc-restart hidden>Начать заново</button>
            </div>
        </form>
    </section>
    <?php
    return ob_get_clean();
}

/**
 * ================= СРАВНЕНИЕ МОДЕЛЕЙ (4.2) =================
 *
 * Полный набор характеристик станций для таблицы сравнения.
 * Числовые характеристики форматируются тем же хелпером, что и в каталоге
 * (izex_format_station_spec) — единый вид единиц измерения (см. 5.1).
 *
 * @return array<int,array{id:int,title:string,url:string,img:string,specs:array<int,array{label:string,value:string}>}>
 */
function izex_get_compare_stations()
{
    $query = new WP_Query(array(
        'post_type'      => 'stations',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'no_found_rows'  => true,
    ));

    $rub = function ($raw) {
        $n = (int) preg_replace('/[^\d]/', '', (string) $raw);
        return $n > 0 ? number_format($n, 0, '.', ' ') . ' ₽' : '';
    };

    $out = array();
    foreach ($query->posts as $post) {
        $id = $post->ID;
        $specs = array(
            array('label' => 'Обслуживает',      'value' => (string) carbon_get_post_meta($id, 'crb_people_count_text')),
            array('label' => 'Производительность', 'value' => izex_format_station_spec(carbon_get_post_meta($id, 'crb_daily_volume'), 'м³/сут')),
            array('label' => 'Залповый сброс',   'value' => izex_format_station_spec(carbon_get_post_meta($id, 'crb_peak_discharge'), 'л')),
            array('label' => 'Потребление',      'value' => izex_format_station_spec(carbon_get_post_meta($id, 'crb_power_consumption'), 'кВт·ч/сут')),
            array('label' => 'Водоотведение',    'value' => (string) carbon_get_post_meta($id, 'crb_water_disposal')),
            array('label' => 'Компрессоров',     'value' => (string) carbon_get_post_meta($id, 'crb_compressors_topas_s')),
            array('label' => 'Габариты (монтаж)', 'value' => (string) carbon_get_post_meta($id, 'crb_mounting_dimensions')),
            array('label' => 'Цена ТОПАС-С',     'value' => $rub(carbon_get_post_meta($id, 'crb_price_topas_s'))),
            array('label' => 'Цена ТОПАС',       'value' => $rub(carbon_get_post_meta($id, 'crb_price'))),
        );
        // Пустые значения показываем как прочерк — чтобы строки таблицы совпадали по колонкам.
        foreach ($specs as &$row) {
            if ($row['value'] === '' || $row['value'] === null) {
                $row['value'] = '—';
            }
        }
        unset($row);

        $out[$id] = array(
            'id'    => $id,
            'title' => get_the_title($id),
            'url'   => get_permalink($id),
            'img'   => get_the_post_thumbnail_url($id, 'medium') ?: '',
            'specs' => $specs,
        );
    }
    wp_reset_postdata();

    return $out;
}

/**
 * Разметка кнопки «Сравнить» для карточки станции.
 * Используется в шаблонах каталога.
 */
function izex_compare_button($post_id)
{
    $post_id = (int) $post_id;
    ?>
    <button type="button" class="station-compare js-compare-toggle" data-compare-id="<?php echo esc_attr($post_id); ?>" aria-pressed="false">
        <svg class="station-compare__icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7 16V4M7 4 3 8M7 4l4 4M17 8v12M17 20l4-4M17 20l-4-4"/></svg>
        <span class="station-compare__label">Сравнить</span>
    </button>
    <?php
}

/**
 * ================= ГАЛЕРЕЯ РАБОТ (4.3) =================
 */

/**
 * Карточка выполненной работы для сетки.
 */
function izex_render_work_card($post_id)
{
    $post_id = (int) $post_id;
    $model = carbon_get_post_meta($post_id, 'crb_work_model');
    $location = carbon_get_post_meta($post_id, 'crb_work_location');
    $img = get_the_post_thumbnail_url($post_id, 'medium_large');
    ?>
    <article class="work-card" data-model="<?php echo esc_attr($model); ?>">
        <a class="work-card__media" href="<?php echo esc_url(get_permalink($post_id)); ?>">
            <?php if ($img) : ?>
                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr(get_the_title($post_id)); ?>" loading="lazy">
            <?php else : ?>
                <span class="work-card__noimg">Фото объекта</span>
            <?php endif; ?>
            <?php if ($model) : ?>
                <span class="work-card__badge"><?php echo esc_html($model); ?></span>
            <?php endif; ?>
        </a>
        <div class="work-card__body">
            <h3 class="work-card__title">
                <a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html(get_the_title($post_id)); ?></a>
            </h3>
            <?php if ($location) : ?>
                <p class="work-card__loc">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?php echo esc_html($location); ?>
                </p>
            <?php endif; ?>
            <?php $excerpt = get_the_excerpt($post_id); ?>
            <?php if ($excerpt) : ?>
                <p class="work-card__desc"><?php echo esc_html(wp_trim_words($excerpt, 18)); ?></p>
            <?php endif; ?>
        </div>
    </article>
    <?php
}

/**
 * Шорткод блока последних работ: [topas_works count="8"]
 * Возвращает пустую строку, если работ ещё нет — чтобы не показывать пустой блок.
 */
add_shortcode('topas_works', 'render_topas_works_block');
function render_topas_works_block($atts)
{
    $atts = shortcode_atts(array('count' => 8), $atts, 'topas_works');

    $query = new WP_Query(array(
        'post_type'      => 'works',
        'posts_per_page' => (int) $atts['count'],
        'post_status'    => 'publish',
        'no_found_rows'  => true,
    ));

    if (!$query->have_posts()) {
        wp_reset_postdata();
        return '';
    }

    ob_start(); ?>
    <section class="works-block testimonials-premium">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Наши работы</h2>
                <p class="section-subtitle" style="color: white">Реальные объекты с установленными станциями ТОПАС</p>
            </div>
            <div class="works-grid">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php izex_render_work_card(get_the_ID()); ?>
                <?php endwhile; ?>
            </div>
            <div class="works-block__more">
                <a class="btn-premium btn-primary" href="<?php echo esc_url(get_post_type_archive_link('works')); ?>">Смотреть все работы</a>
            </div>
        </div>
    </section>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}

/**
 * ================= СТРАНИЦА «ЦЕНЫ» (4.4) =================
 */

/**
 * Таблица цен по моделям: оборудование / монтаж / под ключ.
 * Оборудование и модели — из реального каталога; монтаж — из настроек
 * калькулятора (базовый монтаж «под ключ»). «Под ключ» = оборудование + монтаж.
 *
 * @return array<int,array{title:string,url:string,equipment:int,install:int,turnkey:int}>
 */
function izex_get_prices_table()
{
    $stations = izex_get_calculator_stations();
    $settings = izex_get_calculator_settings();
    $install = (int) $settings['installBase'];

    $rows = array();
    foreach ($stations as $st) {
        $equip = (int) $st['price'];
        $rows[] = array(
            'title'     => $st['title'],
            'url'       => $st['url'],
            'equipment' => $equip,
            'install'   => $install,
            'turnkey'   => $equip > 0 ? $equip + $install : 0,
        );
    }
    return $rows;
}

/**
 * Списки «входит в монтаж» / «оплачивается отдельно» / прайс обслуживания.
 * Берутся из настроек темы (вкладка «Цены»); для «входит/отдельно» —
 * разумные значения по умолчанию, чтобы страница не была пустой до заполнения.
 *
 * @return array{included:string[],extra:string[],maintenance:array<int,array{model:string,price:string}>}
 */
function izex_get_prices_lists()
{
    $pluck = function ($raw, $key) {
        $out = array();
        if (!empty($raw) && is_array($raw)) {
            foreach ($raw as $row) {
                if (!empty($row[$key])) {
                    $out[] = $row[$key];
                }
            }
        }
        return $out;
    };

    $included = $pluck(carbon_get_theme_option('crb_prices_included'), 'item');
    $extra = $pluck(carbon_get_theme_option('crb_prices_extra'), 'item');

    if (empty($included)) {
        $included = array(
            'Выезд инженера и разметка',
            'Земляные работы (котлован под станцию)',
            'Установка и обвязка станции',
            'Врезка подводящей трубы',
            'Пусконаладка и инструктаж',
        );
    }
    if (empty($extra)) {
        $extra = array(
            'Разработка тяжёлого/скального грунта',
            'Обратная засыпка песком (при необходимости)',
            'Прокладка длинных траншей отвода',
            'Обустройство точки сброса на большом удалении',
        );
    }

    $maintenance = array();
    $raw_m = carbon_get_theme_option('crb_prices_maintenance');
    if (!empty($raw_m) && is_array($raw_m)) {
        foreach ($raw_m as $row) {
            if (!empty($row['model'])) {
                $maintenance[] = array(
                    'model' => $row['model'],
                    'price' => isset($row['price']) ? $row['price'] : '',
                );
            }
        }
    }

    return array(
        'included'    => $included,
        'extra'       => $extra,
        'maintenance' => $maintenance,
    );
}

// ===== ПОДКЛЮЧЕНИЕ СКРИПТОВ И СТИЛЕЙ =====
add_action('wp_enqueue_scripts', 'enqueue_premium_form_assets');
/**
 * ID счётчика Яндекс.Метрики (для отправки целей из форм).
 * Значение совпадает со счётчиком в footer.php. Можно переопределить фильтром
 * 'izex_metrika_id' — на случай смены счётчика без правки JS.
 *
 * @return int
 */
function izex_metrika_id()
{
    return (int) apply_filters('izex_metrika_id', 109479860);
}

function enqueue_premium_form_assets() {
    wp_localize_script('global-scripts', 'premiumFormVars', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('premium_form_nonce'),
        'metrikaId' => izex_metrika_id(),
        'messages' => [
            'success' => 'Спасибо! Мы свяжемся с вами в течение 15 минут.',
            'error' => 'Ошибка отправки. Попробуйте позвонить нам.',
            'validation' => [
                'name' => 'Введите имя (мин. 2 символа)',
                'phone' => 'Введите корректный телефон',
            ]
        ]
    ]);
}

// Функция для замены путей к изображениям в статьях
//function replace_image_paths() {
//    global $wpdb;
//    $old_path = '/userfiles/upload/';
//    $new_path = '/wp-content/uploads/2023/08/';
//    $query = "UPDATE {$wpdb->prefix}posts SET post_content = REPLACE(post_content, '{$old_path}', '{$new_path}') WHERE post_type = 'post' AND post_content LIKE '%{$old_path}%'";
//    $wpdb->query($query);
//}
//
//add_action('after_setup_theme', 'replace_image_paths');

// Функция для замены путей к изображениям в статье Взвешивание грузовых автомобилей
/*function replace_image_path_on_page_1971() {
    global $wpdb;
    $page_id = 1971;
    $old_path = 'i/car/1.jpg';
    $new_path = '/assets/image/car/1.jpg';
    $page_content = $wpdb->get_var("SELECT post_content FROM {$wpdb->prefix}posts WHERE ID = {$page_id}");
    $updated_content = str_replace($old_path, $new_path, $page_content);
    $wpdb->update($wpdb->prefix . 'posts', array('post_content' => $updated_content), array('ID' => $page_id));
}
add_action('after_setup_theme', 'replace_image_path_on_page_1971');*/

// Заменить {delivery} на таблицу из TablePress
//function replace_delivery_shortcode( $content ) {
//    if ( false !== strpos( $content, '{delivery}' ) ) {
//        $table_id = 3;
//        $table = tablepress_get_table( $table_id );
//        if ( ! empty( $table ) ) {
//            $table_content = do_shortcode( '[table id=' . $table_id . ' /]' );
//            $content = str_replace( '{delivery}', $table_content, $content );
//        }
//    }
//
//    return $content;
//}
//add_filter( 'the_content', 'replace_delivery_shortcode' );
//function custom_rewrite_rules($rules) {
//    $new_rules = array(
//        'staty/([^/]+)/?$' => 'index.php?name=$matches[1]', // Для страницы
//        'staty-category/([^/]+)/?$' => 'index.php?category_name=$matches[1]', // Для рубрики
//    );
//    return $new_rules + $rules;
//}
//add_filter('rewrite_rules_array', 'custom_rewrite_rules');
/*function custom_category_template_redirect() {
    if (is_category('staty')) {
        $custom_template = get_template_directory() . '/category-staty.php';
        if (file_exists($custom_template)) {
            include $custom_template;
            exit; // Stop further execution to prevent default template loading
        }
    }
}
add_action('template_redirect', 'custom_category_template_redirect');*/

