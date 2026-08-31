<?php
$GLOBALS['DONT_SHOW_PAGE_TEXT'] = 1;
$GLOBALS['DONT_SHOW_PAGE_TITLE'] = 1;
$GLOBALS['DONT_SHOW_BREAD_CRUMBS'] = 1;

echo '<h1>' . $PAGE['pretitle'] . '</h1>';
echo ShowBreadCrumbs();
echo '<div id="text">' . $PAGE['text'] . '</div>';

//function GetMenu($id = false) {
//    GLOBAL $PAGE;
//    if ($id === false)
//        return '';
//    $childrens = GetChildrens($id);
//    $print = '<ul>';
//    foreach ($childrens as $child) {
//        $print .= '<li>' . GetLink($child['id']) . '</li>';
//    }
//    $print .= '</ul>';
//    return $print;
//}
//
//function GetChildrens($id = false, $all = '') {
//	GLOBAL $db;
//	if ($id === false) {
//		return false;
//	}
//	if ($all == 'ALL') {
//		$request = $db->prepare('SELECT * FROM ' . DB_PREFIX . '_map WHERE `parent_id`=:id and `deleted`=0 and `system`=0 ORDER BY `sort_id`');
//		$request->bindValue(':id', $id, PDO::PARAM_INT);
//		$GLOBALS['request_counter'] ++;
//		$request->execute();
//		$map_data = $request->fetchAll(PDO::FETCH_ASSOC);
//	} else {
//		$request = $db->prepare('SELECT * FROM ' . DB_PREFIX . '_map WHERE `parent_id`=:id and `deleted`=0 and `system`=0 and `hidden`=0 ORDER BY `sort_id`');
//		$request->bindValue(':id', $id, PDO::PARAM_INT);
//		$GLOBALS['request_counter'] ++;
//		$request->execute();
//		$map_data = $request->fetchAll(PDO::FETCH_ASSOC);
//	}
//	return $map_data;
//}







function GetArticles() {
    GLOBAL $PAGE;
    GLOBAL $dbs;

    $limit_articles = 10;
    $total_articles = $dbs->selectCell('SELECT COUNT(*) FROM ?_map WHERE parent_id = 117 AND deleted = 0');
    $count_pages = ceil($total_articles / $limit_articles);

    $current_page = '';
    if (isset($_GET['page']) && is_numeric($_GET['page']) && (int) $_GET['page'] > 0) {
        $current_page = (int) $_GET['page'];
    } else {
        $current_page = 1;
    }

    $begin = ($current_page * $limit_articles) - $limit_articles;
    //$request = $dbs->prepare('SELECT * FROM ' . DB_PREFIX . '_map WHERE `parent_id`=:id and `deleted`=0 and `system`=0 ORDER BY `sort_id`');
    //$news = $dbs->select('SELECT * FROM ?_map WHERE parent_id = ?d AND deleted = 0 ORDER BY sort_id LIMIT ?d,?d',$id,$test,$limit_news);

    $articles = $dbs->select('SELECT * FROM ?_map WHERE parent_id = 117 AND deleted = 0 ORDER BY sort_id LIMIT ?d,?d', $begin, $limit_articles);
    //print_r($articles);
    $print = '<div class = "articles_block">';

    foreach ($articles as $article) {
        $print .= '<div class="articles_item">'
                . '<a href="' . GetUrl($article['id']) . '" class="articles_item_title">' . $article['title'] . '</a>'
                . '<div class="articles_item_text">' . $article['seo_description'] . '</div>'
                . '<a href="' . GetUrl($article['id']) . '" class="articles_item_more">Подробнее</a>'
                . '</div>';
    }

    $print .= '</div>';

//echo $print;
    $current_class = '';
    $print .= '<div class="pagination">';
    for ($index = 1; $index <= $count_pages; $index++) {
        $current_class = ($current_page == $index) ? ' current' : '';
        $print .= '<a class="pagination_number' . $current_class . '" href="?page=' . $index . '">' . $index . '</a>';
    }
    $print .= '</div>';

    return $print;
}

echo GetArticles();
?>

<style>


    li{
        padding-bottom: 5px;
    }


</style>


