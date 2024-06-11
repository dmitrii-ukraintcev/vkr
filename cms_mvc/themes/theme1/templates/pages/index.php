<?php include THEMES_PATH . $selected_theme . '/templates/parts/header.php'; ?>

<div class="container my-5">
    <h1><?php echo $page->title; ?></h1>
    <?php echo $page->content; ?>
</div>

<?php include THEMES_PATH . $selected_theme . '/templates/parts/footer.php'; ?>