<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

//Основные параметры сайта
add_action('carbon_fields_register_fields', 'crb_attach_theme_options');
function crb_attach_theme_options()
{
    $basic_options_container = Container::make('theme_options', __('Theme Options'))
        ->add_fields(array(
            Field::make('header_scripts', 'crb_header_script', __('Header Script')),
            Field::make('footer_scripts', 'crb_footer_script', __('Footer Script')),
            //Field::make('file', 'pdf_policy', __('Политика конфиденциальности'))->set_type('application/pdf'),
        ));

    Container::make('theme_options', __('Контактная информация'))
        ->set_page_parent($basic_options_container) // reference to a top level container
        ->add_fields(array(
            Field::make('complex', 'theme_phones', __('Телефоны'))
                ->add_fields('phone', array(
                    Field::make('text', 'phone_caption', __('Название'))->set_attribute('placeholder', 'Название'),
                    //Field::make('text', 'phone_number', __('Номер'))->set_attribute('placeholder', '+7 (****) **-**-**')->set_attribute('type', 'tel')
                    Field::make('complex', 'phone_numbers', 'Телефоны')
                        ->add_fields(array(
                            Field::make('text', 'phone_number', 'Телефон')->set_attribute('placeholder', '+7 (****) **-**-**')->set_attribute('type', 'tel'),
                        )),
                )),
            Field::make('text', 'main_email', __('Email'))->set_attribute('placeholder', 'email')->set_attribute('type', 'email'),
            Field::make('text', 'work_time', __('Режим работы'))->set_attribute('placeholder', 'Режим работы'),
            Field::make('text', 'map_link', __('Ссылка на карту'))->set_attribute('placeholder', 'Ссылка на карту'),
            Field::make('textarea', 'address_text', __('Адрес'))->set_attribute('placeholder', 'Адрес'),
            Field::make('text', 'jd_code', __('ж.д коды'))->set_attribute('placeholder', 'ЖД код'),
            // Field::make('textarea', 'map_script', __('Скрипт каты'))->set_attribute('placeholder', 'Скрипт каты'),
//            Field::make('textarea', 'requisites', __('реквизиты'))->set_attribute('placeholder', 'реквизиты'),
        ));

//    Container::make('theme_options', 'Руководители')
//        ->set_page_parent($basic_options_container)
//        ->add_fields(array(
//            // Поля для руководителей
//            Field::make('complex', 'leaders', 'Руководители')
//                ->add_fields(array(
//                    Field::make('text', 'leaders_full_name', 'ФИО')->set_attribute('placeholder', 'ФИО'),
//                    Field::make('text', 'leaders_position', 'Должность')->set_attribute('placeholder', 'Должность'),
//                    Field::make('complex', 'leaders_phones', 'Телефоны')
//                        ->add_fields(array(
//                            Field::make('text', 'leaders_phone', 'Телефон')->set_attribute('placeholder', '+7 (****) **-**-**')->set_attribute('type', 'tel'),
//                        )),
//                )),
//        ));

    Container::make('theme_options', 'Реквизиты')
        ->set_page_parent($basic_options_container)
        ->add_fields(array(
            Field::make('complex', 'requisites', 'Реквизиты')
                ->add_fields(array(
                    Field::make('text', 'requisite_name', 'Название')->set_attribute('placeholder', 'Название'),
                    Field::make('text', 'requisite_value', 'Значение')->set_attribute('placeholder', 'Значение'),
                )),
        ));

    // Register fields for all post types
    Container::make('post_meta', 'Дополнительные поля')
        ->set_context('side') // Display on the right sidebar
        ->add_fields(array(
            Field::make('text', 'sort_order', 'Сортировка')
                ->set_default_value(0)
                ->set_attribute('placeholder', 'Сортировка')
                ->set_attribute('type', 'number'),
        ));

    // Register fields for post type 'slider'
    Container::make('post_meta', 'Настройки слайдера')
        ->where('post_type', '=', 'slider')
        ->add_fields(array(
            Field::make('text', 'button_link', 'Ссылка')->set_attribute('placeholder', 'Ссылка'),
            Field::make('text', 'button_text', 'Текст кнопки')->set_attribute('placeholder', 'Текст кнопки')->set_default_value('Подробнее'),
        ));


    Container::make('post_meta', 'Характеристики станции')
        ->where('post_type', '=', 'stations')
        ->add_tab('📊 Параметры', array(
            Field::make('text', 'crb_price', 'Цена, ₽')
                ->set_width(50),
            Field::make('text', 'crb_old_price', 'Старая цена, ₽')
                ->set_width(50),

            Field::make('text', 'crb_people_count_text', 'Обслуживает')
                ->set_help_text('Например: "до 4 человек"')
                ->set_width(33),
            Field::make('text', 'crb_daily_volume', 'Производительность, м³/сутки')
                ->set_width(33),
            Field::make('text', 'crb_peak_discharge', 'Залповый сброс, л')
                ->set_width(33),

            Field::make('text', 'crb_power_consumption', 'Потребление, кВт/сутки')
                ->set_width(50),
            Field::make('text', 'crb_dimensions', 'Габариты (Д×Ш×В), см')
                ->set_width(50),
        ))
        ->add_tab('🏷️ Статусы', array(
            Field::make('checkbox', 'crb_is_hit', 'Хит продаж'),
            Field::make('checkbox', 'crb_is_new', 'Новинка'),
            Field::make('checkbox', 'crb_in_stock', 'В наличии'),
        ))
        ->add_tab('📐 Комплектация', array(
            Field::make('complex', 'crb_equipment', 'Состав комплекта')
                ->add_fields(array(
                    Field::make('text', 'item_name', 'Наименование'),
                    Field::make('text', 'item_qty', 'Количество')
                        ->set_default_value('1'),
                )),
        ));

    // ========================================
    // 🔧 ПОЛЯ ДЛЯ УСЛУГ (services)
    // ========================================
    // 🔧 ПОЛЯ ДЛЯ УСЛУГ (простой вариант)
    Container::make('post_meta', 'Настройки услуги')
        ->where('post_type', '=', 'services')  // ← исправлено: services, не service1s
        ->add_fields(array(
            Field::make('text', 'service_price', 'Цена (₽)'),
            Field::make('text', 'service_old_price', 'Старая цена (₽)'),
            Field::make('text', 'service_price_montage', 'Цена (₽)'),
            Field::make('text', 'service_old_price_montage', 'Старая цена (₽)'),
            Field::make('text', 'service_duration', 'Срок выполнения'),
            Field::make('text', 'service_warranty', 'Гарантия'),
            Field::make('textarea', 'service_short_desc', 'Краткое описание'),
            Field::make('checkbox', 'service_is_new', 'Новинка'),
            Field::make('checkbox', 'service_is_popular', 'Популярная'),
            Field::make('checkbox', 'service_in_stock', 'В наличии'),
            Field::make('image', 'service_icon', 'Иконка услуги'),
        ));

    Container::make('post_meta', 'Данные отзыва')
        ->where('post_type', '=', 'reviews')
        ->add_fields(array(

            Field::make('text', 'crb_review_author', 'Имя автора')
                ->set_attribute('placeholder', 'Иван Иванов')
                ->set_required(true),

            Field::make('text', 'crb_review_position', 'Должность/Город')
                ->set_attribute('placeholder', 'Например: г. Чебоксары или "Директор ООО"'),

            Field::make('complex', 'crb_review_rating', 'Оценка')
                ->set_layout('tabbed-horizontal')
                ->add_fields(array(
                    Field::make('text', 'rating_value', 'Оценка (1-5)')
                        ->set_attribute('placeholder', '5')
                        ->set_attribute('type', 'number')
                        ->set_attribute('min', '1')
                        ->set_attribute('max', '5')
                        ->set_default_value('5'),
                )),

            Field::make('text', 'crb_review_date', 'Дата услуги')
                ->set_attribute('placeholder', 'Например: Март 2024')
                ->set_attribute('type', 'text'),

            Field::make('image', 'crb_review_avatar', 'Фото автора'),

            Field::make('checkbox', 'crb_review_verified', 'Проверенный покупатель')
                ->set_default_value(true),

            Field::make('text', 'crb_review_service', 'Услуга/Товар')
                ->set_attribute('placeholder', 'Например: Установка ТОПАС-5'),

            Field::make('text', 'crb_review_sort', 'Порядок сортировки')
                ->set_default_value(0)
                ->set_help_text('Чем меньше число — тем выше отзыв'),
        ));
//    // Register fields for post type 'vacancies'
//    Container::make('post_meta', 'Поля вакансий')
//        ->where('post_type', '=', 'vacancies')
//        ->add_fields(array(
//            Field::make('text', 'contact_phone', 'Контактный телефон')->set_default_value('+7 (8352) 50-60-36'),
////            Field::make('rich_text', 'working_conditions', 'Должностные обязанности'),
////            Field::make('rich_text', 'education', 'Образование'),
////            Field::make('rich_text', 'job_responsibilities', 'Условия работы'),
//        ));

}

