<form method="post">

    <input type="hidden" name="action" value="1">

    <label>Название:</label><br>
    <input type="text" name="title" value="<?= $post->title ?>" placeholder="Название"><br><br>

    <label>Содержимое:</label><br>
    <textarea name="content" placeholder="Содержимое"><?= $post->content ?></textarea><br><br>

    <label>Категория:</label><br>
    <div class="dropdown">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
            Выбрать
        </button>
        <ul class="dropdown-menu">
            <?php foreach ($categories as $c) { ?>
                <li class="dropdown-item">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="category<?= $c->id ?>" name="categories[]" value="<?= $c->id ?>" <?php if (in_array($c->id, $post_categories)) echo 'checked'; ?>>
                        <label class="form-check-label" for="category<?= $c->id ?>"><?= $c->title ?></label>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div><br>

    <input type="submit" value="Обновить">

</form>