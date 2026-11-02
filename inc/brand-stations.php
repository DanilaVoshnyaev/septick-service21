<?php
/**
 * Разовый импорт карточек станций других марок (характеристики проверены
 * по сайтам и паспортам производителей 02.11.2026).
 *
 * Запускается один раз при заходе в админку (флаг в опциях). Существующие
 * записи с тем же адресом не перезаписываются — правки делаются в админке.
 */

function izex_brand_stations_data()
{
    $spec_table = function ($rows) {
        $html = '<table><tbody>';
        foreach ($rows as $label => $value) {
            $html .= '<tr><th>' . esc_html($label) . '</th><td>' . esc_html($value) . '</td></tr>';
        }
        return $html . '</tbody></table>';
    };

    $content = function ($desc, $features, $rows, $source, $brand_url, $brand_name) use ($spec_table) {
        $html = '<p>' . esc_html($desc) . '</p>';
        $html .= '<h2>Особенности</h2><ul>';
        foreach ($features as $feature) {
            $html .= '<li>' . esc_html($feature) . '</li>';
        }
        $html .= '</ul>';
        $html .= '<h2>Технические характеристики</h2>' . $spec_table($rows);
        $html .= '<p>Характеристики приведены по данным производителя (проверено 02.11.2026). Комплектацию и цену уточним перед заказом.</p>';
        $html .= '<p><a href="' . esc_url($brand_url) . '">Все о септиках ' . esc_html($brand_name) . ': монтаж и обслуживание</a></p>';
        return $html;
    };

    return array(
        array(
            'slug'     => 'yunilos-astra-5',
            'title'    => 'Юнилос Астра 5',
            'excerpt'  => 'Станция на пять пользователей; базовое исполнение с самотечным отводом.',
            'content'  => $content(
                'Юнилос Астра 5 рассчитана на переработку 1 м³ бытовых стоков в сутки. Стандартное исполнение отличается от Миди и Лонг высотой и условиями подключения. Перед заказом сопоставляем глубину трубы, пиковую нагрузку и схему отвода воды. Насосное исполнение оформляется отдельной комплектацией.',
                array('Один компрессор 60 л/мин', 'Выбор исполнения по глубине подключения трубы', 'Отвод воды подбирается под участок'),
                array(
                    'Исполнение'          => 'Стандарт, самотечное',
                    'Пользователи'        => 'до 5',
                    'Переработка стоков'  => '1 м³/сут',
                    'Масса'               => '171 кг',
                    'Водоотведение'       => 'самотёком',
                ),
                'https://sbm-group.ru/products/unilos-astra/unilos-astra-5',
                '/septik-unilos-astra/',
                'Юнилос Астра'
            ),
            'meta'     => array(
                'crb_brand'               => 'Юнилос Астра',
                'crb_people_count_text'   => 'до 5',
                'crb_daily_volume'        => '1',
                'crb_water_disposal'      => 'самотечный',
                'crb_mounting_dimensions' => '1120 × 1030 × 2300 мм (высота с крышкой; с вентиляционным грибком — 2370 мм)',
                'crb_manual_pdf'          => 'https://sbm-group.ru/wp-content/uploads/tehpasport_astra_32-polosy-092025-obrazecz-s.pdf',
            ),
            'seo_title' => 'Юнилос Астра 5 в Чебоксарах — подбор и монтаж',
            'seo_desc'  => 'Юнилос Астра 5: характеристики стандартного исполнения, комплектация и расчёт установки в Чебоксарах. Подбор под условия участка.',
        ),
        array(
            'slug'     => 'eko-grand-5',
            'title'    => 'Эко-Гранд 5',
            'excerpt'  => 'Самотечная станция для трёх–пяти пользователей с переработкой до 1,1 м³/сут.',
            'content'  => $content(
                'Эко-Гранд 5 предназначена для очистки бытовых стоков частного дома. У этого исполнения самотечный отвод, производительность до 1,1 м³ в сутки и залповый сброс 250 л. Глубину врезки и компрессорное оборудование согласуем при подборе. Точную стоимость назовём после выбора комплектации.',
                array('До 1,1 м³ стоков в сутки', 'Подключение трубы на глубине 400–800 мм', 'Варианты компрессорной комплектации'),
                array(
                    'Исполнение'               => 'самотечное, один компрессор с клапаном',
                    'Пользователи'             => '3–5',
                    'Переработка стоков'       => '1,1 м³/сут',
                    'Залповый сброс'           => '250 л',
                    'Глубина подключения трубы' => '400–800 мм от поверхности грунта до низа трубы',
                    'Масса'                    => '230 кг',
                    'Энергопотребление'        => '0,9 кВт·ч/сут',
                    'Корпус'                   => 'полипропилен',
                    'Водоотведение'            => 'самотёком',
                ),
                'https://eco-grand.ru/ceptik-eko-grand-5.html',
                '/septik-eko-grand/',
                'Эко-Гранд'
            ),
            'meta'     => array(
                'crb_brand'               => 'Эко-Гранд',
                'crb_people_count_text'   => '3–5',
                'crb_daily_volume'        => '1,1',
                'crb_peak_discharge'      => '250',
                'crb_power_consumption'   => '0,9',
                'crb_water_disposal'      => 'самотечный',
                'crb_mounting_dimensions' => '1080 × 1120 × 2525 мм',
            ),
            'seo_title' => 'Эко-Гранд 5 в Чебоксарах — характеристики и установка',
            'seo_desc'  => 'Эко-Гранд 5: самотечное исполнение, параметры и расчёт установки в Чебоксарах. Уточнение комплектации перед заказом.',
        ),
        array(
            'slug'     => 'evrolos-pro-5',
            'title'    => 'Евролос ПРО 5',
            'excerpt'  => 'Компрессорная установка на пять пользователей с комбинированным отводом воды.',
            'content'  => $content(
                'Евролос ПРО 5 рассчитан на бытовой сток объёмом 1 м³ в сутки. В версии 2026 года предусмотрены самотечный и принудительный выходы. Для принудительного отвода нужен дренажный насос, он приобретается отдельно. Версию станции и комплект оборудования сверим с паспортом перед заказом.',
                array('Комбинированное водоотведение', 'Съёмная камера компрессора', 'Насос подбирается отдельно'),
                array(
                    'Исполнение'               => 'версия 2026 года, комбинированный отвод',
                    'Пользователи'             => 'до 5',
                    'Переработка стоков'       => '1 м³/сут',
                    'Пиковая нагрузка'         => '440 л/ч',
                    'Глубина подключения трубы' => '660 мм от верха горловины до лотка входной трубы',
                    'Масса'                    => '139 кг без насоса',
                    'Корпус'                   => 'монолитный полипропилен',
                    'Водоотведение'            => 'самотёком или насосом (насос приобретается отдельно)',
                    'Обслуживание'             => 'ежегодно — эрлифты и фильтры; удаление осадка — не реже раза в 2 года',
                ),
                'https://eurolos.ru/assets/files/passport/eurolos-pro-passport.pdf',
                '/septik-evrolos/',
                'Евролос'
            ),
            'meta'     => array(
                'crb_brand'               => 'Евролос',
                'crb_people_count_text'   => 'до 5',
                'crb_daily_volume'        => '1',
                'crb_water_disposal'      => 'самотёк или насос',
                'crb_mounting_dimensions' => 'диаметр корпуса 1300 мм',
                'crb_manual_pdf'          => 'https://eurolos.ru/assets/files/passport/eurolos-pro-passport.pdf',
            ),
            'seo_title' => 'Евролос ПРО 5 в Чебоксарах — комплектация и монтаж',
            'seo_desc'  => 'Евролос ПРО 5: характеристики версии 2026, варианты отвода воды и расчёт монтажа. Уточнение насоса и комплектации.',
        ),
        array(
            'slug'     => 'ital-bio-5',
            'title'    => 'Итал Био 5',
            'excerpt'  => 'Станция на пять пользователей с самотечным отводом и опцией насоса.',
            'content'  => $content(
                'Итал Био 5 очищает до 1000 л бытовых стоков в сутки и рассчитана на залповый сброс до 315 л. В базовом варианте вода отводится самотёком. Для другой схемы отвода подбирают дополнительное оборудование. Стандарт, MIDI и LONG различаются высотой и глубиной подключения.',
                array('Корпус из полиэтилена', 'Самотёк с возможностью насосного отвода', 'Обслуживание по регламенту раз в год'),
                array(
                    'Исполнение'               => 'Стандарт, самотечное; насос — опция',
                    'Пользователи'             => 'до 5',
                    'Переработка стоков'       => '1 м³/сут',
                    'Залповый сброс'           => '315 л',
                    'Глубина подключения трубы' => '930 мм от верха крышки до низа трубы',
                    'Масса'                    => '149 кг',
                    'Мощность'                 => '65 Вт',
                    'Корпус'                   => 'первичный полиэтилен',
                    'Гарантия производителя'   => 'корпус 10 лет, компрессор 1 год',
                    'Обслуживание'             => 'раз в 12 месяцев',
                ),
                'https://ital-spb.ru/ital_bio_5/',
                '/septik-ital-bio/',
                'Итал Био'
            ),
            'meta'     => array(
                'crb_brand'               => 'Итал Био',
                'crb_people_count_text'   => 'до 5',
                'crb_daily_volume'        => '1',
                'crb_peak_discharge'      => '315',
                'crb_water_disposal'      => 'самотечный',
                'crb_mounting_dimensions' => 'диаметр 1170 мм, высота 2280 мм',
                'crb_manual_pdf'          => 'https://ital-spb.ru/files/Pasport-Ital-Bio-2025.pdf',
            ),
            'seo_title' => 'Итал Био 5 в Чебоксарах — параметры и монтаж',
            'seo_desc'  => 'Итал Био 5: характеристики, варианты отвода и расчёт монтажа в Чебоксарах. Подбор стандартного исполнения под участок.',
        ),
        array(
            'slug'     => 'kit-5-500',
            'title'    => 'КИТ 5 (500)',
            'excerpt'  => 'КИТ 5 с подключением трубы 500 мм для бытовых стоков до 1 м³/сут.',
            'content'  => $content(
                'КИТ 5 (500) рассчитан на дом до пяти пользователей при соблюдении расчётного объёма стоков. Исполнение 500 выбирают по точке подключения трубы. Если самотечный отвод невозможен, нужен дополнительный дренажный насос. КИТ ПРО — отдельная модель с другими параметрами.',
                array('Исполнение под конкретную глубину ввода трубы', 'До 1000 л стоков в сутки', 'Насос подбирается отдельно'),
                array(
                    'Исполнение'         => 'КИТ, ввод трубы 500',
                    'Пользователи'       => 'до 5',
                    'Переработка стоков' => '1 м³/сут',
                    'Пиковая нагрузка'   => '265 л/ч',
                    'Ввод трубы'         => '500 мм по паспорту исполнения',
                    'Масса'              => '108 кг',
                    'Корпус'             => 'полипропилен',
                    'Водоотведение'      => 'самотёком; принудительно — с дополнительным насосом',
                ),
                'https://kit-septik.com/kit-5/',
                '/septik-kit/',
                'КИТ'
            ),
            'meta'     => array(
                'crb_brand'               => 'КИТ',
                'crb_people_count_text'   => 'до 5',
                'crb_daily_volume'        => '1',
                'crb_water_disposal'      => 'самотечный',
                'crb_mounting_dimensions' => '1100 × 1100 × 2060 мм',
                'crb_manual_pdf'          => 'https://kit-septik.com/files/Tekhnicheskiy_pasport_SBO_KIT_2.pdf',
            ),
            'seo_title' => 'КИТ 5 (500) в Чебоксарах — подбор и установка',
            'seo_desc'  => 'КИТ 5 (500): характеристики, комплектация и расчёт монтажа. Уточнение подключения и способа отвода воды.',
        ),
        array(
            'slug'     => 'evrobion-art-5-standart',
            'title'    => 'Евробион АРТ 5 Стандарт',
            'excerpt'  => 'Евробион АРТ 5 в исполнении Стандарт с врезкой трубы до 600 мм.',
            'content'  => $content(
                'Евробион АРТ 5 Стандарт предназначен для бытовой канализации дома. Производитель указывает переработку 1000 л в сутки и залповый сброс 320 л. Исполнение Стандарт отличается от Стандарт+, Миди и Лонг высотой и глубиной подключения. Комплектацию отвода воды подбираем под участок.',
                array('Исполнение Стандарт', 'Переработка 1000 л в сутки', 'Выбор высоты в линейке АРТ'),
                array(
                    'Исполнение'          => 'АРТ, Стандарт',
                    'Пользователи'        => '4–6',
                    'Переработка стоков'  => '1 м³/сут',
                    'Залповый сброс'      => '320 л',
                    'Глубина врезки трубы' => 'до 600 мм',
                    'Масса'               => '98 кг',
                    'Водоотведение'       => 'самотёком или насосом',
                ),
                'https://gk-nep.ru/catalog/eurobion/art/5',
                '/septik-evrobion/',
                'Евробион'
            ),
            'meta'     => array(
                'crb_brand'               => 'Евробион',
                'crb_people_count_text'   => '4–6',
                'crb_daily_volume'        => '1',
                'crb_peak_discharge'      => '320',
                'crb_water_disposal'      => 'самотёк или насос',
                'crb_mounting_dimensions' => '1000 × 1000 × 2360 мм',
            ),
            'seo_title' => 'Евробион АРТ 5 Стандарт в Чебоксарах — монтаж',
            'seo_desc'  => 'Евробион АРТ 5 Стандарт: параметры, глубина подключения и расчёт установки. Подбор комплектации для дома.',
        ),
        array(
            'slug'     => 'biodevice-standart-5',
            'title'    => 'Биодевайс Стандарт 5',
            'excerpt'  => 'Станция серии Стандарт для четырёх–пяти пользователей.',
            'content'  => $content(
                'Биодевайс Стандарт 5 рассчитан на переработку 1 м³ бытовых стоков в сутки. Производитель указывает залповый сброс 320 л и вход трубы 600 мм. Серия Стандарт имеет отдельный блок электрики. Комплектацию, схему отвода и условия обслуживания согласуем перед заказом.',
                array('Серия Стандарт', 'Отдельный блок электрики', 'Схема отвода подбирается под участок'),
                array(
                    'Исполнение'         => 'серия Стандарт',
                    'Пользователи'       => '4–5',
                    'Переработка стоков' => '1 м³/сут',
                    'Залповый сброс'     => '320 л',
                    'Вход трубы'         => '600 мм',
                    'Корпус'             => 'вспененный полипропилен',
                    'Водоотведение'      => 'самотёком или насосом',
                ),
                'https://biodevice.ru/product/standart/',
                '/septik-biodevice/',
                'Биодевайс'
            ),
            'meta'     => array(
                'crb_brand'               => 'Биодевайс',
                'crb_people_count_text'   => '4–5',
                'crb_daily_volume'        => '1',
                'crb_peak_discharge'      => '320',
                'crb_water_disposal'      => 'самотёк или насос',
                'crb_mounting_dimensions' => '1200 × 1200 × 1764 мм',
            ),
            'seo_title' => 'Биодевайс Стандарт 5 в Чебоксарах — установка',
            'seo_desc'  => 'Биодевайс Стандарт 5: характеристики и расчёт установки. Уточнение комплектации и глубины подключения под участок.',
        ),
    );
}

