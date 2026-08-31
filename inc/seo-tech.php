<?php
/**
 * Техническое SEO по итогам аудита от 11.08.2026.
 *
 * Закрывает то, что решается кодом:
 *  - страницы каталога с параметрами фильтра (?capacity=&drainage=&sort=) —
 *    noindex + canonical на чистый URL, чтобы не плодить дубли;
 *  - архивы авторов — 301 на главную (сайт одного автора, страница пустая);
 *  - архивы рубрик — noindex и исключение из sitemap;
 *  - robots.txt — закрытие параметров фильтра, для Яндекса Clean-param;
 *  - транслитерация ярлыков новых записей (URL на кириллице).
 *
 * Работает и с Yoast, и без него: фильтры плагина применяются только когда он
 * активен, иначе используются ядровые (wp_robots, rel_canonical).
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

/**
 * Параметры, по которым фильтруется каталог.
 *
 * @return string[]
 */
function izex_catalog_filter_params()
{
    return array('capacity', 'drainage', 'sort', 'stock');
}

/**
 * Открыта ли сейчас отфильтрованная выдача каталога.
 *
 * @return bool
 */
function izex_is_filtered_catalog()
{
    if (!is_front_page() && !is_post_type_archive('stations')) {
        return false;
    }
    foreach (izex_catalog_filter_params() as $param) {
        if (!empty($_GET[$param])) {
            return true;
        }
    }
    return false;
}

/**
 * Отфильтрованные виды каталога — noindex, follow.
 *
 * Комбинаций фильтров десятки, содержимое у них пересекается с чистой страницей.
 * follow оставляем: по ссылкам на карточки робот должен ходить.
 *
 * @param array<string,bool|string> $robots Директивы.
 * @return array<string,bool|string>
 */
function izex_filtered_catalog_robots($robots)
{
    if (izex_is_filtered_catalog()) {
        $robots['noindex'] = true;
        $robots['follow'] = true;
        unset($robots['index']);
    }
    return $robots;
}
add_filter('wp_robots', 'izex_filtered_catalog_robots');
add_filter('wpseo_robots_array', 'izex_filtered_catalog_robots');

/**
 * Canonical отфильтрованной выдачи — на страницу без параметров.
 *
 * @param string $canonical Текущий canonical.
 * @return string
 */
function izex_filtered_catalog_canonical($canonical)
{
    if (!izex_is_filtered_catalog()) {
        return $canonical;
    }
    return is_front_page()
        ? home_url('/')
        : (string) get_post_type_archive_link('stations');
}
add_filter('wpseo_canonical', 'izex_filtered_catalog_canonical');

/**
 * Без Yoast canonical ядра тоже должен указывать на чистый URL.
 *
 * @return void
 */
function izex_filtered_catalog_core_canonical()
{
    if (!defined('WPSEO_VERSION') && izex_is_filtered_catalog()) {
        remove_action('wp_head', 'rel_canonical');
        echo '<link rel="canonical" href="' . esc_url(izex_filtered_catalog_canonical('')) . '">' . "\n";
    }
}
add_action('wp_head', 'izex_filtered_catalog_core_canonical', 1);

/**
 * Архивы авторов: сайт ведёт один человек, страница автора дублирует блог.
 * Отдаём 301 на главную — так надёжнее noindex, робот не тратит на неё обходы.
 *
 * @return void
 */
function izex_redirect_author_archives()
{
    if (is_author()) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
}
add_action('template_redirect', 'izex_redirect_author_archives');

// Убираем ссылку на архив автора из <head> и из sitemap Yoast.
add_filter('wpseo_sitemap_exclude_author', function ($users) {
    return get_users(array('fields' => array('ID')));
});

/**
 * Рубрики: тонкие дублирующие страницы (у сайта один информационный раздел).
 *
 * @param array<string,bool|string> $robots Директивы.
 * @return array<string,bool|string>
 */
function izex_category_noindex($robots)
{
    if (is_category() || is_tag() || is_date()) {
        $robots['noindex'] = true;
        $robots['follow'] = true;
        unset($robots['index']);
    }
    return $robots;
}
add_filter('wp_robots', 'izex_category_noindex');
add_filter('wpseo_robots_array', 'izex_category_noindex');

// Рубрики и метки — вне sitemap.
add_filter('wpseo_sitemap_exclude_taxonomy', function ($excluded, $taxonomy) {
    return in_array($taxonomy, array('category', 'post_tag'), true) ? true : $excluded;
}, 10, 2);

/**
 * robots.txt: закрываем параметры фильтра и служебные пути.
 *
 * Clean-param — директива Яндекса: она не запрещает обход, а склеивает URL с
 * параметрами с основным, передавая ему поведенческие и ссылочные показатели.
 * Для Google тем же занимается canonical выше.
 *
 * @param string $output Текущий robots.txt.
 * @return string
 */
function izex_robots_txt($output)
{
    $params = izex_catalog_filter_params();

    $extra = "\n# Служебные пути\n"
        . "Disallow: /wp-admin/\n"
        . "Allow: /wp-admin/admin-ajax.php\n"
        . "Disallow: /?s=\n"
        . "Disallow: /search/\n"
        . "Disallow: /*?replytocom=\n"
        . "\n# Параметры фильтра каталога — дубли выдачи\n";

    foreach ($params as $param) {
        $extra .= 'Disallow: /*?' . $param . '=' . "\n";
        $extra .= 'Disallow: /*&' . $param . '=' . "\n";
    }

    $extra .= "\n# Для Яндекса: склеиваем параметры фильтра с чистым URL\n"
        . 'Clean-param: ' . implode('&', $params) . "\n";

    return $output . $extra;
}
add_filter('robots_txt', 'izex_robots_txt', 20);

/**
 * Транслитерация ярлыков: URL на кириллице превращаются в %d0%be%d0%b1…
 *
 * Работает для НОВЫХ записей и страниц. Существующие адреса
 * (/services/обслуживание/ и три URL отзывов) нужно переименовать вручную
 * и поставить 301 со старых — переименовать их кодом нельзя, это данные.
 *
 * ВАЖНО про $context: транслитерируем только при context === 'save', то есть
 * при создании ярлыка. WordPress пропускает через sanitize_title и адрес из
 * запроса (sanitize_title_for_query, context === 'query'); если переводить и его,
 * уже существующие кириллические URL перестанут находиться и отдадут 404.
 *
 * @param string $slug    Ярлык после стандартной обработки WordPress.
 * @param string $raw     Исходная строка.
 * @param string $context Контекст вызова: save / query / display.
 * @return string
 */
function izex_translit_slug($slug, $raw = '', $context = 'display')
{
    if ($context !== 'save') {
        return $slug;
    }
    if ($slug === '' || !preg_match('/[а-яё]/iu', $slug)) {
        return $slug;
    }

    $map = array(
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
        'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    );

    $slug = mb_strtolower($slug, 'UTF-8');
    $slug = strtr($slug, $map);
    $slug = preg_replace('/[^a-z0-9\-]+/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);

    return trim($slug, '-');
}
add_filter('sanitize_title', 'izex_translit_slug', 20, 3);
