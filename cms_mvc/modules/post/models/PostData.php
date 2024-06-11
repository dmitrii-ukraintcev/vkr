<?php

namespace modules\post\models;

class PostData
{
    public string $title;
    public string $content;
    public int $author_id;
    public array $categories;

    public function __construct(string $title, string $content, int $author_id, array $categories)
    {
        $this->title = $title;
        $this->content = $content;
        $this->author_id = $author_id;
        $this->categories = $categories;
    }
}