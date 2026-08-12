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
    // Пустые файлы-заготовки (0 байт или одна строка sourceMappingURL) не
    // подключаем: правил в них нет, а запрос блокирует рендер.
    if (filesize($abs) < 64) {
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
    // Токены дизайн-системы — первым файлом: остальные CSS обращаются к ним.
    izex_enqueue_theme_style('tokens', '/assets/styles/tokens.css', array());

    wp_enqueue_style('Montserrat-font', get_template_directory_uri() . '/assets/fonts/montserrat.css');
    wp_enqueue_style('normalize', get_template_directory_uri() . '/assets/styles/normalize.css');
    // enqueue_versioned_style('global-style', '/assets/styles/styles.min.css');
    wp_enqueue_style('global-style', get_template_directory_uri() . '/assets/styles/styles.css');
    // Убраны page-staty.css, mobile.css и sidebar.css: в файлах нет ни одного
    // правила, только строка «/*# sourceMappingURL=… */» — это были три
    // блокирующих рендер запроса на каждой странице впустую.
    wp_enqueue_style('catalog-style', get_template_directory_uri() . '/assets/styles/catalog.css', array('global-style'), filemtime(get_template_directory() . '/assets/styles/catalog.css'));
    wp_enqueue_style('premium-components', get_template_directory_uri() . '/assets/styles/premium-components.css', array('global-style'), filemtime(get_template_directory() . '/assets/styles/premium-components.css'));

    wp_deregister_script('jquery');
    wp_register_script('jquery', get_template_directory_uri() . '/assets/js/jquery-3.7.0.min.js', array(), '3.7.0', true);
    wp_enqueue_script('jquery');

    $template = $GLOBALS['izex_current_template'] ?? '';

    // Swiper (160 КБ CSS+JS) грузился на каждой странице, хотя слайдера на сайте
    // нет: разметки swiper-* в шаблонах не осталось, а инициализация (new Swiper)
    // лежит в неподключаемом assets/js/scripts.js. Оставляем только на старой
    // странице «О компании» — там вставлен сторонний виджет со своей вёрсткой.
    if ($template === 'page-o_companii.php') {
        wp_enqueue_style('swiper-css', get_template_directory_uri() . '/assets/styles/swiper-bundle.min.css');
        wp_enqueue_script('swiper', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', array('jquery'), '', true);
    }

    // API Яндекс.Карт отключён: ничего его не вызывает (инициализация лежит в
    // assets/js/scripts.js, который не подключается, а страница контактов
    // показывает карту через <iframe>). Это ~250 КБ внешнего JS на каждой странице.
    // Понадобится снова — подключать точечно на нужном шаблоне.

    wp_enqueue_script('global-scripts', get_template_directory_uri() . '/assets/js/global.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/global.js'), true);
    wp_enqueue_script('premium-ui', get_template_directory_uri() . '/assets/js/premium-ui.js', array(), filemtime(get_template_directory() . '/assets/js/premium-ui.js'), true);

    // Скрипт страницы станции — только на самой странице станции.
    if (is_singular('stations')) {
        wp_enqueue_script('station-single', get_template_directory_uri() . '/assets/js/station-single.js', array(), filemtime(get_template_directory() . '/assets/js/station-single.js'), true);
    }
    // Убраны station-1-data / station-112-data: файлов assets/js/1-id.js и
    // 112-id.js в теме нет, каждая страница получала по два ответа 404.

    // ===== Каталог, калькулятор, сравнение =====
    // Нужны на главной, в архиве станций и на странице станции. Плюс страховка:
    // если шорткод калькулятора или сравнения вставили в контент произвольной
    // страницы через редактор, ассеты всё равно подключатся.
    $needs_catalog_ui = is_front_page() || is_post_type_archive('stations') || is_singular('stations');

    if (!$needs_catalog_ui && is_singular()) {
        $content = (string) get_post_field('post_content', get_queried_object_id());
        foreach (array('topas_calculator', 'topas_compare_inline', 'topas_estimate') as $shortcode) {
            if (has_shortcode($content, $shortcode)) {
                $needs_catalog_ui = true;
                break;
            }
        }
    }

    if ($needs_catalog_ui) {
        // calculator.css больше не подключаем: он описывает разметку прежнего
        // пошагового визарда, а шорткод рендерит одноэкранную версию (её стили
        // лежат в pro-blocks.css). При откате на render_topas_calculator()
        // нужно вернуть и эту строку.
        wp_enqueue_script('topas-calculator', get_template_directory_uri() . '/assets/js/calculator.js', array(), filemtime(get_template_directory() . '/assets/js/calculator.js'), true);
        wp_localize_script('topas-calculator', 'topasCalc', array(
            'stations' => izex_get_calculator_stations(),
            'settings' => izex_get_calculator_settings(),
        ));

        wp_enqueue_script('catalog-instant', get_template_directory_uri() . '/assets/js/catalog-instant.js', array(), filemtime(get_template_directory() . '/assets/js/catalog-instant.js'), true);

        izex_enqueue_theme_style('compare', '/assets/styles/compare.css');
        wp_enqueue_script('topas-compare', get_template_directory_uri() . '/assets/js/compare.js', array(), filemtime(get_template_directory() . '/assets/js/compare.js'), true);
        wp_localize_script('topas-compare', 'topasCompare', array(
            'stations' => izex_get_compare_stations(),
        ));
    }

    // Стили новых блоков нужны везде: карточка/каталог — на страницах каталога,
    // а док связи и нижняя панель есть на всём сайте.
    izex_enqueue_theme_style('pro-blocks', '/assets/styles/pro-blocks.css', array('premium-components'));

    // ===== Постраничные стили (вынесены из <style> в шаблонах) =====

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
 * Слой согласования компонентов — подключается последним.
 *
 * Приоритет 20 важен: файл должен идти после premium-components.css,
 * front-page.css и pro-blocks.css, иначе legacy-правила перебьют его по
 * порядку каскада (селекторы одинаковой специфичности).
 */
function izex_components_style()
{
    izex_enqueue_theme_style('components', '/assets/styles/components.css', array('premium-components'));
}
add_action('wp_enqueue_scripts', 'izex_components_style', 20);

/**
 * ================= ПРОИЗВОДИТЕЛЬНОСТЬ ФРОНТА =================
 */

/**
 * Ранние подсказки браузеру: свой шрифт и хост картинки в hero.
 *
 * Шрифт грузится через @font-face внутри montserrat.css, то есть браузер узнаёт
 * о нём только после разбора CSS — preload убирает эту задержку. Фон hero лежит
 * на внешнем хосте (userapi.com), поэтому для него нужен preconnect: это LCP
 * главной страницы.
 */
function izex_resource_hints()
{
    $font = get_template_directory_uri() . '/assets/fonts/Montserrat-subset.woff2';
    echo '<link rel="preload" href="' . esc_url($font) . '" as="font" type="font/woff2" crossorigin>' . "\n";

    if (is_front_page()) {
        echo '<link rel="preconnect" href="https://sun9-56.userapi.com" crossorigin>' . "\n";
    }
}
add_action('wp_head', 'izex_resource_hints', 1);

/**
 * Убираем то, что WordPress отдаёт по умолчанию, но сайту не нужно:
 * inline-скрипт эмодзи (~10 КБ + внешний запрос), wp-embed.js (встраивание чужих
 * постов WP), стили Gutenberg-блоков и dashicons для незалогиненных.
 */
function izex_dequeue_unused()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');

    wp_dequeue_script('wp-embed');
    wp_deregister_script('wp-embed');

    // Стили блочного редактора убираем только там, где блоков в контенте нет:
    // на страницах и в статьях, свёрстанных блоками, они нужны для вёрстки.
    $has_blocks = false;
    if (is_singular()) {
        $has_blocks = has_blocks(get_queried_object_id());
    }
    if (!$has_blocks) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('global-styles');
        wp_dequeue_style('classic-theme-styles');
    }

    if (!is_user_logged_in()) {
        wp_dequeue_style('dashicons');
    }
}
add_action('wp_enqueue_scripts', 'izex_dequeue_unused', 100);