function izex_import_brand_stations()
{
    if (get_option('izex_brand_stations_imported')) {
        return;
    }
    if (!function_exists('carbon_set_post_meta') || !current_user_can('edit_posts')) {
        return;
    }

    foreach (izex_brand_stations_data() as $station) {
        if (get_page_by_path($station['slug'], OBJECT, 'stations')) {
            continue;
        }

        $post_id = wp_insert_post(array(
            'post_type'    => 'stations',
            'post_status'  => 'publish',
            'post_name'    => $station['slug'],
            'post_title'   => $station['title'],
            'post_excerpt' => $station['excerpt'],
            'post_content' => $station['content'],
        ), true);
        if (is_wp_error($post_id)) {
            continue;
        }

        foreach ($station['meta'] as $key => $value) {
            carbon_set_post_meta($post_id, $key, $value);
        }
        // Наличие у поставщика не подтверждено — пустое значение вместо галочки.
        update_post_meta($post_id, '_crb_in_stock', '');

        update_post_meta($post_id, 'rank_math_title', $station['seo_title'] . ' %sep% %sitename%');
        update_post_meta($post_id, 'rank_math_description', $station['seo_desc']);
    }

    update_option('izex_brand_stations_imported', 1);
    izex_bump_catalog_cache();
}
add_action('admin_init', 'izex_import_brand_stations');

