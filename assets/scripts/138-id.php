<?php
$GLOBALS['DONT_SHOW_PAGE_TITLE'] = 1;
$GLOBALS['DONT_SHOW_BREAD_CRUMBS'] = 1;

echo '<h1>' . $PAGE['pretitle'] . '</h1>';
echo ShowBreadCrumbs();

echo '<script src="/template/default/js/114-id.js"></script>';
echo '<link rel="stylesheet" type="text/css" href="/template/default/css/114-id.css">';


$calc = '<div id="calc">
	<div id="calc_border">
		<form id="calc_form">
			<div id="width_wrap"><label>Ширина стены(см) <input type="text" name="width" placeholder="500"></label></div>
			<div id="height_wrap" class="clearfix"><label>Высота стены(см)<br><input type="text" name="height" placeholder="300"></label></div>
			<div class="clear"></div>
			<input type="submit" value="Рассчитать">
		</form>

		<div id="result_wrap">Необходимое количество блоков: <span id="result">[0]</span></div>
		</div>
	<div id="star">*При толщине раствора в кладке 10 мм и толщине стены в 1 блок</div>
</div>';

$PAGE['text'] = str_replace('{{calc}}', $calc, $PAGE['text']);

?>
