<h6>Список доступных тем</h6>
<hr>

<form method="post">

    <input type="hidden" name="action" value="1">

    <?php foreach ($themes as $theme) { ?>
        <input type="radio" id="<?= $theme ?>" name="selected_theme" value="<?= $theme ?>" <?php if ($theme == $_SESSION['selected_theme']) echo 'checked' ?>>
        <label for="<?= $theme ?>"><?= $theme ?></label><br>
    <?php } ?><br>

    <input type="submit" value="Сохранить">

</form>