//// Определение контейнера полей
//add_action('carbon_fields_register_fields', 'crb_define_custom_fields');
//function crb_define_custom_fields()
//{
//    Container::make('post_meta', __('Дополнительные поля'))
////        ->where('post_type', '=', 'post') // Замените на нужный вам тип записи
//        ->add_fields(array(
//            Field::make('text', 'post_sort', __('Сортировка'))->set_attribute('type', 'number')->set_default_value(0),
//            // Добавьте другие поля по аналогии
//        ));
//
//    Container::make('post_meta', __('Дополнительные поля'))
////        ->where('post_type', '=', 'post') // Замените на нужный вам тип записи
//        ->add_fields(array(
//            Field::make('text', 'post_sort', __('Сортировка'))->set_attribute('type', 'number')->set_default_value(0),
//            //Field::make('text', 'post_link', __('Ссылка'))->show_on_post_type('main-sliders'),
//        ));
//}

function getCarbonFields($name = '')
{
    if (!$name) {
        return false;
    }
    return carbon_get_theme_option($name);
}

function updatePhoneFields($phoneFields)
{
    if (empty($phoneFields)) {
        return [];
    }
    foreach ($phoneFields as &$field) {
        unset($field['_type']);
        foreach ($field['phone_numbers'] as &$phone_number) {
            unset($phone_number['_type']);
            $phone_number['clear_number'] = '+' . preg_replace("/[^0-9]/", '', $phone_number['phone_number']);
        }
    }
    return $phoneFields;

}

