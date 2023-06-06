<?php

$GLOBALS['DONT_SHOW_PAGE_TEXT'] = 1;
$GLOBALS['DONT_SHOW_PAGE_TITLE'] = 1;
$GLOBALS['DONT_SHOW_BREAD_CRUMBS'] = 1;
function DelPage($id) {
    GLOBAL $db;
    $request = $db->prepare('UPDATE ' . DB_PREFIX . '_map SET `deleted`=1 WHERE `id`=:id');
    $request->bindValue(':id', $id, PDO::PARAM_INT);
    $request->execute();
    $childrens = GetChildrens($id);
    if (count($childrens)){
        foreach ($childrens as $child){
            DelPage($child['id']);
        }
    }
    return $request;
}
if (isset($_GET['id'])) {
    DelPage($_GET['id']);
    echo '<div id="menu"><a href="' . GetURL(3) . '">Панель управления</a></div>Страница удалена!';
}
?>
 
