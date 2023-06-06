<?php


error_reporting(E_ALL);
ini_set("display_errors", 0);

$GLOBALS['DONT_SHOW_PAGE_TEXT'] = 1;
$GLOBALS['DONT_SHOW_PAGE_TITLE'] = 1;
function ShowParent($id,$level=0, $parent_id = 0, $page_id = 0 ){
    $prefix='—';
    $childrens = GetChildrens($id, 'ALL');
    if($childrens){
            $prefix_str = '';
            $level++;
            foreach ($childrens as $child){
                $prefix_str = '';
                for($i=1;$i<=$level;$i++){$prefix_str .= $prefix;}
                echo '<option '.$selected.' '.$disabled.' value="'.$child['id'].'">|'.$prefix_str.' '.$child['title'].' (ID:'.$child['id'].')</option>';
                if($disabled){continue;}
                ShowParent($child['id'],$level, $parent_id, $page_id);
            }
    }
}
function editor_save(){
	GLOBAL $db;
	if(isset($_POST['title']) and isset($_POST['parent_id']) and isset($_POST['pretitle']) and isset($_POST['seo_title']) and isset($_POST['seo_description']) and isset($_POST['seo_keywords'])  and isset($_POST['text']) and isset($_POST['dir'])){
                $smenu=0;
                if(@$_POST['smenu']==1){$smenu=1;}
                $request = $db->prepare('INSERT INTO ' . DB_PREFIX . '_map (`title`,`parent_id`,`pretitle`,`seo_title`,`seo_description`,`seo_keywords`,`text`,`dir`,`smenu`,`sort_id`) VALUES(:title,:parent_id,:pretitle,:seo_title,:seo_description,:seo_keywords,:text,:dir,:smenu,:sort_id)');  
		$request->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
		$request->bindValue(':parent_id', $_POST['parent_id'], PDO::PARAM_INT);
		$request->bindValue(':pretitle', $_POST['pretitle'], PDO::PARAM_STR);
		$request->bindValue(':seo_title', $_POST['seo_title'], PDO::PARAM_STR);
		$request->bindValue(':seo_description', $_POST['seo_description'], PDO::PARAM_STR);
		$request->bindValue(':seo_keywords', $_POST['seo_keywords'], PDO::PARAM_STR);
		$request->bindValue(':text', $_POST['text'], PDO::PARAM_STR);
		$request->bindValue(':dir', $_POST['dir'], PDO::PARAM_STR);
		$request->bindValue(':smenu', $smenu, PDO::PARAM_INT);
		$request->bindValue(':sort_id', $_POST['sort_id'], PDO::PARAM_INT);
		$request->execute();
                //header('Location: http://'.$_SERVER['HTTP_HOST'].  GetURL(3));
                echo 'Страница успешно создана!<br> <a href="/admin">Вернуться на панель управления сайтом</a>';
                //echo '<script>window.location = "/admin/"</script>';
                exit();
	}
        
}
function editor(){
?>
    <div id="page_editor">
        <div id="menu"><a href="<?=GetURL(3);?>">Вернутся к карте сайта</a></div>
        <form method="POST">
            <input type="hidden" name="add_page" value="14554513455142145" />
            <table>
                <tbody>
                    <tr><th>Идентификатор страницы</th><td>присваевается автоматически</td></tr>
                    <tr><th>Заголовок</th><td><input type="text" name="title" required="required" value="" /></td></tr>
                    <tr><th>Родительский элимент</th><td><select name="parent_id" required="required"><?php echo '<option value="0">/ (корень сайта)</option>'; ShowParent(0,0); ?></select></td></tr>
                    <tr><th>Расширенный заголовок</th><td><input type="text" name="pretitle" value="" /></td></tr>
                    <tr><th>SEO:title</th><td><input type="text" name="seo_title" value="" /></td></tr>
                    <tr><th>SEO:keywords</th><td><input type="text" name="seo_keywords" value="" /></td></tr>
                    <tr><th>SEO:description</th><td><input type="text" name="seo_description" value="" /></td></tr>
                    <tr><th>Директория</th><td><input type="text" name="dir" required="required"  value="" /></td></tr>
                    <tr><th>Показывать в главном меню</th><td><input type="checkbox" name="smenu" value="1"  id="smenu" /> <label for="smenu"></label></td></tr>
                    <tr><th>Порядок сортировки</th><td><input type="text" name="sort_id" value="0" required="required" pattern="[0-9]{1,}"  /></td></tr>
                    <?php /*<tr><th>Редирект</th><td><input type="text" name="redirect" value="<?=$page_data['redirect'];?>" /></td></tr> */ ?>
                    <tr><th colspan="2">Текст</th></tr>
                    <tr><td colspan="2"><textarea id="ckeditor" name="text"><?=$page_data['text'];?></textarea><script type="text/javascript">CKEDITOR.replace('ckeditor');</script></td></tr>
                    <tr><td colspan="2"><input type="submit" value="Сохранить"/></td></tr>
                </tbody>
            </table>
        </form>
    </div>

<?php
}
    if(isset($_POST['add_page'])){
        editor_save();
    }
    editor();
?>
