<?php

declare(strict_types=1);

namespace modules\post\models;

use src\Entity;

class PostCategories extends Entity
{
    protected static string $tableName = 'post_categories';
    public int $id;
    public int $post_id;
    public int $category_id;

    protected function initFields() : void
    {
        $this->fields = [
            'post_id',
            'category_id'
        ];
    }
}
