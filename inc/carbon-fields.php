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
            Field::make('text', 'whatsapp_number', __('WhatsApp (номер)'))
                ->set_attribute('placeholder', '+7 908 303 32 82')
                ->set_help_text('Номер для кнопки WhatsApp. Если пусто — берётся основной телефон.'),
            Field::make('text', 'telegram_username', __('Telegram (username без @)'))
                ->set_attribute('placeholder', 'servis_septik'),
            Field::make('text', 'main_email', __('Email'))->set_attribute('placeholder', 'email')->set_attribute('type', 'email'),
            Field::make('text', 'lead_emails', __('Email(ы) для заявок'))
                ->set_attribute('placeholder', 'zakazchik@mail.ru, manager@mail.ru')
                ->set_help_text('Куда отправлять заявки с форм сайта. Можно указать несколько адресов через запятую.'),
            Field::make('text', 'work_time', __('Режим работы'))->set_attribute('placeholder', 'Режим работы'),
            Field::make('text', 'map_link', __('Ссылка на карту'))->set_attribute('placeholder', 'Ссылка на карту'),
            Field::make('textarea', 'address_text', __('Адрес'))->set_attribute('placeholder', 'Адрес'),
            Field::make('text', 'jd_code', __('ж.д коды'))->set_attribute('placeholder', 'ЖД код'),
            Field::make('complex', 'social_links', __('Социальные сети'))
                ->set_help_text('Иконки выводятся плавающим блоком сбоку сайта.')
                ->add_fields(array(
                    Field::make('text', 'social_caption', 'Название')->set_attribute('placeholder', 'Например: VK, Telegram, MAX')->set_width(40),
                    Field::make('text', 'social_url', 'Ссылка')->set_attribute('placeholder', 'https://...')->set_width(60),
                    Field::make('image', 'social_icon', 'Иконка (SVG/PNG)'),
                )),
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
            Field::make('text', 'operator_name', 'Оператор (наименование)')
                ->set_help_text('Например: ИП Иванов Иван Иванович или ООО «Название». Используется в Политике конфиденциальности.'),
            Field::make('text', 'operator_inn', 'ИНН'),
            Field::make('text', 'operator_ogrn', 'ОГРН / ОГРНИП'),
            Field::make('complex', 'requisites', 'Реквизиты')
                ->add_fields(array(
                    Field::make('text', 'requisite_name', 'Название')->set_attribute('placeholder', 'Название'),
                    Field::make('text', 'requisite_value', 'Значение')->set_attribute('placeholder', 'Значение'),
                )),
        ));

    // ===== Калькулятор подбора и расчёта стоимости =====
    Container::make('theme_options', 'Калькулятор')
        ->set_page_parent($basic_options_container)
        ->add_fields(array(
            Field::make('text', 'crb_calc_install_base', 'Базовый монтаж «под ключ», ₽')
                ->set_attribute('type', 'number')
                ->set_default_value('35000')
                ->set_help_text('Ориентировочная стоимость стандартного монтажа станции «под ключ».')
                ->set_width(50),
            Field::make('text', 'crb_calc_delivery', 'Доставка, ₽')
                ->set_attribute('type', 'number')
                ->set_default_value('0')
                ->set_help_text('0 — доставка включена/бесплатна.')
                ->set_width(50),

            Field::make('text', 'crb_calc_surcharge_forced', 'Надбавка: принудительное водоотведение (насос), ₽')
                ->set_attribute('type', 'number')
                ->set_default_value('15000')
                ->set_width(50),
            Field::make('text', 'crb_calc_surcharge_ugv', 'Надбавка: высокий УГВ (пригруз/якорение), ₽')
                ->set_attribute('type', 'number')
                ->set_default_value('10000')
                ->set_width(50),

            Field::make('text', 'crb_calc_surcharge_long', 'Надбавка: удлинённая горловина «Лонг», ₽')
                ->set_attribute('type', 'number')
                ->set_default_value('6000')
                ->set_width(50),
            Field::make('text', 'crb_calc_surcharge_longus', 'Надбавка: «Лонг Ус», ₽')
                ->set_attribute('type', 'number')
                ->set_default_value('12000')
                ->set_width(50),

            Field::make('text', 'crb_calc_soil_coeff', 'Коэффициент сложного грунта, %')
                ->set_attribute('type', 'number')
                ->set_default_value('10')
                ->set_help_text('Расширяет верхнюю границу вилки — учитывает тяжёлый грунт, песок и т.п.')
                ->set_width(50),
            Field::make('text', 'crb_calc_remoteness_coeff', 'Коэффициент удалённости, %')
                ->set_attribute('type', 'number')
                ->set_default_value('10')
                ->set_help_text('Расширяет верхнюю границу вилки — учитывает удалённость объекта.')
                ->set_width(50),

            Field::make('textarea', 'crb_calc_note', 'Примечание под результатом')
                ->set_default_value('Это ориентировочный расчёт. Точная смета — после бесплатного выезда инженера.'),
        ));

    // ===== Страница «Цены» (4.4) =====
    Container::make('theme_options', 'Цены')
        ->set_page_parent($basic_options_container)
        ->add_fields(array(
            Field::make('complex', 'crb_prices_included', 'Входит в стандартный монтаж')
                ->set_help_text('Список пунктов, которые входят в стоимость монтажа «под ключ».')
                ->add_fields(array(
                    Field::make('text', 'item', 'Пункт')->set_attribute('placeholder', 'Например: земляные работы, врезка трубы'),
                )),
            Field::make('complex', 'crb_prices_extra', 'Оплачивается отдельно')
                ->set_help_text('Что не входит в стандартный монтаж и оплачивается дополнительно.')
                ->add_fields(array(
                    Field::make('text', 'item', 'Пункт')->set_attribute('placeholder', 'Например: тяжёлый грунт, песок, длинные траншеи'),
                )),
            Field::make('complex', 'crb_prices_maintenance', 'Прайс на обслуживание')
                ->set_help_text('Стоимость разового сервисного обслуживания по моделям.')
                ->add_fields(array(
                    Field::make('text', 'model', 'Модель')->set_attribute('placeholder', 'ТОПАС 5')->set_width(60),
                    Field::make('text', 'price', 'Цена, ₽')->set_attribute('placeholder', '3500')->set_width(40),
                )),
        ));

    // ===== SEO главной страницы =====
    Container::make('theme_options', 'SEO главной')
        ->set_page_parent($basic_options_container)
        ->add_fields(array(
            Field::make('text', 'home_seo_title', 'SEO Title главной')
                ->set_help_text('Тег &lt;title&gt; главной. Если пусто — «Название сайта — Краткое описание». ~50–60 символов.'),
            Field::make('textarea', 'home_seo_description', 'SEO Description главной')
                ->set_help_text('Meta description главной. ~150–160 символов.'),
        ));

    // ===== SEO-поля для контента (страницы, станции, услуги, записи) =====
    Container::make('post_meta', 'SEO')
        ->where('post_type', 'IN', array('page', 'post', 'stations', 'services'))
        ->set_context('normal')
        ->set_priority('low')
        ->add_fields(array(
            Field::make('text', 'crb_seo_title', 'SEO Title')
                ->set_help_text('Тег &lt;title&gt;. Если пусто — формируется автоматически. ~50–60 символов.'),
            Field::make('textarea', 'crb_seo_description', 'SEO Description')
                ->set_help_text('Meta description. Если пусто — берётся из описания/контента. ~150–160 символов.'),
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
            Field::make('text', 'crb_model_number', 'Номер модели (цифра после ТОПАС)')
                ->set_help_text('Например: 5 — выведется как «ТОПАС 5» и «ТОПАС-С 5»')
                ->set_width(34),
            Field::make('text', 'crb_compressors_topas_s', 'Компрессоров (ТОПАС-С)')
                ->set_default_value('1')
                ->set_width(33),
            Field::make('text', 'crb_compressors_topas', 'Компрессоров (ТОПАС)')
                ->set_default_value('2')
                ->set_width(33),

            Field::make('text', 'crb_price_topas_s', 'Цена ТОПАС-С, ₽')
                ->set_width(50),
            Field::make('text', 'crb_price', 'Цена ТОПАС, ₽')
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
                ->set_width(33),
            Field::make('text', 'crb_water_disposal', 'Способ водоотведения')
                ->set_help_text('Например: самотёк или принудительное')
                ->set_width(33),
            Field::make('text', 'crb_mounting_dimensions', 'Габариты для информации по монтажу')
                ->set_width(33),
        ))
        ->add_tab('🏷️ Статусы', array(
            Field::make('checkbox', 'crb_is_hit', 'Хит продаж'),
            Field::make('checkbox', 'crb_is_new', 'Новинка'),
            Field::make('checkbox', 'crb_in_stock', 'В наличии')
                ->set_default_value(true)
                ->set_help_text('По умолчанию включено — все товары считаются в наличии. Снимите галочку, если товара нет.'),
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

    // ===== Поля выполненных работ (4.3) =====
    Container::make('post_meta', 'Данные работы')
        ->where('post_type', '=', 'works')
        ->add_fields(array(
            Field::make('text', 'crb_work_model', 'Модель станции')
                ->set_attribute('placeholder', 'Например: ТОПАС-С 5 Лонг')
                ->set_width(50),
            Field::make('text', 'crb_work_location', 'Район / населённый пункт')
                ->set_attribute('placeholder', 'Например: Чебоксары, Заволжье')
                ->set_width(50),
            Field::make('image', 'crb_work_before', 'Фото «До» (необязательно)')
                ->set_help_text('Если заполнить оба фото «До/После» — на странице работы покажется сравнение.')
                ->set_width(50),
            Field::make('image', 'crb_work_after', 'Фото «После» (необязательно)')
                ->set_width(50),
            Field::make('media_gallery', 'crb_work_gallery', 'Галерея объекта')
                ->set_type(array('image')),
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

/**
 * Контакты компании из Carbon Fields с запасными значениями.
 * Используется в header.php, footer.php, front-page.php, page-contacts.php
 * вместо захардкоженного массива $company.
 *
 * @return array{phone:string,phone_clean:string,phone_alt:string,email:string,address:string,work_time:string,map_link:string}
 */
function getCompanyContacts()
{
    $defaults = array(
        'phone'       => '8908 303 32 82',
        'phone_clean' => '+79083033282',
        'phone_alt'   => '89373737 700',
        'email'       => 'servis.septik.pro@yandex.ru',
        'address'     => '',
        'work_time'   => 'Пн-Вс: 9:00 - 20:00',
        'map_link'    => '',
        'region'      => 'Чувашии',
        'whatsapp'    => '',
        'telegram'    => '',
    );

    // Собираем плоский список всех номеров из Carbon Fields
    $numbers = array();
    foreach (getCarbonPhones() as $group) {
        if (empty($group['phone_numbers'])) {
            continue;
        }
        foreach ($group['phone_numbers'] as $num) {
            if (!empty($num['phone_number'])) {
                $numbers[] = $num;
            }
        }
    }

    $company = $defaults;

    if (!empty($numbers[0]['phone_number'])) {
        $company['phone'] = $numbers[0]['phone_number'];
        $company['phone_clean'] = !empty($numbers[0]['clear_number'])
            ? $numbers[0]['clear_number']
            : '+' . preg_replace('/[^0-9]/', '', $numbers[0]['phone_number']);
    }
    if (!empty($numbers[1]['phone_number'])) {
        $company['phone_alt'] = $numbers[1]['phone_number'];
    }

    $email = getCarbonEmail();
    if (!empty($email)) {
        $company['email'] = $email;
    }

    $work_time = getCarbonFields('work_time');
    if (!empty($work_time)) {
        $company['work_time'] = $work_time;
    }

    $address = getCarbonAddress();
    if (!empty($address)) {
        $company['address'] = $address;
    }

    $map_link = getCarbonAddressLink();
    if (!empty($map_link)) {
        $company['map_link'] = $map_link;
    }

    // Мессенджеры. WhatsApp по умолчанию — основной телефон (только цифры).
    $wa = getCarbonFields('whatsapp_number');
    $wa_digits = preg_replace('/[^0-9]/', '', $wa ?: $company['phone_clean']);
    $company['whatsapp'] = $wa_digits ? $wa_digits : '';

    $tg = getCarbonFields('telegram_username');
    if (!empty($tg)) {
        $company['telegram'] = ltrim(trim($tg), '@');
    }

    return $company;
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

/**
 * Список email-адресов, на которые отправляются заявки с форм.
 * Берём адреса из настроек темы (поле «Email(ы) для заявок», можно несколько
 * через запятую). Если поле пустое — фолбэк на основной email сайта.
 *
 * @return string[] валидные email-адреса (уникальные)
 */
function getLeadEmails()
{
    $emails = array();

    $raw = getCarbonFields('lead_emails');
    if (!empty($raw)) {
        foreach (preg_split('/[,;\s]+/', $raw) as $email) {
            $email = trim($email);
            if ($email !== '' && is_email($email)) {
                $emails[] = $email;
            }
        }
    }

    // Фолбэк — основной email сайта.
    if (empty($emails)) {
        $main = getCarbonEmail();
        if ($main && is_email($main)) {
            $emails[] = $main;
        }
    }

    return array_values(array_unique($emails));
}

/**
 * Социальные сети для плавающего блока сбоку.
 * Каждый элемент: ['caption' => ..., 'url' => ..., 'icon' => ...].
 * Если в настройках ничего не задано — возвращаем дефолтную ссылку (MAX),
 * чтобы блок не был пустым.
 *
 * @return array<int,array{caption:string,url:string,icon:string}>
 */
function getSocialLinks()
{
    $links = array();
    $raw = getCarbonFields('social_links');

    if (!empty($raw) && is_array($raw)) {
        foreach ($raw as $item) {
            $url = isset($item['social_url']) ? trim($item['social_url']) : '';
            if ($url === '') {
                continue;
            }
            $icon = '';
            if (!empty($item['social_icon'])) {
                // Carbon image возвращает ID вложения — получаем URL.
                $icon = is_numeric($item['social_icon'])
                    ? wp_get_attachment_image_url((int) $item['social_icon'], 'full')
                    : $item['social_icon'];
            }
            $links[] = array(
                'caption' => isset($item['social_caption']) ? $item['social_caption'] : '',
                'url'     => $url,
                'icon'    => $icon ?: '',
            );
        }
    }

    // Фолбэк — текущая ссылка MAX.
    if (empty($links)) {
        $links[] = array(
            'caption' => 'MAX',
            'url'     => 'https://max.ru/u/f9LHodD0cOI_AGyWf9AKcrl72RIFsKRL7vOApMiqwT37En8F81IprazW1ro',
            'icon'    => 'https://maxicons.ru/icons/MAX.svg',
        );
    }

    return $links;
}

/**
 * Печатает плавающий блок соцсетей сбоку сайта.
 */
function printSocialFloat()
{
    $links = getSocialLinks();
    if (empty($links)) {
        return;
    }
    ?>
    <aside class="social-float" aria-label="Мы в соцсетях">
        <button type="button" class="social-float__toggle" aria-label="Свернуть/развернуть соцсети" aria-expanded="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
        </button>
        <div class="social-float__list">
            <?php foreach ($links as $link) : ?>
                <a class="social-float__item" href="<?php echo esc_url($link['url']); ?>" target="_blank" rel="noopener nofollow" aria-label="<?php echo esc_attr($link['caption'] ?: 'Соцсеть'); ?>">
                    <?php if (!empty($link['icon'])) : ?>
                        <img class="social-float__icon" src="<?php echo esc_url($link['icon']); ?>" alt="<?php echo esc_attr($link['caption']); ?>" width="26" height="26" loading="lazy">
                    <?php else : ?>
                        <span class="social-float__letter"><?php echo esc_html(mb_substr($link['caption'] ?: '?', 0, 1)); ?></span>
                    <?php endif; ?>
                    <?php if (!empty($link['caption'])) : ?>
                        <span class="social-float__label"><?php echo esc_html($link['caption']); ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </aside>
    <?php
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

/**
 * В наличии ли станция.
 *
 * По умолчанию ВСЕ товары считаются в наличии: если поле «В наличии»
 * ещё ни разу не сохранялось у записи (старые товары) — возвращаем true.
 * Если поле сохранено, используем его реальное значение, чтобы при
 * необходимости можно было снять галочку и пометить товар как отсутствующий.
 *
 * @param int $post_id
 * @return bool
 */
function station_is_in_stock($post_id)
{
    foreach (array('_crb_in_stock', 'crb_in_stock') as $meta_key) {
        if (metadata_exists('post', $post_id, $meta_key)) {
            return (bool) carbon_get_post_meta($post_id, 'crb_in_stock');
        }
    }
    // Поле никогда не сохранялось — по умолчанию «в наличии».
    return true;
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
