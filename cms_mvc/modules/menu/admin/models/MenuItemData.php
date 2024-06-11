<?php

namespace modules\menu\admin\models;

class MenuItemData
{
    public string $title;
    public string $url;
    public ?int $parent_menu_item_id;
    public ?int $route_id;

    public function __construct(string $title, string $url, int $parent_menu_item_id = null, int $route_id = null)
    {
        $this->title = $title;
        $this->url = $url;
        $this->parent_menu_item_id = $parent_menu_item_id;
        $this->route_id = $route_id;
    }
}