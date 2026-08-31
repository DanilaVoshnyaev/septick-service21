<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$GLOBALS['DONT_SHOW_PAGE_TITLE'] = 1;
$GLOBALS['DONT_SHOW_BREAD_CRUMBS'] = 1;

echo '<h1>' . $PAGE['pretitle'] . '</h1>';
echo ShowBreadCrumbs();
?>

<br><br>
<div id="text">
	<?php
// Все заказы /orders/


	if (isset($_GET['id']) && is_numeric($_GET['id'])) {

		echo '← <a href="' . GetURL($PAGE['id']) . '">Все заказы</a>';

		$order = $dbs->selectRow('SELECT * FROM ?_orders WHERE id = ?d', $_GET['id']);
		if (!empty($order)) {
			$action = $order['action'];
			echo '<h2>Информация о заказе № ' . $_GET['id'] . '</h2>';

			echo '<div style="padding:10px; border: 1px solid #ccc; display:inline-block; margin: 10px 0 30px">';
			echo '<h3>' . $order['action_descr'] . '</h3>';
			echo GetBasket(json_decode($order['items'], 1));
			echo '<br>Итого: <b>' . $order['amount'] . ' руб.</b><br>';
			echo '<br>Способ доставки: ' . TranslateDeliveryTypes($order['delivery_method']) . '<br>';
			if ($order['delivery_method'] === 'company') {
				echo 'Населённый пункт: ' . $dbs->selectCell('SELECT locality FROM ?_delivery WHERE id = ?d', $order['city_id']) . '<br>';
			}
			echo '</div>';
			echo '<br>ФИО: <b>' . $order['user_last_name'] . ' ' . $order['user_name'] . ' ' . $order['user_middle_name'] . '</b><br>';
			echo 'Телефон: <b>' . $order['user_phone'] . '</b><br>';
			//echo 'Email: <b>' . $order['user_email'] . '</b><br>';
			echo 'Комментарий: <b><i>' . $order['user_comment'] . '</i></b><br>';
			echo '<br>Дата заказа: <b>' . date('d.m.Y, H:i', strtotime($order['created'])) . '</b><br>';
		} else {
			echo '<div>Неверный параметр!</div>';
		}
	} else {

		$actions = ['basket'];
		//FILTER  (role, date)
		$filter = '';
//		$search = isset($_GET['search']) && $_GET['search'] != 'undefined' && $_GET['search'] != 'all' ? trim($_GET['search']) : '';
		$start = isset($_GET['start']) && $_GET['start'] != 'undefined' && $_GET['start'] != 'all' ? $_GET['start'] : '';
		$end = isset($_GET['end']) && $_GET['end'] != 'undefined' && $_GET['end'] != 'all' ? $_GET['end'] : '';
		//$start_pay = isset($_GET['start_pay']) && $_GET['start_pay'] != 'undefined' && $_GET['start_pay'] != 'all' ? $_GET['start_pay'] : '';
		//$end_pay = isset($_GET['end_pay']) && $_GET['end_pay'] != 'undefined' && $_GET['end_pay'] != 'all' ? $_GET['end_pay'] : '';
		$action = isset($_GET['action']) && in_array($_GET['action'], $actions) ? $_GET['action'] : '';

//		$search_str = '';
//		if ($search) {
//			$search_query = filter_entry(urldecode($_GET['search']));
//			$search_query = preg_replace('/[^0-9a-zA-Zа-яА-я + -.@]/ui', '', $search);
//			$search_query = str_replace('+', ' ', $search);
//			foreach (explode(' ', $search_query) as $s) {
//				$t_arr[] = ' (`user_name` LIKE "%' . $s . '%" OR `user_phone` LIKE "%' . $s . '%" OR `account_number` LIKE "%' . $s . '%")';
//			}
//			$search_str = ' and (' . implode(' and ', $t_arr) . ') ';
//		}
//		$filter .= $search_str ? $search_str : '';
		$filter .= $start ? ' AND `created` >= "' . $start . ' 00:00"' : '';
		$filter .= $end ? ' AND `created` <= "' . $end . ' 23:59"' : '';
		//$filter .= $start_pay ? ' AND `pay_date` >= "' . $start_pay . ' 00:00"' : '';
		//$filter .= $end_pay ? ' AND `pay_date` <= "' . $end_pay . ' 23:59"' : '';
		$filter .= $action ? ' AND `action` = "' . $action . '"' : '';
		//die($filter);
		?>

		<form id="filter_meter">

			<?php /*
			  <div class="group">
			  <label>Тип заявки</label>
			  <select name="action">
			  <option value="all">Все</option>
			  <?php
			  foreach ($actions as $key => $action_item) {
			  echo '<option value="' . $action_item . '"  ' . ($action && $action === $action_item ? 'selected="selected"' : '') . '>' . TransActions($action_item) . '</option>';
			  }
			  ?>
			  </select>
			  </div>
			  <div class="group">
			  <label>Поиск</label>
			  <input type="text" name="search" value="<?= $search ?>" placeholder="По ФИО, телефону, лиц.счёту">
			  </div>
			  <div class="group_separate"></div>
			 */ ?>

			<div class="group">
				<label>Созданы с:</label><input type="date" name="start" <?= $start ? 'value="' . $start . '"' : '' ?> max="<?= date('Y-m-d') ?>">
			</div>
			<div class="group">
				<label>Созданы по:</label><input type="date" name="end" <?= $end ? 'value="' . $end . '"' : '' ?>  max="<?= date('Y-m-d') ?>">
			</div>
			<?php /*
			  <div class="group_separate"></div>
			  <div class="group">
			  <label>Оплачены с:</label><input type="date" name="start_pay" <?= $start_pay ? 'value="' . $start_pay . '"' : '' ?> max="<?= date('Y-m-d') ?>">
			  </div>
			  <div class="group">
			  <label>Оплачены по:</label><input type="date" name="end_pay" <?= $end_pay ? 'value="' . $end_pay . '"' : '' ?>  max="<?= date('Y-m-d') ?>">
			  </div>
			 */ ?>

			<button type="submit">Искать</button>
		</form>	

		<?php
		$orders_count = $dbs->selectCell('SELECT COUNT(*) FROM ?_orders WHERE deleted = 0 ' . $filter);
		if ($orders_count) {

			$page_limit = 20;
			$pages = ceil($orders_count / $page_limit);
			$page = (isset($_GET['page']) && $_GET['page'] >= 1 AND (int) $_GET['page'] <= $pages) ? (int) $_GET['page'] : 1;

			$orders = $dbs->select('SELECT * FROM ?_orders WHERE deleted = 0 ' . $filter . ' ORDER BY `id` desc LIMIT ' . ($page - 1) * $page_limit . ', ' . $page_limit);
			$total_amount = $dbs->selectCell('SELECT SUM(`amount`) FROM ?_orders WHERE deleted = 0 ' . $filter . ' ORDER BY `id` desc LIMIT ' . ($page - 1) * $page_limit . ', ' . $page_limit);
			;

			echo '<h3>Всего ' . $orders_count . '</h3>';
			echo '<h4>Сумма ' . number_format($total_amount, 2, '.', ' ') . ' руб.</h4>';

			echo '<table id="orders"> <thead><tr> '
			. '<th>№</th> '
			. '<th>Наименование заказа</th> '
			. '<th>Имя</th> '
			. '<th>Телефон</th> '
			//. '<th>Email</th> '
			. '<th>Способ доставки</th> '
			. '<th>Адрес доставки</th> '
			. '<th>Комментарий</th> '
			. '<th>Стоимость</th> '
			. '<th>Создан</th> '
			. '</tr> '
			. '</thead> '
			. '</tbody>';
			foreach ($orders as $order) {
				$online_pay = $order['payment_method'] === 'online';
				echo '<tr> '
				. '<td><a class="more" href="?id=' . $order['id'] . '" title="Подробнее"> ' . $order['id'] . '</a></td>'
				. '<td>' . $order['action_descr'] . '</a></td>'
				. '<td>' . $order['user_last_name'] . ' ' . $order['user_name'] . ' ' . $order['user_middle_name'] . '</td>'
				. '<td>' . $order['user_phone'] . '</td>'
				//. '<td>' . $order['user_email'] . '</td>'
				. '<td>' . TranslateDeliveryTypes($order['delivery_method']) . '</td>'
				. '<td>' . ($order['delivery_method'] === 'company' ? $dbs->selectCell('SELECT locality FROM ?_delivery WHERE id = ?d', $order['city_id']) : '') . '</td>'
				. '<td> ' . ($order['user_comment'] ? '<i style="font-size:12px; background-color: #fff3dc;padding: 3px 7px;">' . $order['user_comment'] . '</i>' : '') . '</td>'
				. '<td>' . nf($order['amount']) . ' руб.' . '</td>'
				. '<td>' . date('d.m.Y, H:i', strtotime($order['created'])) . '</td>'
				. '</tr>';
			}
			echo '<tbody> </table>';

			if ($orders_count > $page_limit) {
				//Paginator

				$filter_params = '';
				$parse_url = parse_url($_SERVER['REQUEST_URI']);
				if (isset($parse_url['query'])) {
					//Убираем page из query
					parse_str($parse_url['query'], $gets);
					if (isset($gets['page'])) {
						unset($gets['page']);
					}
					$filter_params = http_build_query($gets);
				}


				echo '<div class="paginator">';
				for ($i = 1; $i <= $pages; $i++) {
					if ($i == $page) {
						echo ' <span id="current_page">' . $i . '</span>';
					} else {
						echo '<a class="page_number" href="' . '?page=' . $i . '&' . $filter_params . '">' . $i . '</a> ';
					}
				}
				echo '</div>';
			}
		} else {
			echo '<p>Заказов нет</p>';
		}
	}
	?>


