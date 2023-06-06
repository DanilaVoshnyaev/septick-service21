<?php

$PGAE['anti-spam']['part1'] = rand(1, 9);
$PGAE['anti-spam']['part2'] = rand(11, 45);
$PGAE['anti-spam']['hash'] = md5(($PGAE['anti-spam']['part1'] + $PGAE['anti-spam']['part2']) . 'izex-anti-spam');

function GetLink($id = FALSE, $title = '')
{ //use
    global $PAGE;
    if ($id === false) {
        return '';
    }
    if ($title == '') {
        $title = GetPageTitle($id);
    }
    if ($id == $PAGE['id']) {
        return '<span class="current">' . $title . '</span>';
    } else {
        return '<a href="' . GetURL($id) . '" title="' . $title . '">' . $title . '</a>';
    }
}

function GetSidebarMenu($id = false)
{ //don't know how but it is work
    global $PAGE;
    if ($id == false) {
        return '';
    }
    $pages = GetChildrens($id);
    $print = '<ul>';
    foreach ($pages as $page) {
        $print .= '<li>' . GetLink($page['id']);
        $subpages = GetChildrens($page['id']);
        if ($subpages) {
            $print .= '<ul>';
            foreach ($subpages as $subpage) {
                $print .= '<li> ' . GetLink($subpage['id']) . '</li>';
            }
            $print .= '</ul>';
        }
        $print .= '</li>';
    }
    $print .= '</ul>';
    return $print;
}

