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
if (file_exists(get_template_directory() . '/inc/seo.php')) {
    require get_template_directory() . '/inc/seo.php';
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
    d($recipients);
    d($subject);
    $sent = wp_mail($recipients, $subject, $message, $headers);
    dd($sent);
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
        <h4><?php echo esc_html($atts['title']); ?></h4>
        <div class="form-group">
            <input type="text" name="name" placeholder="Ваше имя *" required>
        </div>
        <div class="form-group">
            <input type="tel" name="phone" placeholder="+7 (___) ___-__-__ *" required>
        </div>
        <button type="submit" class="btn-premium btn-gold"><?php echo esc_html($atts['button']); ?></button>
        <p class="form-privacy">Нажимая кнопку, вы соглашаетесь с <a href="/privacy/">политикой конфиденциальности</a></p>
    </form>
    <?php
    return ob_get_clean();
}

// ===== ПОДКЛЮЧЕНИЕ СКРИПТОВ И СТИЛЕЙ =====
add_action('wp_enqueue_scripts', 'enqueue_premium_form_assets');
function enqueue_premium_form_assets() {
    wp_localize_script('global-scripts', 'premiumFormVars', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('premium_form_nonce'),
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

