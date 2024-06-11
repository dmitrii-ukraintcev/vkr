<form method="post">
    <input type="hidden" name="action" value="1">
    <label>Выберите страницы:</label><br>
    <?php foreach ($pages as $c) { ?>
        <input type="checkbox" id="page<?= $c->id ?>" name="page_ids[]" value="<?= $c->id ?>" <?php if (in_array($c->id, $added_page_ids)) echo 'checked disabled'; ?>>
        <label for="page<?= $c->id ?>"><?= $c->title ?></label><br>
    <?php } ?><br>
    <input type="submit" value="Добавить">
</form>