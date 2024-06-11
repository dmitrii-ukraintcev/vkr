<?php

namespace modules\category\models;

class CategoryData
{
    public string $title;
    public ?int $parent_category_id;

    public function __construct(string $title, int $parent_category_id = null)
    {
        $this->title = $title;
        $this->parent_category_id = $parent_category_id;
    }
}