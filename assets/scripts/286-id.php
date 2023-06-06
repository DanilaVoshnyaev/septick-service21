<?php
$GLOBALS['DONT_SHOW_PAGE_TITLE'] = 1;
$GLOBALS['DONT_SHOW_BREAD_CRUMBS'] = 1;

echo '<h1>' . $PAGE['pretitle'] . '</h1>';
echo ShowBreadCrumbs();