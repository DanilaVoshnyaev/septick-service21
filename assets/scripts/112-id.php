<?php
$GLOBALS['DONT_SHOW_PAGE_TITLE'] = 1;
$GLOBALS['DONT_SHOW_BREAD_CRUMBS'] = 1;

echo '<h1>' . $PAGE['pretitle'] . '</h1>';
echo ShowBreadCrumbs();

$calc = '

<div id="calc_beton">

	<div class="choose_wrap">
		<div class="choose_title">Марка бетона:</div>
		<div id="marka" class="choose_variant">  
			<div id="current_marka_beton"><span>М-100</span> <i class="fa fa-sort-desc"></i></div>
			<div id="marka_beton_wrap">
				<div class="marka_beton clicked">М-100</div>
				<div class="marka_beton">М-150</div>
				<div class="marka_beton">М-200</div>
				<div class="marka_beton">М-250</div>
				<div class="marka_beton">М-300</div>
				<div class="marka_beton">М-350</div>
                                
				<div class="marka_beton">М-200 на гр. щебне</div>
				<div class="marka_beton">М-250 на гр. щебне</div>
				<div class="marka_beton">М-300 на гр. щебне</div>
                                
				<div class="marka_beton">М-350 на гр. щебне w6</div>
				<div class="marka_beton">М-400 на гр. щебне w8</div>
				<div class="marka_beton">М-450 на гр. щебне w8</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="choose_wrap">
		<div class="choose_title">Добавки:</div>
		<div id="add" class="choose_variant">
			<div class="checkbox_wrap">
				<div class="checkbox clicked"><i class="fa fa-check"></i></div>
				<div class="checkbox_title">Без</div>
			</div>  
			<div class="checkbox_wrap">
				<div class="checkbox"><i class="fa fa-check"></i></div>
				<div class="checkbox_title">-10°С</div>
			</div>  
			<div class="checkbox_wrap">
				<div class="checkbox"><i class="fa fa-check"></i></div>
				<div class="checkbox_title">-25°С</div>
			</div>  
		</div>
			<div class="clear"></div>
	</div>
	
	
	<div class="choose_wrap">
		<div class="choose_title">Кол-во бетона (куб):</div>
		<div id="smesitel" class="choose_variant">
				<input id="count_beton" type="number" name="count_beton" min="1" value="6">
		</div>
			<div class="clear"></div>
	</div>
	
	<div id="result">Итого: <span id="result_price"></span></div>
	
	<div id="order_beton">Заказать</div>
</div>
';


$PAGE['text'] = str_replace('{{calc}}', $calc, $PAGE['text']);

$prices = GetBetonPrice();
$prices_beton = json_encode($prices);


?>

<script id="beton_price">
    var beton_price = <?= $prices_beton ?>;
</script>