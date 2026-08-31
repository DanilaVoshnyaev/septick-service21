<?php
if ($_SERVER['REMOTE_ADDR'] == '77.40.2.115') {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}


$GLOBALS['DONT_SHOW_PAGE_TEXT'] = 1;
$GLOBALS['DONT_SHOW_PAGE_TITLE'] = 1;
if (isset($_POST['edit_page'])) {
    editor_save();
}

function ShowParent($id, $level = 0, $parent_id = 0, $page_id = 0) {
    $prefix = '—';
    $childrens = GetChildrens($id, 'ALL');
    if ($childrens) {
        $prefix_str = '';
        $level++;
        foreach ($childrens as $child) {
            $prefix_str = '';
            for ($i = 1; $i <= $level; $i++) {
                $prefix_str .= $prefix;
            }

            $selected = '';
            if ($child['id'] == $parent_id) {
                $selected = 'selected="selected"';
            }

            $disabled = '';
            if ($child['id'] == $page_id) {
                $disabled = 'disabled="disabled"';
            }

            echo '<option ' . $selected . ' ' . $disabled . ' value="' . $child['id'] . '">|' . $prefix_str . ' ' . $child['title'] . ' (ID:' . $child['id'] . ')</option>';
            if ($disabled) {
                continue;
            }
            ShowParent($child['id'], $level, $parent_id, $page_id);
        }
    }
}

function editor_save() {
    GLOBAL $db;
    if (isset($_POST['id']) and isset($_POST['title']) and isset($_POST['parent_id']) and isset($_POST['pretitle']) and isset($_POST['seo_title']) and isset($_POST['seo_description']) and isset($_POST['seo_keywords']) and isset($_POST['text']) and isset($_POST['dir'])) {
        $request = $db->prepare('UPDATE ' . DB_PREFIX . '_map SET `title`=:title,`parent_id`=:parent_id,`pretitle`=:pretitle,`seo_title`=:seo_title,`seo_description`=:seo_description,	`seo_keywords`=:seo_keywords, `text`=:text, `smenu`=:smenu, `sort_id`=:sort_id,`dir`=:dir WHERE `id`=:id');
        $request->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
        $request->bindValue(':sort_id', $_POST['sort_id'], PDO::PARAM_INT);
        $request->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
        $request->bindValue(':parent_id', $_POST['parent_id'], PDO::PARAM_INT);
        $request->bindValue(':pretitle', $_POST['pretitle'], PDO::PARAM_STR);
        $request->bindValue(':seo_title', $_POST['seo_title'], PDO::PARAM_STR);
        $request->bindValue(':seo_description', $_POST['seo_description'], PDO::PARAM_STR);
        $request->bindValue(':seo_keywords', $_POST['seo_keywords'], PDO::PARAM_STR);
        $request->bindValue(':dir', $_POST['dir'], PDO::PARAM_STR);
        $request->bindValue(':text', $_POST['text'], PDO::PARAM_STR);
        $request->bindValue(':smenu', $_POST['smenu'], PDO::PARAM_INT);
        $request->execute();
        //header('Location: '.$_SERVER['HTTP_REFERER'].'');
        echo 'Страница успешно обновлена!<br> <a href="/admin">Вернуться на панель управления сайтом</a>';
        echo '<script>window.location = "/admin/editor/?id=' . $_POST['id'] . '"</script>';
        exit();
    }
}

function editor() {
    if (isset($_GET['id']) and $_GET['id'] != 0) {
        $id = $_GET['id'];
        $page_data = GetInfoById($id);
        $smenu = '';
        if ($page_data['smenu'] == 1) {
            $smenu = 'checked="checked"';
        }
        ?>
        <div id="page_editor">
            <div id="menu"><a href="<?= GetURL(4); ?>?id=<?= $page_data['id']; ?>">Обновить</a><a href="<?= GetURL(3); ?>">Вернутся к карте сайта</a><a href="<?= GetURL($page_data['id']); ?>">Перейти на страницу</a></div>

            <form method="POST">
                <input type="hidden" name="edit_page" value="14554513455142145" />
                <table>
                    <tbody>
                        <tr><th>Идентификатор страницы</th><td><?= $page_data['id']; ?><input type="hidden" name="id" value="<?= $page_data['id']; ?>" /></td></tr>
                        <tr><th>Заголовок</th><td><input type="text" name="title" value="<?= $page_data['title']; ?>" /></td></tr>
                        <tr><th>Родительский элимент</th><td><select name="parent_id"><?php echo '<option value="0">/ (корень сайта)</option>';
        ShowParent(0, 0, $page_data['parent_id'], $page_data['id']); ?></select></td></tr>
                        <tr><th>Расширенный заголовок</th><td><input type="text" name="pretitle" value="<?= $page_data['pretitle']; ?>" /></td></tr>
                        <tr><th>SEO:title</th><td><input type="text" name="seo_title" value="<?= $page_data['seo_title']; ?>" /></td></tr>
                        <tr><th>SEO:keywords</th><td><input type="text" name="seo_keywords" value="<?= $page_data['seo_keywords']; ?>" /></td></tr>
                        <tr><th>SEO:description</th><td><input type="text" name="seo_description" value="<?= $page_data['seo_description']; ?>" /></td></tr>
                        <tr><th>Директория</th><td><input type="text" name="dir"  value="<?= $page_data['dir']; ?>" /></td></tr>
                        <tr><th>Показывать в главном меню</th><td><input type="checkbox" name="smenu" value="1" <?= $smenu; ?> id="smenu" /> <label for="smenu"></label></td></tr>
                        <tr><th>Порядок сортировки</th><td><input type="text" name="sort_id" pattern="[0-9]{1,}" value="<?= $page_data['sort_id']; ?>"  /></td></tr>
        <?php /* <tr><th>Редирект</th><td><input type="text" name="redirect" value="<?=$page_data['redirect'];?>" /></td></tr> */ ?>
                        <tr><th colspan="2">Текст</th></tr>
                        <tr><td colspan="2"><textarea id="ckeditor" name="text"><?= $page_data['text']; ?></textarea><script type="text/javascript">CKEDITOR.replace('ckeditor');</script></td></tr>
                        <tr><td colspan="2"><input type="submit" value="Сохранить"/></td></tr> 
                    </tbody>
                </table>
            </form>

            <?php
            include_once $_SERVER['DOCUMENT_ROOT'] . '/include/add_gallery_multy.php';
            ?>
        </div>

        <?php
    } else {
        echo 'Ошибка! Не передан идентификатор страницы.';
    }
}

editor();
?>