</div>

<style>

    #page{
        line-height: 1.5;
    }
    table.order_items th {
        text-align: center;
    }

    .paginator{
        margin-top: 25px;
        margin-left: -3px;
        display: flex;
    }
    .paginator a, .paginator span{
        margin-left: 3px;
        margin-bottom: 3px;
        border: 1px solid #ccc;
        padding: 2px 9px;
    }

    .paginator a:hover, .paginator span{
        background-color: #e7951c;
        color: #fff;
    }

    #wrapper table td a{
        color: #1a3c39;
        text-decoration: underline;
    }

    .page_244 table th,.page_244 table td {
        text-align: left;
        border: 1px solid #ddd;
        padding: 10px;
    }
    th {
        line-height: 1.5;
        padding: 10px;
        font-weight: bold;
        font-size: 12px;
        text-align: left;
    }
    td{
        /*vertical-align: top;*/
        padding: 10px 10px 6px;
        font-size: 12px;
    }



    #wrapper #orders  a{
        font-size: inherit;
		color: #45b8aa;
    }

	#wrapper #orders  a.more{
		padding: 5px 7px;
		background-color: #45b8aa;
		color: #fff;

	}

    .success{
        color: #1ce26b;
    }
    .success:before{
        content: '✓ ';
    }
    .attention{
        color: #e2a51c;
    }
    .error{
        color: #e21c21;
    }
    .attention:before, .error:before{
        content: '! ';
    }



	#filter_meter{
        margin-bottom: 35px;
        display: flex;
        align-items: flex-end;
		padding: 10px 35px 20px;
		background-color: #f9f9f9;
    }

	.group_separate{
		height: 60px;
		margin-right: 10px;
		width: 3px;
		background-color: #ccc;
	}


    .group label{
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
    }

    #filter_meter select,     #filter_meter input{
        height: 35px;
        padding: 2px 10px;
        margin-right: 10px;
		font-size: 14px;
		border: 1px solid #ccc;
    }
	#filter_meter input{
	}

    #filter_meter label{
        font-weight: 400;
    }
	#filter_meter button{
		/*box-shadow: 1px 5px 11px rgba(36, 103, 180, 0.25);*/
		border-radius: 5px;
		padding: 2px 17px;
		height: 35px;
		transition: .3s all;
		border: 1px solid transparent;
		width: fit-content;
		cursor: pointer;
		background-color: #e7951c;
		color: #fff;
	}

	#orders td, #orders th{
		padding: 7px 12px;
		border: 1px solid #ccc;
	}

</style>