/**
 * Кэш подбора и сравнения мог собраться до исключения других марок.
 */
function izex_brand_stations_flush_cache()
{
    if (get_option('izex_brand_stations_cache_flushed')) {
        return;
    }
    update_option('izex_brand_stations_cache_flushed', 1, false);
    izex_bump_catalog_cache();
}
add_action('init', 'izex_brand_stations_flush_cache');

/**
 * Разово: ссылки со страниц марок на карточки моделей и отправка адресов в IndexNow.
 * Итог пишется в uploads/izex-brand-links.log.
 */
function izex_brand_pages_link_stations()
{
    if (get_option('izex_brand_pages_linked')) {
        return;
    }
    update_option('izex_brand_pages_linked', 1, false);
    // Запрос может прийти от гостя — без этого kses перечистит разметку всей страницы.
    kses_remove_filters();

    $map = array(
        'septik-unilos-astra' => 'yunilos-astra-5',
        'septik-eko-grand'    => 'eko-grand-5',
        'septik-evrolos'      => 'evrolos-pro-5',
        'septik-ital-bio'     => 'ital-bio-5',
        'septik-kit'          => 'kit-5-500',
        'septik-evrobion'     => 'evrobion-art-5-standart',
        'septik-biodevice'    => 'biodevice-standart-5',
    );

    $log = array();
    $urls = array(get_post_type_archive_link('stations'));
    foreach ($map as $page_slug => $station_slug) {
        $page = get_page_by_path($page_slug, OBJECT, 'page');
        $station = get_page_by_path($station_slug, OBJECT, 'stations');
        if (!$page || !$station || $station->post_status !== 'publish') {
            $log[] = $page_slug . ': нет страницы или карточки';
            continue;
        }
        $urls[] = get_permalink($station);
        $urls[] = get_permalink($page);

        $content = $page->post_content;
        $href = '/stations/' . $station_slug . '/';
        if (strpos($content, $href) !== false) {
            $log[] = $page_slug . ': ссылка уже есть';
            continue;
        }

        $line = "\n" . '<p>Подробная карточка модели: <a href="' . $href . '">' . esc_html($station->post_title) . '</a> — характеристики, паспорт производителя и заявка на расчёт монтажа.</p>';
        $table_end = strpos($content, '</table>');
        $note = $table_end === false ? false : strpos($content, '<p class="landing-note">', $table_end);
        $note_end = $note === false ? false : strpos($content, '</p>', $note);
        if ($note_end !== false) {
            $pos = $note_end + strlen('</p>');
        } elseif ($table_end !== false) {
            $pos = $table_end + strlen('</table>');
        } else {
            $log[] = $page_slug . ': не нашёл таблицу моделей';
            continue;
        }

        $result = wp_update_post(array(
            'ID'           => $page->ID,
            'post_content' => substr($content, 0, $pos) . $line . substr($content, $pos),
        ), true);
        $log[] = $page_slug . ': ' . (is_wp_error($result) ? 'ошибка ' . $result->get_error_message() : 'ссылка добавлена');
    }

    $log[] = 'IndexNow: ' . izex_brand_stations_indexnow($urls);
    file_put_contents(wp_upload_dir()['basedir'] . '/izex-brand-links.log', implode("\n", array_merge($urls, $log)) . "\n");
}
add_action('wp_loaded', 'izex_brand_pages_link_stations');

