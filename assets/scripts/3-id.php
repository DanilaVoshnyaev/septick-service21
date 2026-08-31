<?php
$GLOBALS['DONT_SHOW_PAGE_TEXT'] = 1;
$GLOBALS['DONT_SHOW_PAGE_TITLE'] = 1;

function ShowMap($id) {
	global $dbs;
	$childrens = $dbs->select('SELECT * FROM ?_map WHERE `parent_id` = ?d and `deleted`=0  and `system` =0 ORDER BY `id`, `sort_id`', $id);
	if(!$childrens){
		return;
	}
	?>
	<ul>
		<?php
		foreach ($childrens as $child) {
			?>
			<li>
				<div class="title"><?= ($child['class_id'] === '5' ? '<i class="fa fa-circle-o"></i>' : '') ?>
					<a class="page_link" href="<?= getUrl($child['id']) ?>"><?= $child['title'] ?></a>
					<a href="<?= GetURL(4) . '?id=' . $child['id'] ?>" class="green action_button">Редактировать</a>
					<a href="<?= GetURL(5) . '?id=' . $child['id'] ?>" onclick="return confirm(\'ВНИМАНИЕ! Удалить? (не обратимое удаление)\')" class="red action_button">Удалить</a>                    
					<div class="dir"><a href="<?= GetURL($child['id']) ?>"><?= GetURL($child['id']) ?></a> <span class="sort_id">[Порядок сортировки: <?= $child['sort_id'] ?>]</span></div>
				</div>
				<?= ShowMap($child['id']); ?>
			</li>
			<?php
		}
		?>
	</ul>
	<?php
}
?>
<div id="menu"><a href="<?= GetURL(6); ?>">+ Добавить страницу</a></div>
<div id="map"><?= ShowMap(0); ?></div>