/**
 * Отложенная загрузка некритичного JS.
 *
 * Всё перечисленное не участвует в первой отрисовке: defer снимает их с
 * критического пути, но сохраняет порядок выполнения (в отличие от async).
 */
function izex_defer_scripts($tag, $handle)
{
    // jQuery сознательно НЕ откладываем: скрипты плагинов зависят от него и сами
    // не отложены — они выполнились бы раньше загрузки jQuery и упали с ошибкой.
    // Из критического пути он уже выведен переносом в футер.
    $defer = array(
        'global-scripts', 'premium-ui', 'station-single',
        'topas-calculator', 'topas-compare', 'catalog-instant',
    );

    if (in_array($handle, $defer, true) && strpos($tag, ' defer') === false) {
        $tag = str_replace(' src=', ' defer src=', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'izex_defer_scripts', 10, 2);

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
 * Блоки по прототипу заказчика: карточка станции, смета «входит/отдельно»,
 * инлайн-сравнение, бегущая строка, одноэкранный калькулятор.
 */
require get_template_directory() . '/inc/pro-blocks.php';

/**
 * Микроразметка Schema.org, которую не покрывает Yoast:
 * LocalBusiness, Product для станций, ItemList для каталога.
 */
require get_template_directory() . '/inc/seo-schema.php';

/**
 * Техническое SEO по итогам аудита: дубли отфильтрованного каталога,
 * архивы авторов и рубрик, robots.txt, транслитерация ярлыков.
 */
require get_template_directory() . '/inc/seo-tech.php';

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
        error_log('[servis-diag] ОТКАЗ nonce (403). form_type=' . ($_POST['form_type'] ?? '?') . ' name=' . ($_POST['name'] ?? '?'));
        wp_send_json_error(['message' => 'Ошибка безопасности'], 403);
    }

    // ===== Анти-спам =====
    // Только honeypot: скрытое поле hp_email заполняют лишь боты — для реальных
    // клиентов оно невидимо и никак не мешает.
    // Прежнюю «временную ловушку» (отправка < 2 сек) УБРАЛИ: она молча теряла
    // настоящие заявки (показывала «Спасибо», но ничего не сохраняла и не слала).
    $hp = isset($_POST['hp_email']) ? trim((string) $_POST['hp_email']) : '';
    if ($hp !== '') {
        error_log('[servis-diag] honeypot сработал (бот): name=' . ($_POST['name'] ?? '?'));
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
        error_log('[servis-diag] ВАЛИДАЦИЯ не прошла: ' . implode('; ', $errors) . ' name="' . ($_POST['name'] ?? '') . '" phone="' . ($_POST['phone'] ?? '') . '"');
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
    // Адрес отправителя ДОЛЖЕН совпадать с ящиком, под которым авторизуется
    // WP Mail SMTP (smtp.mail.ru → gogle20023202@mail.ru). Иначе mail.ru
    // отклоняет письмо как спам с ошибкой «550 spam message rejected».
    $from_email = 'gogle20023202@mail.ru';
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $site_name . ' <' . $from_email . '>',
    );
    // Reply-To на основной email компании (чтобы ответ уходил владельцу).
    $owner_email = function_exists('getCarbonEmail') ? getCarbonEmail() : '';
    if ($owner_email && is_email($owner_email)) {
        $headers[] = 'Reply-To: ' . $owner_email;
    }

    // ===== СОХРАНЕНИЕ ЗАЯВКИ В БАЗУ (видно в админке → «Заявки») =====
    // Сохраняем ДО отправки письма, чтобы заявка не потерялась даже при сбое почты.
    $lead_id = wp_insert_post(array(
        'post_type'   => 'lead',
        'post_status' => 'publish',
        'post_title'  => ($name !== '' ? $name : 'Без имени') . ' — ' . $phone,
    ), true);

    if (is_wp_error($lead_id) || !$lead_id) {
        // База недоступна — это критично, сообщаем об ошибке.
        error_log('[servis-septik] Не удалось сохранить заявку: ' . (is_wp_error($lead_id) ? $lead_id->get_error_message() : 'unknown'));
        wp_send_json_error(array('message' => 'Ошибка отправки. Попробуйте позвонить нам.'), 500);
    }

    update_post_meta($lead_id, '_lead_name', $name);
    update_post_meta($lead_id, '_lead_phone', $phone);
    update_post_meta($lead_id, '_lead_form_type', $form_label);
    update_post_meta($lead_id, '_lead_address', $address);
    update_post_meta($lead_id, '_lead_comment', $comment);
    update_post_meta($lead_id, '_lead_email', $email_lead);
    update_post_meta($lead_id, '_lead_product', trim($product_name . ($product_id ? " (ID {$product_id})" : '')));
    update_post_meta($lead_id, '_lead_page_url', $page_url);
    update_post_meta($lead_id, '_lead_ip', $ip);
    update_post_meta($lead_id, '_lead_ua', $user_agent);
    update_post_meta($lead_id, '_lead_consent', $consent === '0' ? 'Нет' : 'Да');

    // ===== УВЕДОМЛЕНИЕ В MAX (best-effort: заявка уже сохранена) =====
    $max_text  = "🔔 *Новая заявка с сайта*\n";
    $max_text .= "Тип: " . $form_label . "\n";
    $max_text .= "Имя: " . $name . "\n";
    $max_text .= "Телефон: " . $phone . "\n";
    if ($product_name || $product_id) {
        $max_text .= "Товар/услуга: " . trim($product_name . ($product_id ? " (ID {$product_id})" : '')) . "\n";
    }
    if ($comment) {
        $max_text .= "Комментарий: " . $comment . "\n";
    }
    if ($page_url) {
        $max_text .= "Страница: " . $page_url . "\n";
    }
    $max_text .= "Время: " . $datetime;
    servis_notify_max($max_text);

    // ===== ОТПРАВКА ПИСЬМА (best-effort: заявка уже сохранена) =====
    $sent = wp_mail($recipients, $subject, $message, $headers);
    update_post_meta($lead_id, '_lead_mail_sent', $sent ? '1' : '0');
    if (!$sent) {
        error_log("[servis-septik] Письмо по заявке #{$lead_id} не отправлено (заявка сохранена в БД).");
    }

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

    error_log("Premium Form [{$form_type}] #{$lead_id}: {$name}, {$phone}, mail=" . ($sent ? 'ok' : 'FAIL'));

    // Заявка сохранена в базе — визитёру всегда показываем успех,
    // даже если письмо не ушло (заявку менеджер увидит в админке).
    wp_send_json_success(array(
        'message'  => 'Спасибо! Мы свяжемся с вами в течение 15 минут.',
        'redirect' => $product_id ? get_permalink($product_id) : '',
    ));
}