function izex_brand_pages_fix_wording()
{
    if (get_option('izex_brand_pages_wording_fixed')) {
        return;
    }
    update_option('izex_brand_pages_wording_fixed', 1, false);
    kses_remove_filters();

    foreach (array('septik-eko-grand', 'septik-evrobion', 'septik-biodevice') as $page_slug) {
        $page = get_page_by_path($page_slug, OBJECT, 'page');
        if (!$page) {
            continue;
        }
        $content = str_replace('характеристики, паспорт производителя и заявка', 'характеристики и заявка', $page->post_content);
        if ($content !== $page->post_content) {
            wp_update_post(array('ID' => $page->ID, 'post_content' => $content));
        }
    }
}
add_action('wp_loaded', 'izex_brand_pages_fix_wording');

function izex_brand_stations_indexnow($urls)
{
    if (class_exists('\RankMath\Instant_Indexing\Api')) {
        $api = \RankMath\Instant_Indexing\Api::get();
        if (method_exists($api, 'submit')) {
            return $api->submit($urls, true) ? 'отправлено через Rank Math' : 'Rank Math вернул ошибку';
        }
    }
    return 'модуль IndexNow в Rank Math не найден';
}


/**
 * Разово: фото моделей и паспорта производителей — в нашу медиатеку,
 * ссылки на сайты производителей из карточек убираем. Файлы заранее
 * залиты в uploads/stations/. Итог — в uploads/izex-station-media.log.
 */
