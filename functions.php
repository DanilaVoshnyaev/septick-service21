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

    wp_deregister_script('jquery');
    wp_register_script('jquery', get_template_directory_uri() . '/assets/js/jquery-3.7.0.min.js');
    wp_enqueue_script('jquery');
    wp_enqueue_script('swiper', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', array('jquery'), '', true);
    wp_enqueue_script('ya-map', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=b14c454d-b28c-418a-8f62-e7f2244905fc&amp;ver=6.2.2', array('jquery'), '', true);
    //wp_enqueue_script('global',get_template_direcory_uri(). '/assets(/js/globaljs)',array('jquery'),'',true);
    //enqueue_versioned_script('global-scripts', '/assets/js/scripts.js');
    wp_enqueue_script('global-scripts', get_template_directory_uri() . '/assets/js/global.js', array('jquery'), '', true);
    wp_enqueue_script('global-scripts', get_template_directory_uri() . '/assets/js/1-id.js', array('jquery'), '', true);
    wp_enqueue_script('global-scripts', get_template_directory_uri() . '/assets/js/112-id.js', array('jquery'), '', true);
    //wp_enqueue_script('izex-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

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

    // ===== ФОРМИРОВАНИЕ ПИСЬМА =====
    $site_name = get_bloginfo('name');
    $admin_email = get_option('admin_email');

    $subject = "📩 Новая заявка: $form_type — $site_name";

    $message = "
    <h2>📋 Данные заявки</h2>
    <table style='border-collapse: collapse; width: 100%;'>
        <tr><td style='padding: 8px; border-bottom: 1px solid #eee;'><strong>Тип формы:</strong></td><td>$form_type</td></tr>
        <tr><td style='padding: 8px; border-bottom: 1px solid #eee;'><strong>Имя:</strong></td><td>$name</td></tr>
        <tr><td style='padding: 8px; border-bottom: 1px solid #eee;'><strong>Телефон:</strong></td><td>$phone</td></tr>
    ";

    if (!empty($address)) {
        $message .= "<tr><td style='padding: 8px; border-bottom: 1px solid #eee;'><strong>Адрес:</strong></td><td>$address</td></tr>";
    }
    if (!empty($comment)) {
        $message .= "<tr><td style='padding: 8px; border-bottom: 1px solid #eee;'><strong>Комментарий:</strong></td><td>$comment</td></tr>";
    }
    if ($product_id) {
        $message .= "<tr><td style='padding: 8px; border-bottom: 1px solid #eee;'><strong>Товар/Услуга:</strong></td><td>$product_name (ID: $product_id)</td></tr>";
    }

    $message .= "
        <tr><td style='padding: 8px; border-bottom: 1px solid #eee;'><strong>Дата:</strong></td><td>" . date('d.m.Y H:i') . "</td></tr>
        <tr><td style='padding: 8px; border-bottom: 1px solid #eee;'><strong>IP:</strong></td><td>" . $_SERVER['REMOTE_ADDR'] . "</td></tr>
        <tr><td style='padding: 8px; border-bottom: 1px solid #eee;'><strong>Страница:</strong></td><td>" . esc_url($_POST['page_url'] ?? '') . "</td></tr>
    </table>
    ";

    // Заголовки для HTML-письма
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $site_name . ' <noreply@' . preg_replace('#^www\.#', '', parse_url(home_url(), PHP_URL_HOST)) . '>'
    );

    // ===== ОТПРАВКА ПИСЬМА =====
    $sent = wp_mail($admin_email, $subject, $message, $headers);

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
    // Стили для уведомлений
    wp_add_inline_style('wp-block-library', '
        /* ===== TOAST NOTIFICATIONS ===== */
        .toast-container {
            position: fixed; top: 20px; right: 20px; z-index: 9999;
            display: flex; flex-direction: column; gap: 10px; max-width: 380px;
        }
        .toast {
            background: #fff; border-left: 4px solid #21b224;
            border-radius: 8px; padding: 14px 18px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
            display: flex; align-items: flex-start; gap: 12px;
            animation: toastSlideIn 0.3s ease;
        }
        .toast.toast-error { border-left-color: #ef4444; }
        .toast.toast-warning { border-left-color: #f59e0b; }
        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateX(100px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .toast-icon { flex-shrink: 0; width: 20px; height: 20px; margin-top: 2px; }
        .toast-success .toast-icon { color: #21b224; }
        .toast-error .toast-icon { color: #ef4444; }
        .toast-content { flex: 1; }
        .toast-title { font-weight: 600; color: #1e293b; margin-bottom: 4px; }
        .toast-message { font-size: 14px; color: #64748b; line-height: 1.4; }
        .toast-close {
            background: none; border: none; color: #94a3b8;
            cursor: pointer; padding: 4px; border-radius: 4px;
        }
        .toast-close:hover { background: #f1f5f9; color: #1e293b; }
        
        /* ===== FORM STATES ===== */
        .premium-contact-form .form-group input {
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .premium-contact-form .form-group input.error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }
        .premium-contact-form .form-error {
            color: #ef4444; font-size: 12px; margin-top: 4px;
            display: none;
        }
        .premium-contact-form .form-error.visible { display: block; }
        .premium-contact-form button[type="submit"]:disabled {
            opacity: 0.7; cursor: not-allowed;
        }
        .premium-contact-form button[type="submit"] .spinner {
            display: none; width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff; border-radius: 50%;
            animation: spin 0.6s linear infinite; margin-right: 8px;
        }
        .premium-contact-form button[type="submit"].loading .spinner { display: inline-block; }
        .premium-contact-form button[type="submit"].loading .btn-text { opacity: 0.7; }
        @keyframes spin { to { transform: rotate(360deg); } }
    ');

    // JS для форм
    wp_enqueue_script('premium-forms', get_template_directory_uri() . '/assets/js/premium-forms.js', ['jquery'], '1.0', true);

    // Локализация JS
    wp_localize_script('premium-forms', 'premiumFormVars', [
        'ajaxUrl' => admin_url('admin-post.php'),
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