// ===== НАСТРОЙКА ОТПРАВКИ ПОЧТЫ ЧЕРЕЗ SMTP (без плагина) =====
// Аутентифицированная отправка через SMTP — надёжный способ доставки во «Входящие».
// Логин/пароль/сервер задаются КОНСТАНТАМИ в wp-config.php (не в теме и не в git!):
//
//   define('TOPAS_SMTP_HOST', 'smtp.mail.ru');
//   define('TOPAS_SMTP_USER', 'gogle20023202@mail.ru');
//   define('TOPAS_SMTP_PASS', 'пароль-приложения-mail.ru');
//   define('TOPAS_SMTP_PORT', 465);
//   define('TOPAS_SMTP_SECURE', 'ssl');            // ssl (порт 465) | tls (порт 587)
//   define('TOPAS_SMTP_FROM', 'gogle20023202@mail.ru');   // ДОЛЖЕН совпадать с USER
//   define('TOPAS_SMTP_FROM_NAME', 'Сервис Септик');
//
// ВАЖНО: плагин WP Mail SMTP при этом нужно ОТКЛЮЧИТЬ, иначе он перебьёт настройки.
add_action('phpmailer_init', 'servis_configure_smtp');
function servis_configure_smtp($phpmailer)
{
    // Без заданного хоста ничего не делаем — письма уйдут стандартным способом.
    if (!defined('TOPAS_SMTP_HOST') || !TOPAS_SMTP_HOST) {
        // Всё равно выравниваем Return-Path с From — помогает против спам-фильтров.
        if (!empty($phpmailer->From)) {
            $phpmailer->Sender = $phpmailer->From;
        }
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host       = TOPAS_SMTP_HOST;
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Username   = defined('TOPAS_SMTP_USER') ? TOPAS_SMTP_USER : '';
    $phpmailer->Password   = defined('TOPAS_SMTP_PASS') ? TOPAS_SMTP_PASS : '';
    $phpmailer->Port       = defined('TOPAS_SMTP_PORT') ? (int) TOPAS_SMTP_PORT : 465;
    $phpmailer->SMTPSecure = defined('TOPAS_SMTP_SECURE') ? TOPAS_SMTP_SECURE : 'ssl';
    $phpmailer->SMTPAutoTLS = false; // не навязывать TLS поверх SSL — как было в плагине
    $phpmailer->CharSet     = 'UTF-8';

    // From обязан совпадать с авторизованным ящиком, иначе mail.ru отклонит письмо (550).
    $from = defined('TOPAS_SMTP_FROM') && TOPAS_SMTP_FROM ? TOPAS_SMTP_FROM : (defined('TOPAS_SMTP_USER') ? TOPAS_SMTP_USER : '');
    if ($from) {
        $phpmailer->From   = $from;
        $phpmailer->Sender = $from; // Return-Path
    }
    if (defined('TOPAS_SMTP_FROM_NAME') && TOPAS_SMTP_FROM_NAME) {
        $phpmailer->FromName = TOPAS_SMTP_FROM_NAME;
    }
}

// Логируем причину, если письмо не удалось отправить (видно в логах хостинга).
add_action('wp_mail_failed', 'servis_log_mail_failed');
function servis_log_mail_failed($wp_error)
{
    if (is_wp_error($wp_error)) {
        error_log('[servis-septik] wp_mail failed: ' . $wp_error->get_error_message());
    }
}

// ===== УВЕДОМЛЕНИЕ О ЗАЯВКЕ В МЕССЕНДЖЕР MAX =====
// Надёжный канал уведомлений (не зависит от почтовой репутации домена).
// Токен бота и chat_id задаются КОНСТАНТАМИ в wp-config.php:
//
//   define('MAX_BOT_TOKEN', 'токен-бота-из-MAX');
//   define('MAX_CHAT_ID', '123456');   // id чата/диалога с ботом
//
// (необязательно) базовый адрес API можно переопределить:
//   define('MAX_API_BASE', 'https://platform-api.max.ru');
//
// Ответ MAX пишется в debug.log с меткой [max] — по нему видно, доставлено ли.
function servis_notify_max($text)
{
    if (!defined('MAX_BOT_TOKEN') || !MAX_BOT_TOKEN || !defined('MAX_CHAT_ID') || !MAX_CHAT_ID) {
        return; // не настроено — тихо выходим
    }
    $base = defined('MAX_API_BASE') && MAX_API_BASE ? MAX_API_BASE : 'https://platform-api.max.ru';

    // MAX_CHAT_ID может содержать несколько id через запятую/пробел/точку с запятой —
    // отправим каждому (личные чаты и/или группы).
    $chat_ids = array_filter(array_map('trim', preg_split('/[,;\s]+/', (string) MAX_CHAT_ID)));

    $payload = wp_json_encode(array(
        'text'   => mb_substr($text, 0, 3900),
        'format' => 'markdown',
    ));

    foreach ($chat_ids as $chat_id) {
        $response = wp_remote_post($base . '/messages?chat_id=' . rawurlencode($chat_id), array(
            'timeout' => 15,
            'headers' => array(
                'Authorization' => MAX_BOT_TOKEN,
                'Content-Type'  => 'application/json; charset=utf-8',
            ),
            'body' => $payload,
        ));

        if (is_wp_error($response)) {
            error_log('[max] chat ' . $chat_id . ' ошибка запроса: ' . $response->get_error_message());
            continue;
        }
        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        error_log('[max] chat ' . $chat_id . ' HTTP ' . $code . ' resp: ' . mb_substr((string) $body, 0, 400));
    }
}

// ===== ТИП ЗАПИСИ «ЗАЯВКИ» (для просмотра заявок в админке) =====
add_action('init', 'servis_register_lead_cpt');
function servis_register_lead_cpt()
{
    register_post_type('lead', array(
        'labels' => array(
            'name'          => 'Заявки',
            'singular_name' => 'Заявка',
            'menu_name'     => 'Заявки',
            'all_items'     => 'Все заявки',
            'edit_item'     => 'Просмотр заявки',
            'search_items'  => 'Искать заявки',
            'not_found'     => 'Заявок пока нет',
        ),
        'public'             => false,   // не показывать на сайте
        'show_ui'            => true,    // но показывать в админке
        'show_in_menu'       => true,
        'menu_position'      => 26,
        'menu_icon'          => 'dashicons-email-alt',
        'supports'           => array('title'),
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
        'exclude_from_search'=> true,
        'has_archive'        => false,
        'rewrite'            => false,
    ));
}

// Колонки в списке заявок.
add_filter('manage_lead_posts_columns', 'servis_lead_columns');
function servis_lead_columns($columns)
{
    return array(
        'cb'           => isset($columns['cb']) ? $columns['cb'] : '<input type="checkbox" />',
        'title'        => 'Имя',
        'lead_phone'   => 'Телефон',
        'lead_type'    => 'Тип заявки',
        'lead_product' => 'Товар/Услуга',
        'lead_page'    => 'Страница',
        'lead_mail'    => 'Письмо',
        'date'         => 'Дата',
    );
}

add_action('manage_lead_posts_custom_column', 'servis_lead_column_content', 10, 2);
function servis_lead_column_content($column, $post_id)
{
    switch ($column) {
        case 'lead_phone':
            $phone = get_post_meta($post_id, '_lead_phone', true);
            echo $phone ? '<a href="tel:' . esc_attr(preg_replace('/[^\d+]/', '', $phone)) . '">' . esc_html($phone) . '</a>' : '—';
            break;
        case 'lead_type':
            echo esc_html(get_post_meta($post_id, '_lead_form_type', true) ?: '—');
            break;
        case 'lead_product':
            echo esc_html(get_post_meta($post_id, '_lead_product', true) ?: '—');
            break;
        case 'lead_page':
            $url = get_post_meta($post_id, '_lead_page_url', true);
            echo $url ? '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">открыть</a>' : '—';
            break;
        case 'lead_mail':
            $sent = get_post_meta($post_id, '_lead_mail_sent', true);
            if ($sent === '1') {
                echo '<span style="color:#21b224;">✓ отправлено</span>';
            } elseif ($sent === '0') {
                echo '<span style="color:#d63638;">✗ не ушло</span>';
            } else {
                echo '—';
            }
            break;
    }
}

// Метабокс с полной информацией на странице просмотра заявки.
add_action('add_meta_boxes', 'servis_lead_metabox');
function servis_lead_metabox()
{
    add_meta_box('lead_details', 'Данные заявки', 'servis_lead_metabox_render', 'lead', 'normal', 'high');
}

function servis_lead_metabox_render($post)
{
    $fields = array(
        '_lead_name'      => 'Имя',
        '_lead_phone'     => 'Телефон',
        '_lead_email'     => 'Email',
        '_lead_form_type' => 'Тип заявки',
        '_lead_product'   => 'Товар/Услуга',
        '_lead_address'   => 'Адрес',
        '_lead_comment'   => 'Комментарий',
        '_lead_page_url'  => 'Страница заявки',
        '_lead_consent'   => 'Согласие на обработку ПД',
        '_lead_ip'        => 'IP-адрес',
        '_lead_ua'        => 'Устройство',
        '_lead_mail_sent' => 'Письмо отправлено',
    );
    echo '<table class="widefat striped"><tbody>';
    foreach ($fields as $key => $label) {
        $val = get_post_meta($post->ID, $key, true);
        if ($key === '_lead_mail_sent') {
            $val = $val === '1' ? 'Да' : ($val === '0' ? 'Нет' : '—');
        }
        echo '<tr><td style="width:200px;font-weight:600;">' . esc_html($label) . '</td><td>' . nl2br(esc_html($val !== '' ? $val : '—')) . '</td></tr>';
    }
    echo '</tbody></table>';
}

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
        // peopleStr/specs нужны карточке результата одноэкранного калькулятора
        // (см. calculator.js) — данные те же, что в карточке каталога.
        $pro = function_exists('izex_pro_station_data') ? izex_pro_station_data($id) : array();

        $stations[] = array(
            'number'    => $number,
            'title'     => get_the_title($id),
            'url'       => get_permalink($id),
            'price'     => $price,
            'img'       => get_the_post_thumbnail_url($id, 'medium') ?: '',
            'peopleStr' => isset($pro['people_text']) ? $pro['people_text'] : '',
            'specs'     => isset($pro['specs']) ? $pro['specs'] : array(),
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
        // Ссылка «Весь каталог» в карточке результата калькулятора.
        'catalogUrl'      => get_post_type_archive_link('stations') ?: '',
    );
}

