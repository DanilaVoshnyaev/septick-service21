<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$GLOBALS['DONT_SHOW_PAGE_TITLE'] = 1;
$GLOBALS['DONT_SHOW_BREAD_CRUMBS'] = 1;

echo '<h1>' . $PAGE['pretitle'] . '</h1>';
echo ShowBreadCrumbs();



?>


<h2>Прайс</h2>
<?php
$types = $dbs->selectCol('SELECT DISTINCT type FROM ?_items WHERE deleted = 0 AND type !="" ORDER BY sort_id DESC, type, id DESC');
//echo '<pre style="font-size:12px; color: green">' . print_r($types, 1) . '</pre>';
foreach ($types as $type) {
	$items = $dbs->select('SELECT * FROM ?_items WHERE deleted = 0 AND type = ? ORDER BY sort_id DESC, title DESC, id DESC', $type);
	?>
<h2><?=$type?></h2>
	<table class="price_list standart_table">
		<thead>
			<tr>
<!--				<th rowspan="2">Наименование</th>-->
				<th rowspan="2">Марка</th>
				<th colspan="2">С НДС</th>
				<th colspan="2">Без НДС</th>
			</tr>
			<tr>
				<th class="leto">Лето</th>
				<th class="zima">Зима</th>
				<th class="leto">Лето</th>
				<th class="zima">Зима</th>
			</tr>
		</thead>
		<tbody>
                    
			<?php
                        $checkbox_block = '<div class="checkbox_item"><div class="check_ball"></div></div>';
			$qty_block = '<div class="quantity_block">
                                        <div class="quantity_btn minus">-</div>
                                        <input type="number" class="quantity_val" min="0" value="0">
                                        <div class="quantity_btn plus">+</div></div>';
			if (!$items) {
				continue;
			}
			?>
			<?php
			foreach ($items as $key => $item) {
				?>
				<tr>
					<?php if ($key === 0) { ?>
						
					<?php }
					?>
                                    <td class="marka"><span class="item_title" data-id="<?= $item['id'] ?>"><?= $item['title'] ?></span></td>
					<td><div class="action" data-price_type="С НДС" data-price_period="Лето" data-type_item="<?= $type ?>" data-price_column="price_nds_summer"><span class="price"><?= nf($item['price_nds_summer']) . '</span>  ' . $qty_block ?><?=$checkbox_block;?></div></td>
					<td><div class="action" data-price_type="С НДС" data-price_period="Зима" data-type_item="<?= $type ?>" data-price_column="price_nds_winter"><span class="price"><?= nf($item['price_nds_winter']) . '</span>  ' . $qty_block ?><?=$checkbox_block;?></div></td>
					<td><div class="action" data-price_type="Без НДС" data-price_period="Лето" data-type_item="<?= $type ?>" data-price_column="price_no_nds_summer"><span class="price"><?= nf($item['price_no_nds_summer']) . '</span>  ' . $qty_block ?><?=$checkbox_block;?></div></td>
					<td><div class="action" data-price_type="Без НДС" data-price_period="Лето" data-type_item="<?= $type ?>" data-price_column="price_no_nds_winter"><span class="price"><?= nf($item['price_no_nds_winter']) . '</span>  ' . $qty_block ?><?=$checkbox_block;?></div></td>
				</tr>
			<?php }
			?>
		</tbody>
	</table>
	<?php
}
?>

<div class="order_block_empty">
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
</div>

<?php
$delivery_cities = $dbs->select('SELECT * FROM ?_delivery WHERE deleted = 0 ORDER BY sort_id DESC, locality, id');
?>

<h2>Стоимость доставки </h2>
<table class="standart_table delivery_table">
	<thead>
		<tr>
			<th>Населённый пункт 1</th>
			<th>Бетоносмеситель 8 м3</th>
			<th>Бетоносмеситель 6 м3</th>
			<th>Кран-манипулятор 10 т</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($delivery_cities as $city) { ?>
			<tr>
				<td><span class="locality" data-id="<?= $city['id'] ?>"><?= $city['locality'] ?></span></td>
				<td><?= $city['mixer_8_cubes_price'] ?>₽</td>
				<td><?= $city['mixer_6_cubes_price'] ?>₽</td>
				<td><?= $city['crane_10t_price'] ?>₽</td>
			</tr>
		<?php }
		?>
	</tbody>
</table>

