<?php

namespace modules\page\models;

class PageData
{
    public string $title;
    public string $content;
    public int $parent_page_id;

    public function __construct(string $title, string $content, int $parent_page_id)
    {
        $this->title = $title;
        $this->content = $content;
        $this->parent_page_id = $parent_page_id;
    }
}