/**
 * Шорткод калькулятора: [topas_calculator]
 *
 * Рендерит одноэкранную версию (inc/pro-blocks.php): вопросы слева, живая
 * карточка расчёта справа. Прежний пошаговый визард остался ниже в виде
 * render_topas_calculator() — на случай откката достаточно поменять коллбэк.
 */
add_shortcode('topas_calculator', 'render_topas_calculator_live');
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
    $img = has_post_thumbnail($post_id);
    ?>
    <article class="work-card" data-model="<?php echo esc_attr($model); ?>">
        <a class="work-card__media" href="<?php echo esc_url(get_permalink($post_id)); ?>">
            <?php if ($img) : ?>
                <?php
                // srcset + width/height от WordPress: в блоке 8 фото, на мобильных
                // раньше грузились полноразмерные medium_large для каждой карточки.
                echo get_the_post_thumbnail($post_id, 'medium_large', array(
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                    'alt'      => trim(get_the_title($post_id) . ($location ? ', ' . $location : '')),
                ));
                ?>
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
    // Раньше таблица строилась из izex_get_calculator_stations(), а та отбрасывает
    // станции без crb_model_number (для подбора модели номер обязателен). Из-за
    // этого в прайс попадали только базовые модели — 11 из 23, а модификации
    // Лонг/Пр оставались в каталоге без цены. Здесь берём все опубликованные
    // станции и отбрасываем только те, у которых не заполнена цена.
    $settings = izex_get_calculator_settings();
    $install = (int) $settings['installBase'];

    $query = new WP_Query(array(
        'post_type'      => 'stations',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ));

    $rows = array();
    foreach ($query->posts as $post) {
        $d = izex_pro_station_data($post->ID);
        if (empty($d['price'])) {
            continue; // без цены строка в прайсе бессмысленна
        }
        $rows[] = array(
            'title'     => $d['title'],
            'url'       => $d['url'],
            'equipment' => $d['price'],
            'install'   => $install,
            'turnkey'   => $d['turnkey'],
        );
    }
    wp_reset_postdata();

    return $rows;
}