function getCarbonPhones(array $phoneIndexes = [])
{
    $name = 'theme_phones';
    $fields = getCarbonFields($name);
    if (empty($fields)) {
        return [];
    }
    if (empty($phoneIndexes)) {
        return updatePhoneFields($fields);
    }
    foreach ($phoneIndexes as &$phoneIndex) {
        $phoneIndex = $phoneIndex - 1;
    }
    $array = [];
    foreach ($fields as $key => $field) {
        if (!in_array($key, $phoneIndexes)) {
            continue;
        }
        $array[] = $field;
    }
    return updatePhoneFields($array);
}

function printCarbonPhones(array $phoneIndexes = [], $type = 'single')
{
    $phones = getCarbonPhones($phoneIndexes);
    $text = '';
    if (empty($phones)) {
        return $text;
    }
    foreach ($phones as $phone) {
        $text .= sprintf('<div class="contact contact_type_phone"><div class="contact__title">%s</div>', $phone['phone_caption']);
        if (!empty($phone['phone_numbers'])) {
            foreach ($phone['phone_numbers'] as $key => $phone_number) {
                if ($type == 'single' && $key > 0) {
                    continue;
                }
                $text .= sprintf('<a href = "tel:%s" class="contact__value" >%s</a>', $phone_number['clear_number'], $phone_number['phone_number']);
            }
        }
        $text .= '</div>';
    }
    return $text;
}

function getCarbonEmail()
{
    $name = 'main_email';
    $email = getCarbonFields($name);
    if (empty($email)) {
        return '';
    }
    return $email;
}

function getCarbonRequisites()
{
    $name = 'requisites';
    $requisites = getCarbonFields($name);
    if (empty($requisites)) {
        return '';
    }
    return nl2br($requisites);
}

function getCarbonAddress()
{
    $name = 'address_text';
    $address = getCarbonFields($name);
    if (empty($address)) {
        return '';
    }
    return $address;
}

function getCarbonAddressLink()
{
    $name = 'map_link';
    $addressLink = getCarbonFields($name);
    if (empty($addressLink)) {
        return '';
    }
    return $addressLink;
}

function getCarbonJdCode()
{
    $name = 'jd_code';
    $jd_code = getCarbonFields($name);
    if (empty($jd_code)) {
        return '';
    }
    return $jd_code;
}

function printCarbonLeaders()
{
    $leaders = carbon_get_theme_option('leaders');
    $text = '';
    if (empty($leaders)) {
        return $text;
    }
    foreach ($leaders as $leader) {
        $text .= '<div class="leader">
                        <div class="leader__name">' . $leader['leaders_full_name'] . '</div>
                        <div class="leader__position">' . $leader['leaders_position'] . '</div>';
        if (!empty($leader['leaders_phones'])) {
            foreach ($leader['leaders_phones'] as $leaders_phone) {
                $clearPhone = '+' . preg_replace("/[^0-9]/", '', $leaders_phone['leaders_phone']);
                $text .= '<a href="tel:' . $clearPhone . '" class="leader__phone">' . $leaders_phone['leaders_phone'] . '</a>';
            }
        }
        $text .= '</div>';
    }
    return $text;
}

function printCarbonRequisites()
{
    $text = '';
    $requisites = carbon_get_theme_option('requisites');
    if (empty($requisites)) {
        return $text;
    }
    foreach ($requisites as $requisite) {
        $text .= '<div class="requisite">
                        <span class="requisite__name">' . $requisite['requisite_name'] . '</span>
                        <span class="requisite__value">' . $requisite['requisite_value'] . '</span>
                 </div>';
    }
    return $text;
}