function GetAdminPanel()
{ //use
    global $PAGE;
    $edit_this_page = '';
    if ($PAGE['template'] == 'default') {
        $edit_this_page = '<a href="' . GetURL(4) . '?id=' . $PAGE['id'] . '" title="Редактировать эту страницу">Редактировать эту страницу</a>';
    }
    return '<div id="admin_panel">
                <a href="' . GetURL(1) . '" title="Главная">Главная</a>
                <a href="' . GetURL(3) . '" title="Панель администрирования">Панель администрирования</a>
                <a href="' . GetURL(199) . '" title="Редактор доставки">!Доставка!</a>
				<a href="' . GetURL(200) . '" title="Редактор продукции">!Продукция!</a>
				<a href="' . GetURL(202) . '" title="Заказы">Заказы</a>
				
                 ' . $edit_this_page . '
                <a href="/beton-price">Цены на бетон</a>
				<span>[#' . $PAGE['id'] . ']</span>
                <a href="?logout=exit">Выход</a>
            </div>';
}

function GetMenu($id = false)
{
    global $PAGE;
    if ($id === false)
        return '';
    $childrens = GetChildrens($id);
    $print = '<ul>';
    foreach ($childrens as $child) {
        $print .= '<li>' . GetLink($child['id']) . '</li>';
    }
    $print .= '</ul>';
    return $print;
}

function GetMainMenu()
{
    global $PAGE;
    $childrens = GetChildrens(0);
    $print = '<ul>';
    foreach ($childrens as $child) {
        $url = '';
        $url = GetURL($child['id']);
        if (!$child['smenu']) {
            continue;
        }
        if ($url != $PAGE['url']) {

            $print .= '<li><a href="' . $url . '" title="' . $child['title'] . '">' . $child['title'] . '</a></li>';
        } else {
            $print .= '<li class="current">' . $child['title'] . '</li>';
        }
    }
    $print .= '</ul>';
    return $print;
}

function ShowBreadCrumbs($current_id = 0, $separator = ' »  ', $homename = 'Главная')
{
    global $PAGE;
    $current_id = $PAGE['id'];
    $print = '';
    $print = $separator . $PAGE['title'];
    MakeBreadCrumbs($current_id, $print, $separator);
    return '<div id="bread_crumbs"><a href="/">' . $homename . '</a>' . $print . '</div>';
}

function MakeBreadCrumbs($current_id, &$print, $separator)
{
    global $PAGE;
    if ($current_id == $PAGE['id']) {
        $print = $separator . $PAGE['title'];
        if ($PAGE['parent_id'])
            MakeBreadCrumbs($PAGE['parent_id'], $print, $separator);
        else
            return true;
    } else {
        $current_page_info = GetInfoById($current_id);
        $print = $separator . '<a href="' . GetURL($current_page_info['id']) . '">' . $current_page_info['title'] . '</a>' . $print;
        if ($current_page_info['parent_id'])
            MakeBreadCrumbs($current_page_info['parent_id'], $print, $separator);
        else
            return true;
    }
}

function ShowBreadCrumbs4ProducsPage($current_id = 0, $separator = ' »  ', $homename = 'Главная')
{
    global $PAGE;
    $current_id = $PAGE['id'];
    $print = '';
    $print = $separator . $PAGE['title'];
    MakeBreadCrumbs4ProducsPage($current_id, $print, $separator);
    return '<div class="bread_crumbs"><a href="/">' . $homename . '</a>' . $print . '</div>';
}

function MakeBreadCrumbs4ProducsPage($current_id, &$print, $separator)
{
    global $PAGE;

    $current_page_info = GetInfoById($current_id);
    $print = $separator . '<a href="' . GetURL($current_page_info['id']) . '">' . $current_page_info['title'] . '</a>' . $print;
    if ($current_page_info['parent_id'])
        MakeBreadCrumbs4ProducsPage($current_page_info['parent_id'], $print, $separator);
    else
        return true;
}

# ========================================================================================================================
# Продукция

function ShowTree($id, $level = 0, $section = 0, &$print)
{
    $prefix = '—';
    $childrens = GetChildrens($id, 'ALL');

    $section = explode(',', $section);
    if ($childrens) {
        $prefix_str = '';
        $level++;
        foreach ($childrens as $child) {
            $prefix_str = '';
            for ($i = 1; $i <= $level; $i++) {
                $prefix_str .= $prefix;
            }
            $selected = '';
            if (array_search($child['id'], $section) !== false) {
                $selected = ' selected="selected"';
            }
            $print .= '<option ' . $selected . '  value="' . $child['id'] . '">|' . $prefix_str . ' ' . $child['title'] . ' (ID:' . $child['id'] . ')</option>';
            ShowTree($child['id'], $level, implode(',', $section), $print);
        }
    }
}

function ShowCatalog($id)
{
    global $db;
    $childrens = GetChildrens($id);;
//    echo '<br>';
//    print_r($childrens);
//    echo '</br>';
    $print = '<section id="production"><div class="inner"><h2>Продукция и услуги</h2><div class="production-block">';
    foreach ($childrens as $children){
        //print_r($children);
        //die();
        $print .= '<a href="' . GetURL($children['id']) . '" class="production-item layzy">
                <div class="production-img" style="background-image: url(/i/beton/2.png);"></div>
                <div class="production-text">
                    <div class="product-title">Бетон</div>
                    <div class="product-price">от ' . GetMinprice() . ' </div>
                    <div class="product-quant"> руб./м<sup>3</sup></div>
                </div>
            </a>';
    }
    $catalog = '
				<section id="production">
    <div class="inner">
        <h2>Продукция и услуги</h2>
        <div class="production-block">
            <a href="' . GetURL(112) . '" class="production-item layzy">
                <div class="production-img" style="background-image: url(/i/beton/2.png);"></div>
                <div class="production-text">
                    <div class="product-title">Бетон</div>
                    <div class="product-price">ЛЕТО-ЗИМА</div>
                </div>
            </a>
           <a href="' . GetURL(175) . '" class="production-item layzy">
                <div class="production-img" style="background-image: url(/i/beton/5.png);"></div>
                <div class="production-text">
                    <div class="product-title">Цементные растворы</div>
                    <div class="product-price">ЛЕТО-ЗИМА</div>
                </div>
            </a>
             <a href="' . GetURL(209) . '" class="production-item layzy">
                <div class="production-img" style="background-image: url(/i/beton/truck.jpg);"></div>
                <div class="production-text">
                    <div class="product-title">ВЗВЕШИВАНИЕ</div>
                    <div class="product-price">ГРУЗОВЫХ АВТОМОБИЛЕЙ</div>
                    <div class="product-quant"></div>
                </div>
            </a>
            <a href="' . GetURL(259) . '" class="production-item layzy">
                <div class="production-img" style="background-image: url(/userfiles/upload/d467a85850088eb51d2fd61756f0d72c.jpg);"></div>
                <div class="production-text">
                    <div class="product-title">Услуги </div>
                    <div class="product-price">автобетононасоса</div>
                    <div class="product-quant"></div>
                </div>
            </a>
            
           <!-- <a href="' . GetURL(116) . '" class="production-item layzy">
                <div class="production-img" style="background-image: url(/i/beton/7.png);"></div>
                <div class="production-text">
                    <div class="product-title">Квартиры</div>
                    <div class="product-price">на продажу</div>
                    <div class="product-quant"></div>
                </div>
            </a>
          
            <a href="' . GetURL(113) . '" class="production-item layzy" style="display:none;">
                <div class="production-img" style="background-image: url(/i/beton/3.png);display:none;"></div>
                <div class="production-text">
                    <div class="product-title">Цемент</div>
                    <div class="product-price">от 5</div>
                    <div class="product-quant">руб. за 1 кг</div>
                </div>
            </a>
            
            
            <a href="' . GetURL(114) . '" class="production-item layzy" style="display:none;">
                <div class="production-img" style="backgroundm-image: url(/i/beton/4.png);display:none;"></div>
                <div class="production-text">
                    <div class="product-title">Керамзитобетонные блоки</div>
                    <div class="product-price">от 30</div>
                    <div class="product-quant"> руб./шт</div>
                </div>
            </a>
                    
            
            <a href="' . GetURL(115) . '" class="production-item layzy" style="display:none;">
                <div class="production-img" style="background-image: url(/i/beton/6.png);"></div>
                <div class="production-text">
                    <div class="product-title">Автоуслуги:</div>
                    <div class="product-price">манипулатор, </div>
                    <div class="product-quant">бетононасос</div>
                </div>
            </a>
            -->
            
            
        </div>
    </div>
</section>

';
    return $catalog;
}

function GetMinprice()
{
    global $db;

    $Minprice = $db->prepare('SELECT `not_add` FROM `cms_beton_price` WHERE `id` = :id');
    $Minprice->bindValue(':id', 1, PDO::PARAM_INT);
    $GLOBALS['request_counter']++;
    $Minprice->execute();

    $pricesArray = $Minprice->fetchAll(PDO::FETCH_ASSOC);


    //var_dump($pricesArray);


    return $pricesArray[0]['not_add'];
}

function SendMail($to = '', $subject = '', $body = '', $attachment = false)
{
    if ($to == '' or $subject == '' or $body == '')
        return false;
    $mail = new PHPMailerLite();
    $mail->IsMail();
    $mail->CharSet = "utf-8";
    $mail->SetFrom('no-reply@' . $_SERVER['HTTP_HOST']);
    $mail->AddAddress($to);
    $mail->Subject = $subject;
    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
    $mail->MsgHTML(NiceEmail($subject, $body));
    if ($attachment)
        $mail->AddAttachment($attachment);
    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function NiceEmail($subject = '', $body = '')
{
    return '<table width="100%" bgcolor="#f1f1f1" cellpadding="0" cellspacing="0" border="0">
    <tbody>
        <tr>
            <td style="padding:40px 0; ">
                <!-- begin main block -->
                <table cellpadding="0" cellspacing="0" width="600" border="0" align="center">
                    <tbody  style="background-color:#fff">
                        <tr>
                            <td style="padding:0px 0; ">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding:20px 0 10px; background-color: f1f1f1;">
                                    <tbody>
                                        <tr>
          <td colspan="2" style="text-align:center;">
                                                <a href="' . (IsHttps() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/" style="display:inline-block;  margin-left: 0; padding-top: 0px;">
                                                 <img src="' . (IsHttps() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/i/logo.png" height="54">
                                                </a>
                                            </td>  
                                            <td colspan="2">
                                                <p style="display:none; width:100%; margin: 0;  padding-top: 0px; color:#ffffff; font-size:26px">
                                                    "Kolpa-San"
                                                </p>
                                            </td>  
                                        </tr>
                                </table>
                             
                               
                                <p style="display:none; padding: 20px 0 0; margin-top: 0; margin-bottom: 5px; border-top: 1px solid #e0e0e0; background: #fff; text-align:center; font-size:20px; 
                                line-height:1.4;  color:#333333;">Здравствуйте! </p>
                                    <p style="display:none; margin: 0; font-size:17px; color:#333333; background: #fff;  text-align:center; line-height:1.4;">' . $subject . '<p>
                             
                                <!-- begin wrapper -->
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tbody style="background-color:#fff">
                                      
                                        <tr>
                                            <td colspan="3" rowspan="3" bgcolor="#FFFFFF" style="padding:0 30px 30px;">
                                                <!-- begin content -->
                                                <p style="margin:0; font-size:15px; line-height:1.5; color:#333;">
                                               ' . $body . '
                                                </p>
                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <tbody style="background-color:#fff">
                                                <tr valign="top">
                                                            <td style="padding:0 30px 30px;">
                                                                <p style="color:#666; font-size:13px;margin-top:40px;">
                                            Сайт: <a href="' . (IsHttps() ? 'https' : 'http') . '://' . $_SERVER["HTTP_HOST"] . '">www.' . $_SERVER["HTTP_HOST"] . '</a><br>
                                            E-mail: <a href="mailto:prodtorgservis21@mail.ru">prodtorgservis21@mail.ru</a><br>
                                            © ' . date('Y') . ' "Продторгсервис" - это производство высококачественного бетона, строительного раствора ориентировочной производительностью 400 кубометров в смену.<br> Все права защищены.
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <!-- end content --> 
                                            </td>
                                    
                                    </tbody>
                                </table>
                                <!-- end wrapper-->
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- end main block -->
            </td>
        </tr>
    </tbody>
</table>';
}

function GetBasket($positions)
{
    $print = '<b>Продукция:</b><br>';
    foreach ($positions as $item) {
        $print .= '<div class="position"> - ' . $item['type_item'] . ' ' . $item['title'] . ' (' . $item['price_period'] . '). ' . $item['qty'] . ' м<sup>3</sup>. 
				 Сумма ' . (nf($item['qty'] * $item['price'])) . ' ₽ (' . $item['price_type'] . ')</div>';
    }
    return $print;
}

function nf($number)
{
    return number_format($number, 0, '', ' ');
}

function TranslateDeliveryTypes($delivery_method)
{
    switch ($delivery_method) {
        case 'pickup':
            return 'Самовывоз';
        case 'company':
            return 'Компанией';
        default :
            return $delivery_method;
    }
}

function GetProductByType($type = FALSE,$noQtyBlock = false)
{
    global $dbs;
//    if ($type == FALSE) {
//        $type = '';
//    }
    // $types = $dbs->selectCol('SELECT DISTINCT type FROM ?_items WHERE deleted = 0 AND type !="" ORDER BY sort_id DESC, type, id DESC');
//echo '<pre style="font-size:12px; color: green">' . print_r($types, 1) . '</pre>';
    $items = $dbs->select('SELECT * FROM ?_items WHERE deleted = 0 AND type = ? ORDER BY sort_id DESC, title, id DESC', $type);

    $checkbox_block = '<div class="checkbox_item"><div class="check_ball"></div></div>';
    $qty_block = !$noQtyBlock ? '<div class="quantity_block">
                                        <div class="quantity_btn minus">-</div>
                                        <input type="number" class="quantity_val" min="0" value="0">
                                        <div class="quantity_btn plus">+</div></div>' : '';
    $print = '<h2 class="product-type">' . $type . '</h2>
	<table class="price_list standart_table ">
		<thead>
			<tr>
<!--				<th rowspan="2">Наименование</th>-->
				<th rowspan="2">Марка</th>
				<th colspan="2">С НДС, ЦЕНА ЗА 1 КУБ. М.</th>
				<th colspan="2">Без НДС, ЦЕНА ЗА 1 КУБ. М.</th>
			</tr>
			<tr>
				<th class="leto">Лето</th>
				<th class="zima">Зима</th>
				<th class="leto">Лето</th>
				<th class="zima">Зима</th>
			</tr>
		</thead>
		<tbody>';


    if (!$items) {
        return '';
    }

    foreach ($items as $key => $item) {

        $print .= '<tr>';
        if ($key === 0) {

        }

        $print .= '<td class="marka"><span class="item_title" data-id="' . $item['id'] . '">' . $item['title'] . '</span></td>
					<td><div class="action" data-price_type="С НДС" data-price_period="Лето" data-type_item="' . $type . '" data-price_column="price_nds_summer"><span class="price">' . nf($item['price_nds_summer']) . '</span>  ' . $qty_block . '' . $checkbox_block . '</div></td>
					<td><div class="action" data-price_type="С НДС" data-price_period="Зима" data-type_item="' . $type . '" data-price_column="price_nds_winter"><span class="price">' . nf($item['price_nds_winter']) . '</span>  ' . $qty_block . '' . $checkbox_block . '</div></td>
					<td><div class="action" data-price_type="Без НДС" data-price_period="Лето" data-type_item="' . $type . '" data-price_column="price_no_nds_summer"><span class="price">' . nf($item['price_no_nds_summer']) . '</span>  ' . $qty_block . '' . $checkbox_block . '</div></td>
					<td><div class="action" data-price_type="Без НДС" data-price_period="Лето" data-type_item="' . $type . '" data-price_column="price_no_nds_winter"><span class="price">' . nf($item['price_no_nds_winter']) . '</span>  ' . $qty_block . '' . $checkbox_block . '</div></td>
				</tr>';
    }

    $print .= '</tbody></table>';


    return $print;
}

function GetProductBasket()
{
    $print = '<div class="order_block_empty">
	<p>Выберите продукцию</p>
</div>

<div class="order_block">
	<div class="positions_title">Вы выбрали: </div>
        
        <ol class="positions"></ol>
	<br>
	<div class="total_wrap">
		<span class="total_title">ИТОГО:</span>
		<span class="total_val">0</span>
		<span class="total_currency">₽</span>
	</div>
	<br>
	<div class="delivery_cost_info"> *Цена указана без учёта доставки)<br> Стоимость доставки вам рассчитает наш оператор.<br>
		<span class="to_delivery_table">В таблице ниже</span> указана стоимость доставки до опредённых населённых пунктов</div>
		<div class="order_button">Заказать <div class="order_button_info"><span class="qty_items"></span> <span class="morhp_items"></span> на  <span class="total_val"></span> ₽</div></div>
</div>';
    return $print;
}

function GetOnlineAppBeton() {
    $print = '<div id="product-form">
            <div id="product-form__title">Онлайн-заявка</div>
            <div id="product-form__descr">111Оставьте ваши контактные данные и мы свяжемся с вами в ближайшее время</div>
            <form class="product-form">
                <input name="form_name" type="hidden" value="Онлайн-заявка" />
                <input name="came_from" type="hidden" value="https://betonservis21.ru/produkciya/beton/"  />
                <input name="user_name" type="text" value="" placeholder="Имя" required="required" />
                <input name="user_phone" type="text" value="" placeholder="Контактная информация" required="required" />
                <input type="submit" value="Отправить заявку" />
            </form>
        </div>';
    return $print;
}

function GetOnlineAppCem() {
    $print = '<div id="product-form">
            <div id="product-form__title">Онлайн-заявка</div>
            <div id="product-form__descr">Оставьте ваши контактные данные и мы свяжемся с вами в ближайшее время</div>
            <form class="product-form">
                <input name="form_name" type="hidden" value="Онлайн-заявка" />
                <input name="came_from" type="hidden" value="https://betonservis21.ru/produkciya/cementnie-rastvory/"  />
                <input name="user_name" type="text" value="" placeholder="Имя" required="required" />
                <input name="user_phone" type="text" value="" placeholder="Контактная информация" required="required" />
                <input type="submit" value="Отправить заявку" />
            </form>
        </div>';
    return $print;
}

function GetProductDelivery()
{
    global $dbs;

    $delivery_cities = $dbs->select('SELECT * FROM ?_delivery WHERE deleted = 0 ORDER BY sort_id DESC, locality, id');


    $print = '<table class="standart_table delivery_table">'
        . '<thead><tr><th style="width: 25%">Населённый пункт</th>'
        . '<th>Бетоносмеситель 8 м3</th>'
        . '<th>Бетоносмеситель 6 м3</th>'
        . '<th>Услуги автобетононасоса 22 м</th>'
        . '</tr>'
        . '</thead>'
        . '<tbody>';
    foreach ($delivery_cities as $city) {
        $print .= '<tr>'
            . '<td><span class="locality" data-id="' . $city['id'] . '">' . $city['locality'] . '</span></td>'
            . '<td>' . $city['mixer_8_cubes_price'] . '</td>'
            . '<td>' . $city['mixer_6_cubes_price'] . '</td>'
            . '<td>' . $city['crane_10t_price'] . '</td>'
            . '</tr>';
    }

    $print .= '</tbody></table>';
    return $print;
}

function ReplacementText($text)
{

    $matches = [];
    if (preg_match_all('/\{product\((.*)\)\}/i', $text, $matches)) {

        foreach ($matches[1] as $key => $value) {
            $serach = $matches[0][$key];
            $text = str_replace($serach, GetProductByType($value), $text);
        }
//        $text .= GetProductBasket();
//        $text .= GetProductDelivery();
        return $text;
    } else {
        return $text;
    }
}

function AllReplase($text)
{
    $text = ReplacementText($text);
    $text = str_replace('{basket}', GetProductBasket(), $text);
    $text = str_replace('{delivery}', GetProductDelivery(), $text);
    $text = str_replace('{online-beton}', GetOnlineAppBeton(), $text);
    $text = str_replace('{online-cem}', GetOnlineAppCem(), $text);
    return $text;
}

//Для соц.сетей при "поделиться"
function printOg($search = false, $type = false)
{
    global $PAGE, $dbs;
    //$parentDir = $dbs->selectCol('SELECT dir FROM ?_map WHERE id = ?d',$PAGE['parent_id']);
    $typeName = 'article';
    if ($type) {
        $typeName = $type;
    }

    $ogText = '';
    $ogText .= '<meta property="og:title" content="' . $PAGE['seo_title'] . '"/>';
    $ogText .= '<meta property="og:description" content="' . $PAGE['seo_description'] . '"/>';
    if (isset($PAGE['image']) && $PAGE['image'] != '') {
        $ogText .= '<meta property="og:image" content="' . $PAGE['image'] . '"/>';
        $ogText .= '<meta property="image" content="' . $PAGE['image'] . '"/>';
    } else {
        $ogText .= '<meta property="og:image" content="/i/slide_1.jpg"/>';
        $ogText .= '<meta property="image" content="/i/slide_1.jpg"/>';
    }
    if ($search) {
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode("/", $url);
        $key = array_search($search, $urlParts);
        if ($key === false) {
            $ogText .= '<meta property="og:type" content="website"/>';
        } else {
            $ogText .= '<meta property="og:type" content="' . $typeName . '"/>';
        }
    } else {
        $ogText .= '<meta property="og:type" content="website"/>';
    }
    $ogText .= '<meta property="og:url" content="https://' . $_SERVER['HTTP_HOST'] . '' . $PAGE['url'] . '"/>';
    return $ogText;
}

function printSchema($organizationData = false, $categories = false)
{
    global $PAGE, $dbs;
    $image = 'https://' . $_SERVER['HTTP_HOST'] . '/i/logo.png';
    $date_str = $PAGE['modifed'];
    $date = strtotime($date_str);
    $newDate = Date('Y-m-d', $date);

    $parentDir = $dbs->selectCell('SELECT dir FROM ?_map WHERE id = ?', $PAGE['parent_id']);
    $categories = [['category' => 'staty', 'schemaName' => 'Article'], ['category' => 'produkciya', 'schemaName' => 'Product']];

    $organizationData = [
        'name' => 'Компания «Продторгсервис»',
        'alternateName' => 'Производство бетона и растворов в Чебоксарах',
        'description' => 'Компания «Продторгсервис» г. Чебоксары является одним из лидеров по производству бетона и строительного раствора в Чебоксарах',
        'url' => 'https://' . $_SERVER['HTTP_HOST'] . '',
        'email' => 'prodtorgservis21@mail.ru',
        'legalName' => 'ООО «Продторсервис»',
        'logo' => 'https://' . $_SERVER['HTTP_HOST'] . '/i/logo.png',
        'address' => [
            'addressCountry' => 'RU',
            'addressLocality' => 'Чебоксары',
            'addressRegion' => 'Чувашская Республика',
            'postalCode' => '428000',
            'streetAddress' => 'проезд Ишлейский, 13',
        ],
        'telephone' => '8-8352-507-809',
        'sameAs' => [
            ['socialName' => '', 'socialAddres' => ''],
            ['socialName' => '', 'socialAddres' => ''],
            ['socialName' => '', 'socialAddres' => '']
        ],
    ];

    $schema = '<script type="application/ld+json"> 
        
         {
            "@context": "http://schema.org",
            "@type": "Organization",
            "name" : "' . $organizationData['name'] . '",
            "alternateName": "' . $organizationData['alternateName'] . '",
            "description": "' . $organizationData['description'] . '",
            "url": "' . $organizationData['url'] . '",
            "email": "' . $organizationData['email'] . '",
            "legalName": "' . $organizationData['legalName'] . '",
            "logo": "' . $organizationData['logo'] . '",
            "address": {
            "@type": "PostalAddress",
            "addressCountry": "' . $organizationData['address'][''] . '",
            "addressLocality": "' . $organizationData['address']['addressCountry'] . '",
            "addressRegion": "' . $organizationData['address']['addressRegion'] . '",
            "postalCode": "' . $organizationData['address']['postalCode'] . '",
            "streetAddress": "' . $organizationData['address']['streetAddress'] . '"
            },
            "telephone": "' . $organizationData['name'] . '"
           
        }</script>';

    if ($categories) {
        foreach ($categories as $categori) {
            if ($categori['category'] == $parentDir) {
                switch ($categori['schemaName']) {
                    case 'Article':
                        $schema .= '<script type="application/ld+json">
                            {
                            "@context": "http://schema.org",
                            "@type": "Article",
                            "author": "' . $organizationData['name'] . '",
                            "publisher": {"@type":"Organization","name":"' . $organizationData['name'] . '","logo": {"@type": "ImageObject",
   "url": "' . $organizationData['logo'] . '"}, "url": "' . $organizationData['url'] . '"},
                            "name": "' . $PAGE['seo_title'] . '",
                            "image":"' . $image . '",
                                
                            "datePublished":"' . $newDate . '",
                            "headline":"' . $PAGE['seo_title'] . '",	
                            "description":"' . $PAGE['seo_description'] . '",
                            "url":"' . $_SERVER['REQUEST_URI'] . '"
                        }</script>';
                        break;
                    case 'Product':
                        $schema .= '<script type="application/ld+json">
                        {
                            "@context": "http://schema.org",
                            "@type": "Product",
                            "author": "' . $organizationData['name'] . '",
                            "name": "' . $PAGE['seo_title'] . '",
                            "image":"' . $image . '",
                            "description":"",
                            "url":""
                        }</script>';
                        break;
                }
            }

        }
    }

    return $schema;
}

function ep($mixed)
{
    return '<pre>' . print_r($mixed, 1) . '</pre>';
}

// Галерейка:
function GetPageGalleryImages($id = NULL) {
    global $dbs, $PAGE;
    $page_id = $id ?? $PAGE['id'];
    $images = $dbs->select('SELECT `id`, `image` FROM ?_page_gallery WHERE page_id = ?d AND deleted = 0 ORDER BY sort', $page_id);
    return $images;
}

function ShowPageGallery($id = NULL) {
    global $PAGE;
    $page_id = $id ?? $PAGE['id'];
    $gallery_images = GetPageGalleryImages($page_id);
   // print_r($gallery_images);
    $gallery = '';
    foreach ($gallery_images as $gallery_image) {
        $image_src = '/userfiles/gallery/' . $page_id . '/' . $gallery_image['image'];
        $gallery .= '<div class="gallery_item_wrap" title="Увеличить">'
            . '<div class="gallery_item layzy"  data-gallery_img="' . $image_src . '" data-background="/core/image.php?path=' . $image_src . '&w=544" data-id="' . $gallery_image['id'] . '"></div>'
            . '</div>' . "\n";
    }
    if ($gallery) {
        $PAGE['isset_gallery'] = true;
        return '<div class="gallery_page">' . $gallery . '</div>';
    }
    $PAGE['isset_gallery'] = false;
    return '';
}


function uploadImage($src, $dest, $max_width = 0, $max_height = 0, &$error) {
    $error = 'Нет ошибок';

    if (!function_exists('exif_read_data')) {
        $error = 'function exif_read_data not avialable';
        return false;
    }

    $imageinfo = getimagesize($src);
    if (!$imageinfo) {
        $error = 'error getimagesize()';
        return false;
    }
    $mime = $imageinfo['mime'];
    $format = strtolower(substr($mime, strpos($mime, '/') + 1));
    $funct_imagecreatefrom_format = 'imagecreatefrom' . $format;
    $funct_image_format = 'image' . $format;
    if (!function_exists($funct_imagecreatefrom_format)) {
        $error = 'error ' . $funct_imagecreatefrom_format . '()';
        return false;
    }
    if (!function_exists($funct_image_format)) {
        $error = 'error ' . $funct_image_format . '()';
        return false;
    }


    $original_width = $imageinfo[0];
    $original_height = $imageinfo[1];

    $exif = exif_read_data($src);
    if (!empty($exif['Orientation'])) {
        switch ($exif['Orientation']) {
            // Поворот на 180 градусов
            case 3: {
//                $image = imagerotate($image,180,0);
                break;
            }
            // Поворот вправо на 90 градусов
            case 6: {

                $original_width = $imageinfo[1];
                $original_height = $imageinfo[0];
                //              $image = imagerotate($image,-90,0);
                break;
            }
            // Поворот влево на 90 градусов
            case 8: {
                $original_width = $imageinfo[1];
                $original_height = $imageinfo[0];
                //            $image = imagerotate($image,90,0);
                break;
            }
        }
    }






    $landscape = 0;
    $portrait = 0;
    if ($original_width >= $original_height) {
        $orientation = 'landscape';
        $landscape = 1;
    } else {
        $orientation = 'portrait';
        $portrait = 1;
    }
    $q = 90;
    if ($format == 'png')
        $q = 9;


    if ($max_width == 0 && $max_height == 0) {
        $new_width = $original_width;
        $new_height = $original_height;
    }

    if ($max_width != 0 && $max_height == 0) {
        // ширина задана максимальная
        if ($original_width > $max_width) {
            //считаем пропорционально по ширине.
            // max-w режим (пропорционально, приоритет ширина)
            $new_width = $max_width;
            $new_height = round(($original_height * $max_width) / $original_width);
        } else {
            $new_width = $original_width;
            $new_height = $original_height;
        }
    }
    if ($max_width == 0 && $max_height != 0) {
        // ширина высота максимальная
        if ($original_height > $max_height) {
            //считаем пропорционально по высоте.
            $new_width = round(($original_width * $max_height) / $original_height);
            $new_height = $max_height;
        } else {
            $new_width = $original_width;
            $new_height = $original_height;
        }
    }
    if ($max_width != 0 && $max_height != 0) {
        // ширина высота максимальная
        if ($original_height > $max_height) {
            //считаем пропорционально.
            if ($landscape) {
                // ширина задана максимальная
                if ($original_width > $max_width) {
                    //считаем пропорционально по ширине.
                    // max-w режим (пропорционально, приоритет ширина)
                    $new_width = $max_width;
                    $new_height = round(($original_height * $max_width) / $original_width);
                } else {
                    $new_width = $original_width;
                    $new_height = $original_height;
                }
            } else {
                // ширина высота максимальная
                if ($original_height > $max_height) {
                    //считаем пропорционально по высоте.
                    $new_width = round(($original_width * $max_height) / $original_height);
                    $new_height = $max_height;
                } else {
                    $new_width = $original_width;
                    $new_height = $original_height;
                }
            }
        } else {
            $new_width = $original_width;
            $new_height = $original_height;
        }
    }


    $original_image = $funct_imagecreatefrom_format($src);


    if (!empty($exif['Orientation'])) {
        switch ($exif['Orientation']) {
            // Поворот на 180 градусов
            case 3: {
                $original_image = imagerotate($original_image, 180, 0);
                break;
            }
            // Поворот вправо на 90 градусов
            case 6: {

                $original_image = imagerotate($original_image, -90, 0);
                break;
            }
            // Поворот влево на 90 градусов
            case 8: {
                $original_image = imagerotate($original_image, 90, 0);
                break;
            }
        }
    }


    $new_image = imagecreatetruecolor($new_width, $new_height);
    if ($format == 'png') {
        imagealphablending($original_image, false);
        imagesavealpha($original_image, true);
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
    } else {
        imagefill($new_image, 0, 0, 0xFFFFFF);
    }

    imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
    imageinterlace($new_image, 1);
    $funct_image_format($new_image, $dest, $q);
    imagedestroy($new_image);
    imagedestroy($original_image);
    return true;
}