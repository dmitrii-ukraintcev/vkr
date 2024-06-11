<div class="container my-5">
    <?php if (!empty($posts)) : ?>
        <ul>
            <?php foreach ($posts as $post) : ?>
                <li>
                    <h2><a href="<?= $post->url ?>"><?= $post->title ?></a></h2>
                    <!-- <p><?= htmlspecialchars($post->excerpt) ?></p> -->
                    <p>Categories:
                        <?php if (!empty($post->categories)) : ?>
                    <ul>
                        <?php foreach ($post->categories as $category) : ?>
                            <li><a href="<?= $category->url ?>"><?= $category->title ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    No categories.
                <?php endif; ?>
                </p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No posts found in this category.</p>
    <?php endif; ?>
</div>