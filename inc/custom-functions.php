<?php

function enqueue_versioned_script($handle, $src = false, $deps = array(), $in_footer = false)
{
    wp_enqueue_script($handle, get_stylesheet_directory_uri() . $src, $deps, filemtime(get_stylesheet_directory() . $src), $in_footer);
}

function enqueue_versioned_style($handle, $src = false, $deps = array(), $media = 'all')
{
    wp_enqueue_style($handle, get_stylesheet_directory_uri() . $src, $deps = array(), filemtime(get_stylesheet_directory() . $src), $media);
}

function assets($link)
{
    $finalLink = get_stylesheet_directory_uri() . '/assets' . $link . '';
    return wp_make_link_relative($finalLink);
}


function num2word($num, $words)
{
    $num = $num % 100;
    if ($num > 19) {
        $num = $num % 10;
    }
    switch ($num) {
        case 1:
        {
            return ($words[0]);
        }
        case 2:
        case 3:
        case 4:
        {
            return ($words[1]);
        }
        default:
        {
            return ($words[2]);
        }
    }
}


function excerpt($limit = 250, $id = '')
{
    $id = $id ?? get_the_ID();
    $excerpt = explode(' ', get_the_excerpt($id), $limit);
    if (count($excerpt) >= $limit) {
        array_pop($excerpt);
        $excerpt = implode(" ", $excerpt) . '...';
    } else {
        $excerpt = implode(" ", $excerpt);
    }
    $excerpt = preg_replace('`[[^]]*]`', '', $excerpt);
    return $excerpt;
}

function declOfNum($num, $titles)
{
    $cases = array(2, 0, 1, 1, 1, 2);
    $text = $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
    $text = '<span>&nbsp;' . $text . '</span>';
    return $num . " " . $text;
}


function send_curl($url, $request_data = array())
{
    $arReturn = array();

    $ch = curl_init();
    // curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    if (!empty($request_data)) {
        curl_setopt_array($ch, array(CURLOPT_POST => TRUE, CURLOPT_POSTFIELDS => json_encode($request_data),));
    }

    // Выполнение запроса
    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        // Вывод сообщения об ошибке
        $arReturn['error'] = curl_error($ch);
    } else {
        // Вывод результата в виде строки
        $arReturn['result'] = $result;
    }
    curl_close($ch);
    return $arReturn;
}

function d($mixed, $caption = null)
{
    echo $caption ? '<br><b>' . $caption . '</b>' : '';
    $debug_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    echo '<div style="font-size:11px; color: gray;">' . str_replace('/home/nikolas/NetBeansProjectsGit/tahograph-twig', '', $debug_backtrace[0]['file']) . ' on line ' . $debug_backtrace[0]['line'] . '</div>';
    echo '<pre style="font-size:11px; line-height:1.2; padding:5px; color:darkgreen;">' . print_r($mixed, 1) . '</pre>';
    echo '<hr>';
}

function dd($mixed, $caption = null)
{
    echo $caption ? '<br><b>' . $caption . '</b>' : '';
    $debug_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    echo '<div style="font-size:12px; color: gray;">' . str_replace('/home/nikolas/NetBeansProjectsGit/tahograph-twig', '', $debug_backtrace[0]['file']) . ' on line ' . $debug_backtrace[0]['line'] . '</div>';
    echo '<pre style="font-size:12px; color:black;">' . print_r($mixed, 1) . '</pre>';
    exit;
}


function nformat($num)
{
    return number_format($num, 2, '.', ' ');
}

function htime($date)
{
    return (new DateTime($date))->format('H:i');
}

function hdate($date)
{
    return (new DateTime($date))->format('d.m.Y');
}

function catStr($str, $len = 120)
{
    return mb_strimwidth(strip_tags($str), 0, $len, '...');
}

function num2str($num)
{
    $nul = 'ноль';
    $ten = [['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'], ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],];
    $a20 = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];
    $tens = [2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];
    $hundred = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];
    $unit = [// Units
        ['копейка', 'копейки', 'копеек', 1], ['рубль', 'рубля', 'рублей', 0], ['тысяча', 'тысячи', 'тысяч', 1], ['миллион', 'миллиона', 'миллионов', 0], ['миллиард', 'милиарда', 'миллиардов', 0],];
    //
    [$rub, $kop] = explode('.', sprintf("%015.2f", floatval($num)));
    $out = [];
    if (intval($rub) > 0) {
        foreach (str_split($rub, 3) as $uk => $v) { // by 3 symbols
            if (!intval($v)) continue;
            $uk = sizeof($unit) - $uk - 1; // unit key
            $gender = $unit[$uk][3];
            [$i1, $i2, $i3] = array_map('intval', str_split($v, 1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2 > 1) $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3];# 20-99
            else
                $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3];# 10-19 | 1-9
            // units without rub & kop
            if ($uk > 1) $out[] = morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
        } //foreach
    } else
        $out[] = $nul;
    $out[] = morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
    $out[] = $kop . ' ' . morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
}

function morph($n, $f1, $f2, $f3)
{

    $n = abs((int)$n) % 100;
    if ($n > 10 && $n < 20) {
        return $f3;
    }
    $n = $n % 10;
    if ($n > 1 && $n < 5) {
        return $f2;
    }
    if ($n == 1) {
        return $f1;
    }
    return $f3;
}

/**
 * Единый формат характеристики станции.
 *
 * Приводит «грязные» значения из админки к одному виду: вырезает вписанную
 * вручную единицу измерения (м3, кВт/сутки и т.п.), нормализует разделители
 * диапазона и добавляет каноническую единицу. Используется и в карточке
 * каталога, и на странице станции — единый источник правды по форматированию,
 * чтобы исключить разнобой («0,8 м³/сутки» vs «0,8 м3/сутки», «250» без единицы).
 *
 * @param string $raw  Значение поля («0,8 м3/сутки», «250», «1,2-1,6 кВт*сутки»…).
 * @param string $unit Каноническая единица («м³/сут», «кВт·ч/сут», «л», «м») или '' — только число.
 * @return string      Отформатированное значение (без HTML-экранирования) или '' если пусто.
 */
function izex_format_station_spec($raw, $unit = '')
{
    $raw = trim((string) $raw);
    if ($raw === '') {
        return '';
    }

    // Разные виды тире приводим к обычному дефису.
    $normalized = str_replace(array('–', '—', '−'), '-', $raw);

    // Извлекаем числовую часть: одиночное число или диапазон «a-b».
    if (preg_match('/\d+(?:[.,]\d+)?(?:\s*-\s*\d+(?:[.,]\d+)?)?/u', $normalized, $m)) {
        // Убираем пробелы вокруг дефиса: «1,2 - 1,6» → «1,2-1,6».
        $value = preg_replace('/\s*-\s*/', '-', trim($m[0]));
    } else {
        // Число не распознано — возвращаем исходную строку без добавления единицы.
        return $raw;
    }

    return $unit !== '' ? $value . ' ' . $unit : $value;
}

?>
