<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/core.php';
require $_SERVER['DOCUMENT_ROOT'] . '/scripts/functions.php';
require $_SERVER['DOCUMENT_ROOT'] . '/core/phpmailer.php';


//OLD
if (isset($_POST['ajax_name']) && $_POST['ajax_name'] == 'change_price_beton') {
	GLOBAL $db;
	$request = $db->prepare('UPDATE `cms_beton_price` SET ' . $_POST['column'] . ' = ' . $_POST['new_price'] . ' WHERE `id` =' . $_POST['id'] . '');
	if ($request->execute()) {
		echo 'ok';
	} else {
		echo 'Ой, ошибочка...Обратитесь к создателям сайта';
	}
	exit;
}
//END OLD

$USER = GetUserDataFromSession();

$response['status'] = 'ERROR';
$response['msg'] = 'Неизвестный статус';


$post = $_POST;

if (!isset($post['action'])) {
	$response['msg'] = 'Неизвестная задача';
	PushJson();
}



GLOBAL $dbs;


/////////////////ОБРАБОТКА ЗАЯВОК
if (isset($post['action']) && in_array($post['action'], ['order'])) {
	
	if(!$post['items']){
		PushJson('Не указаны позиции');
	}

	logEvent('Новый заказ ' . $post['action'] . print_r($post, 1), 'orders');

	//PushJson(print_r($post));

	$form_descr = 'Заказ продукции';
	$order['action'] = htmlspecialchars($post['action']);
	$order['action_descr'] = $form_descr;
	$order['user_name'] = htmlspecialchars($post['user_name']);
	$order['user_phone'] = htmlspecialchars($post['user_phone']);
	$order['user_comment'] = htmlspecialchars($post['user_comment']);
	$order['delivery_method'] = htmlspecialchars($post['delivery_method']);
	$order['city_id'] = htmlspecialchars($post['city_id']);

	$order['url'] = $_SERVER['HTTP_REFERER'];
	$order['ip'] = $_SERVER['REMOTE_ADDR'];
	$order['all_data'] = json_encode($post);
	$order['items'] = $post['items'];
	$order['user_id'] = $USER['id'];

	$amount = 0;
	//Проверка на цены
	$items = json_decode($post['items'], 1);
	foreach ($items as $item){
		if(!isset($item['id']) || !isset($item['price_column']) || !isset($item['qty']) || !isset($item['price'])){
			PushJson('У позиции '.$item['title'].' не полные данные');
		}
		// Проверяем цену
		$db_item = $dbs->selectRow('SELECT deleted, ?# as price FROM ?_items WHERE id = ?d AND deleted = 0', $item['price_column'], $item['id']);
		if(!$db_item || $db_item['deleted'] === '1'){
			PushJson('Ошибка, товар '.$item['title'] .' не существует');
		}
		if($db_item['price'] != $item['price']){
			PushJson('Ошибка, у товара '.$item['title'] .' текущая цена = '.$db_item['price'].', не '.$item['price']);
		}
		$amount += $item['sum'];
	}
	$order['amount'] = $amount;
	

	$order_id = $dbs->query('INSERT INTO ?_orders (?#) VALUES (?a)', array_keys($order), array_values($order));
	if (!$order_id) {
		PushJson('Ошибка регистрации заказа...');
	}

$basket = GetBasket($items);
	
//ФОРМИРУЕМ E-MAIL ПИСЬМО МАГАЗИНУ
	$subject = htmlspecialchars($form_descr) . ' на сайте ' . $_SERVER['HTTP_HOST'];
	$message = '<h2>' . $subject . '</h2>';
	$message .= '<b>Имя</b>: ' . htmlspecialchars($post['user_name']) . '<br />';
	$message .= '<b>Телефон</b>: ' . htmlspecialchars($post['user_phone']) . '<br />';
	if (isset($post['user_comment'])) {
		$message .= '<b>Сообщение</b>: ' . (($post['user_comment']) ? htmlspecialchars($post['user_comment']) : '<i>Без комментария</i>') . '<br />';
	}
	$message .= '<br />';
	$message .= isset($basket) ? $basket : '';
	$message .= '<br /><b>Итого:</b>: ' . nf($amount) . ' руб.<br />';
	$message .= '<br />';
	$message .= '<b>Дата и время заказа</b>: ' . date('d.m.Y, H:i') . '<br />';
//	$message .= '<b>IP заказчика</b>: ' . $_SERVER['REMOTE_ADDR'] . '<br />';
	$mail_report = SendMail('prodtorgservis21@mail.ru', $subject, $message);
//	$mail_report = SendMail('jvn6@mail.ru', $subject, $message);
	if (!$mail_report) {
		PushJson('Ошибка при отправке сообщения. Попытайтесь позже или обратитесь к администратору сайта');
	}



	//SendMail('nikolay@izex.org', $subject, $message);
	//ФОРМИРУЕМ E-MAIL ПИСЬМО ЗАКАЗЧИКУ
		if (isset($post['user_email']) && filter_var(trim($post['user_email']), FILTER_VALIDATE_EMAIL)) {

			//При онлайн оплате клиент всё видит на странице после оплаты
//            if ($online_pay) {
			$subject_client = 'Благодарим за заказ на сайте ' . $_SERVER['HTTP_HOST'] . '!';

			$message_client = '<h2>' . $form_descr ? $form_descr : $subject_client . '</h2>';
			$message_client .= '<h3>Ваш заказ:</h3>';
			$message_client .= '<b>ФИО</b>: ' . $post['user_last_name'] . ' ' . $post['user_name'] . ' ' . $post['user_middle_name'] . '<br />';
			$message_client .= '<b>Телефон</b>: ' . htmlspecialchars($post['user_phone']) . '<br />';
			$message_client .= '<b>E-mail</b>: ' . htmlspecialchars($post['user_email']) . '<br />';
			if (isset($post['user_comment'])) {
				$message_client .= '<b>Комментарий</b>: ' . (($post['user_comment']) ? htmlspecialchars($post['user_comment']) : '<i>Без комментария</i>') . '<br />';
			}
			$message_client .= isset($basket) ? $basket : '';
			$message_client .= '<b>Дата и время заказа</b>: ' . date('d.m.Y, H:i') . '<br />';
			$mail_report = SendMail(trim($post['user_email']), $subject_client, $message_client);
//            }
		}


	//$response['status'] = 'OK';
//	PushJson('GREAT! BUT TEST');
	PushJson('Данные успешно отправлены');
}


