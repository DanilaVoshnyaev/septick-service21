<?php
function GetSiteMap($id=0){
    GLOBAL $PAGE;

    $childrens = GetChildrens($id);
    $print = '<ul id="sitemap">';
    foreach ($childrens as $child) {
        $url = '';
        $url = GetURL($child['id']);
        
        if ($url != $PAGE['url']) {
            $print.='<li><a href="' . $url . '" title="' . $child['title'] . '">' . $child['title'] . '</a>';
        } else {
            $print.='<li class="current">' . $child['title'] . '';
        }
        
        
        $print .= GetSiteMap($child['id']);
        $print.='</li>';
    }
    $print.='</ul>';
    return $print;
}



$PAGE['text'] =  GetSiteMap();





?>