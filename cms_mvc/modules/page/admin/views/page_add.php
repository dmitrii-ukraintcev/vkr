<form method="post">

    <input type="hidden" name="action" value="1">

    <label>Название:</label><br>
    <input type="text" name="title" placeholder="Page name"><br><br>

    <label>Содержимое:</label><br>
    <textarea name="content" placeholder="Page content"></textarea><br><br>

    <label>Родительская страница:</label><br>
    <select name="parent_page_id">
        <option value=<?= $pages[0]->id ?>>Нет</option>
        <?php array_shift($pages);
        foreach ($pages as $c) { ?>
            <option value=<?= $c->id ?>><?= $c->title ?></option>
        <?php } ?>
    </select><br><br>

    <input type="submit" value="Добавить">

</form>