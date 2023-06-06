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
$table = 'delivery';
$columns = [];
$columns[] = ['title' => 'locality', 'title_rus' => 'Населённый пункт', 'type' => "text", 'required' => true];
$columns[] = ['title' => 'mixer_8_cubes_price', 'title_rus' => 'Бетоносмеситель 8 м3', 'type' => "number", 'required' => true];
$columns[] = ['title' => 'mixer_6_cubes_price', 'title_rus' => 'Бетоносмеситель 6 м3', 'type' => "number", 'required' => true];
$columns[] = ['title' => 'crane_10t_price', 'title_rus' => 'Услуги автобетононасоса 22 м', 'type' => "number", 'required' => true];
$columns[] = ['title' => 'sort_id', 'title_rus' => 'Приоритет показа', 'type' => "number"];
$columns[] = ['title' => 'update_at', 'title_rus' => 'Последнее редактирование', 'type' => "info"];


$edit = new EditHandbook($dbs, $table, $columns);
$edit->showForm();