/**
 * Списки «входит в монтаж» / «оплачивается отдельно» / прайс обслуживания.
 * Берутся из настроек темы (вкладка «Цены»); для «входит/отдельно» —
 * разумные значения по умолчанию, чтобы страница не была пустой до заполнения.
 *
 * «Оплачивается отдельно» отдаётся списком пар item + price: цену можно указать
 * диапазоном («от 5 000 ₽»), и тогда она выводится рядом с пунктом. Пункт без
 * цены выводится как раньше — просто текстом.
 *
 * @return array{included:string[],extra:array<int,array{item:string,price:string}>,maintenance:array<int,array{model:string,price:string}>}
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

    $extra = array();
    $raw_extra = carbon_get_theme_option('crb_prices_extra');
    if (!empty($raw_extra) && is_array($raw_extra)) {
        foreach ($raw_extra as $row) {
            if (!empty($row['item'])) {
                $extra[] = array(
                    'item'  => $row['item'],
                    'price' => isset($row['price']) ? (string) $row['price'] : '',
                );
            }
        }
    }

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
        // Значения по умолчанию — с ориентировочными диапазонами: пункт «оплачивается
        // отдельно» без цифр клиент достраивает по худшему сценарию.
        $extra = array(
            array('item' => 'Разработка тяжёлого или скального грунта', 'price' => 'уточняется на выезде'),
            array('item' => 'Обратная засыпка песком (при необходимости)', 'price' => 'уточняется на выезде'),
            array('item' => 'Прокладка длинных траншей отвода', 'price' => 'уточняется на выезде'),
            array('item' => 'Обустройство точки сброса на большом удалении', 'price' => 'уточняется на выезде'),
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