//Удаление изображения из галереи
if ($post['action'] == 'remove_gallery_img') {
    if (isset($post['image_id'])) {
        $update = $dbs->query('UPDATE ?_page_gallery SET `deleted` = 1  WHERE `id` = ?d', (int) $post['image_id']);
    }
    $response['status'] = 'OK';
    $response['msg'] = 'Изображение удалено';

    //Удаляем картинку с сервера
    $image = $dbs->selectRow('SELECT `image`, `page_id` FROM ?_page_gallery WHERE `id` = ?d', (int) $post['image_id']);
    unlink($_SERVER['DOCUMENT_ROOT'] . '/userfiles/gallery/' . $image['page_id'] . '/' . $image['image']);
    PushJson();
}

//Сортировка изображений у галереи
if ($post['action'] === 'sort_gallery_items') {
    checkAdminAccess(9);
    foreach ($post['gallery_items_id'] as $key => $gallery_item_id) {
        $dbs->query('UPDATE ?_page_gallery SET sort = ?d WHERE id = ?d', $key, $gallery_item_id);
    }
    $response['status'] = 'OK';
    $response['msg'] = 'Успешно';
    PushJson();
}

function checkAdminAccess() {
	GLOBAL $USER;
	if ((int) $USER['access'] < 9) {
		$response['status'] = 'ERROR';
		$response['msg'] = $USER['access'];
		PushJson();
	}
}

function PushJson($msg = false) {
	global $response;
	if ($msg) {
		$response['msg'] = $msg;
	}
	if ($response['status'] === 'ERROR') {
		logEvent($response['msg'] . "\n" . print_r($_POST, 1), 'ajax_errors');
	}
	echo json_encode($response, JSON_UNESCAPED_UNICODE);
	exit;
}

PushJson();

