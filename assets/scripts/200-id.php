<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$GLOBALS['DONT_SHOW_PAGE_TITLE'] = 1;
$GLOBALS['DONT_SHOW_BREAD_CRUMBS'] = 1;

echo '<h1>' . $PAGE['pretitle'] . '</h1>';
echo ShowBreadCrumbs();
require_once './include/handbook_editor/EditHandbook.php';
?>
<link href="/include/handbook_editor/handbook_editor.css?var=<?=filemtime($_SERVER['DOCUMENT_ROOT'].'/include/handbook_editor/handbook_editor.css')?>" rel="stylesheet" type="text/css"/>
<script src="/include/handbook_editor/handbook_editor.js?var=<?=filemtime($_SERVER['DOCUMENT_ROOT'].'/include/handbook_editor/handbook_editor.js')?>" type="text/javascript"></script>
<?php

$table = 'items';
$columns = [];
$columns[] = ['title' => 'title', 'title_rus' => 'Название', 'type' => "text", 'required' => true];
$columns[] = ['title' => 'type', 'title_rus' => 'Раздел (Бетон, раствор и тд.)', 'type' => "text", 'required' => true];
$columns[] = ['title' => 'price_nds_summer', 'title_rus' => 'Цена с НДС Летняя', 'type' => "number", 'required' => true];
$columns[] = ['title' => 'price_nds_winter', 'title_rus' => 'Цена с НДС Зимняя', 'type' => "number", 'required' => true];
$columns[] = ['title' => 'price_no_nds_summer', 'title_rus' => 'Цена без НДС Летняя', 'type' => "number", 'required' => true];
$columns[] = ['title' => 'price_no_nds_winter', 'title_rus' => 'Цена без НДС Зимняя', 'type' => "number", 'required' => true];

$columns[] = ['title' => 'only_cash', 'title_rus' => 'Только наличными', 'type' => "checkbox"];

$columns[] = ['title' => 'sort_id', 'title_rus' => 'Приоритет показа', 'type' => "number"];
$columns[] = ['title' => 'update_at', 'title_rus' => 'Последнее редактирование', 'type' => "info"];



$edit = new EditHandbook($dbs, $table, $columns);
$edit->showForm();
