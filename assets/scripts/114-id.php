<?php

$GLOBALS['DONT_SHOW_PAGE_TITLE'] = 1;
$GLOBALS['DONT_SHOW_BREAD_CRUMBS'] = 1;

echo '<h1>' . $PAGE['pretitle'] . '</h1>';
echo ShowBreadCrumbs();

$calc = '<div id="calc_wrap">
	<div id="calc_text">
		<h2 align="justify">
	КАЛЬКУЛЯТОР РАСЧЕТА БЛОКОВ</h2>
		<p align="justify">
			Часто бывает необходимость перед строительством объекта из керамзитобетонных блоков рассчитать необходимое количество материала. Иначе вы можете переплатить за лишние керамзитобетонные блоки или же, наоборот, приобрести недостаточное их количество.</p>
		<p align="justify">
			Предлагаем вам воспользоваться нашим онлайн-калькулятором для расчета керамзитобетонных блоков. Расчет производится для одной стены, при толщине кладки раствора в 10 мм и толщиной в 1 ряд блоков.
			</p>
			<p align="justify">
			Кроме того, вы всегда можете обратиться к нашим специалистам. Вам помогут произвести расчет керамзитобетонных блоков различного вида, оформят доставку материала и ответят на другие вопросы наши менеджеры. 
			Для этого вы можете воспользоваться формой заявки на сайте или заказать обратный звонок.</p>
	</div>

	<div id="calc">
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
	</div>
	<div class="clear"></div>
</div>';

$PAGE['text'] = str_replace('{{calc}}', $calc, $PAGE['text']);