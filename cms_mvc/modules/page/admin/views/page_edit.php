<h3>Page id: <?= $page->id ?></h3>
<hr>
<form method="post">

    <input type="hidden" name="action" value="1">

    <label>Название:</label><br>
    <input type="text" name="title" value="<?= $page->title ?>" placeholder="Название"><br><br>

    <label>Содержимое:</label><br>
    <textarea name="content" placeholder="Содержимое"><?= $page->content ?></textarea><br><br>

    <?php if ($page->id != 1) { ?>
        <label>Родительская страница:</label><br>
        <select name="parent_page_id">
            <option value=<?= $pages[0]->id ?>>Нет (Главная)</option>
            <?php array_shift($pages);
            foreach ($pages as $c) {
                if ($c != $page && !in_array($c, $child_pages)) { ?>
                    <option value=<?= $c->id ?> <?php if ($c->id == $page->parent_page_id) echo 'selected'; ?>><?= $c->title ?></option>
            <?php }
            } ?>
        </select><br><br>
    <?php } ?>

    <input type="submit" value="Обновить">

</form>