function izex_station_media_import()
{
    if (get_option('izex_station_media_imported')) {
        return;
    }
    update_option('izex_station_media_imported', 1, false);

    require_once ABSPATH . 'wp-admin/includes/image.php';
    kses_remove_filters();

    $map = array(
        'yunilos-astra-5'         => array('unilos-astra-5.jpg', 'pasport-unilos-astra.pdf'),
        'eko-grand-5'             => array('eko-grand-5.jpg', 'pasport-eko-grand.pdf'),
        'evrolos-pro-5'           => array('evrolos-pro-5.jpg', 'pasport-evrolos-pro.pdf'),
        'ital-bio-5'              => array('ital-bio-5.jpg', 'pasport-ital-bio.pdf'),
        'kit-5-500'               => array('kit-5.jpg', 'pasport-kit.pdf'),
        'evrobion-art-5-standart' => array('evrobion-art-5.jpg', ''),
        'biodevice-standart-5'    => array('', ''),
    );

    $uploads = wp_upload_dir();
    $dir = $uploads['basedir'] . '/stations/';
    $url = $uploads['baseurl'] . '/stations/';
    $log = array();

    $attach = function ($file, $parent, $title) use ($dir, $url) {
        $path = $dir . $file;
        if (!file_exists($path)) {
            return 0;
        }
        $type = wp_check_filetype($file);
        $id = wp_insert_attachment(array(
            'guid'           => $url . $file,
            'post_mime_type' => $type['type'],
            'post_title'     => $title,
            'post_status'    => 'inherit',
        ), $path, $parent);
        if (!$id || is_wp_error($id)) {
            return 0;
        }
        wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $path));
        return $id;
    };

    foreach ($map as $slug => $files) {
        $station = get_page_by_path($slug, OBJECT, 'stations');
        if (!$station) {
            $log[] = $slug . ': нет карточки';
            continue;
        }
        $title = $station->post_title;

        if ($files[0] && !has_post_thumbnail($station->ID)) {
            $img = $attach($files[0], $station->ID, $title);
            if ($img) {
                update_post_meta($img, '_wp_attachment_image_alt', $title);
                set_post_thumbnail($station->ID, $img);
                $log[] = $slug . ': фото ' . $files[0];
            } else {
                $log[] = $slug . ': нет файла ' . $files[0];
            }
        }

        if ($files[1] && strpos((string) carbon_get_post_meta($station->ID, 'crb_manual_pdf'), $url . $files[1]) === false) {
            $pdf = $attach($files[1], $station->ID, 'Паспорт ' . $title);
            if ($pdf) {
                carbon_set_post_meta($station->ID, 'crb_manual_pdf', wp_get_attachment_url($pdf));
                $log[] = $slug . ': паспорт ' . $files[1];
            } else {
                $log[] = $slug . ': нет файла ' . $files[1];
            }
        } elseif (!$files[1]) {
            $current = (string) carbon_get_post_meta($station->ID, 'crb_manual_pdf');
            if ($current && strpos($current, home_url()) !== 0) {
                carbon_set_post_meta($station->ID, 'crb_manual_pdf', '');
                $log[] = $slug . ': внешний паспорт убран';
            }
        }

        $home = preg_quote(wp_parse_url(home_url(), PHP_URL_HOST), '~');
        $content = preg_replace('~ \(<a href="https?://(?!' . $home . ')[^"]*"[^>]*>источник</a>, (проверено [^)]*)\)~u', ' ($1)', $station->post_content);
        $content = preg_replace('~<a href="https?://(?!' . $home . ')[^"]*"[^>]*>(.*?)</a>~us', '$1', $content);
        if ($content !== $station->post_content) {
            wp_update_post(array('ID' => $station->ID, 'post_content' => $content));
            $log[] = $slug . ': внешние ссылки убраны из описания';
        }
    }

    izex_bump_catalog_cache();
    file_put_contents($uploads['basedir'] . '/izex-station-media.log', implode("
", $log) . "
");
}
add_action('wp_loaded', 'izex_station_media_import');

/**
 * Разово: на страницах марок — фото модели из нашей медиатеки рядом с
 * первым абзацем, упоминания сайтов производителей заменены на «данные
 * производителя». Итог — в uploads/izex-brand-pages-media.log.
 */
function izex_brand_pages_media()
{
    if (get_option('izex_brand_pages_media_done')) {
        return;
    }
    update_option('izex_brand_pages_media_done', 1, false);
    kses_remove_filters();

    $map = array(
        'septik-unilos-astra' => 'yunilos-astra-5',
        'septik-eko-grand'    => 'eko-grand-5',
        'septik-evrolos'      => 'evrolos-pro-5',
        'septik-ital-bio'     => 'ital-bio-5',
        'septik-kit'          => 'kit-5-500',
        'septik-evrobion'     => 'evrobion-art-5-standart',
        'septik-biodevice'    => 'biodevice-standart-5',
    );
    $log = array();

    foreach ($map as $page_slug => $station_slug) {
        $page = get_page_by_path($page_slug, OBJECT, 'page');
        $station = get_page_by_path($station_slug, OBJECT, 'stations');
        if (!$page) {
            $log[] = $page_slug . ': нет страницы';
            continue;
        }
        $content = $page->post_content;

        $content = preg_replace('~На сайте [a-z0-9.-]+\.(?:ru|com)\b~u', 'По данным производителя,', $content);
        $content = preg_replace('~с сайта (?:производителя )?[a-z0-9.-]+\.(?:ru|com)\b~u', 'производителя', $content);

        $thumb = $station ? get_post_thumbnail_id($station->ID) : 0;
        if ($thumb && strpos($content, 'ss-brand-photo') === false) {
            $figure = '<figure class="ss-brand-photo">'
                . wp_get_attachment_image($thumb, 'medium', false, array('alt' => $station->post_title, 'loading' => 'lazy'))
                . '<figcaption><a href="' . esc_url(get_permalink($station)) . '">' . esc_html($station->post_title) . '</a> — характеристики и расчёт монтажа</figcaption>'
                . '</figure>';
            $h2 = strpos($content, 'Что за станция');
            $p_end = $h2 === false ? false : strpos($content, '</p>', $h2);
            if ($p_end !== false) {
                $content = substr($content, 0, $p_end + 4) . "\n" . $figure . substr($content, $p_end + 4);
                $log[] = $page_slug . ': фото ' . $station->post_title;
            } else {
                $log[] = $page_slug . ': не нашёл абзац для фото';
            }
        } elseif (!$thumb) {
            $log[] = $page_slug . ': у модели нет фото';
        }

        if ($content !== $page->post_content) {
            wp_update_post(array('ID' => $page->ID, 'post_content' => $content));
            $log[] = $page_slug . ': сохранено';
        }
    }

    $uploads = wp_upload_dir();
    file_put_contents($uploads['basedir'] . '/izex-brand-pages-media.log', implode("\n", $log) . "\n");
}
add_action('wp_loaded', 'izex_brand_pages_media');
