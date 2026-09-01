<?php
/**
 * ================= ИКОНКИ (задача #21) =================
 *
 * Эмодзи в интерфейсе рисуются шрифтом операционной системы: на Windows, macOS
 * и Android это три разные картинки, они не наследуют цвет текста и не
 * подчиняются размеру строки. Поэтому весь набор — inline-SVG из одного места:
 * один и тот же штрих 2 px, размер задаётся атрибутом, цвет наследуется
 * от currentColor.
 *
 * Использование: echo izex_icon('check', 18);
 *
 * @package WordPress
 * @subpackage Topas_Template
 */

/**
 * Inline-SVG иконка из набора темы.
 *
 * @param string $name  Имя иконки.
 * @param int    $size  Размер в пикселях.
 * @param string $class Дополнительный класс.
 * @return string Готовая разметка (или пустая строка, если иконки нет).
 */
function izex_icon($name, $size = 20, $class = '')
{
    static $paths = array(
        // Проверено, входит в комплект, готово.
        'check'    => '<path d="M20 6 9 17l-5-5"/>',
        // Гарантия, договор, защита.
        'shield'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        // Подсказка, совет.
        'bulb'     => '<path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12.7V17h8v-2.3A7 7 0 0 0 12 2z"/>',
        // Наличие, срочность.
        'bolt'     => '<path d="M13 2 4 14h7l-1 8 9-12h-7l1-8z"/>',
        // Дата, месяц.
        'calendar' => '<path d="M8 2v4M16 2v4M3 10h18"/><rect x="3" y="4" width="18" height="18" rx="2"/>',
        // Услуга, сервис, обслуживание.
        'wrench'   => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
        // Рекомендация, подбор.
        'sparkle'  => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M18.4 5.6l-2.8 2.8M8.4 15.6l-2.8 2.8"/>',
        // Оценка.
        'star'     => '<path d="m12 3 2.9 5.9 6.5.9-4.7 4.6 1.1 6.5-5.8-3-5.8 3 1.1-6.5L2.6 9.8l6.5-.9L12 3z"/>',
    );

    // Звезда рейтинга — заливкой: контурная в ряду из пяти читается хуже.
    if ($name === 'star-filled') {
        return sprintf(
            '<svg class="izex-icon%s" width="%d" height="%d" viewBox="0 0 24 24" '
            . 'fill="currentColor" aria-hidden="true" focusable="false">'
            . '<path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.8 21l1.2-6.8-5-4.9 6.9-1L12 2z"/></svg>',
            $class ? ' ' . esc_attr($class) : '',
            (int) $size,
            (int) $size
        );
    }

    if (!isset($paths[$name])) {
        return '';
    }

    $size = (int) $size;

    return sprintf(
        '<svg class="izex-icon%s" width="%d" height="%d" viewBox="0 0 24 24" fill="none" '
        . 'stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" '
        . 'aria-hidden="true" focusable="false">%s</svg>',
        $class ? ' ' . esc_attr($class) : '',
        $size,
        $size,
        $paths[$name]
    );
}
