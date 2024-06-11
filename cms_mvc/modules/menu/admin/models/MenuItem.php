<?php

namespace modules\menu\admin\models;

use src\Entity;

class MenuItem extends Entity
{
    protected static string $tableName = 'menu_items';
    public int $menu_id;
    public string $title;
    public string $url;
    public ?int $parent_menu_item_id;
    public ?int $route_id;
    public int $order_num;
    public array $child_menu_items = [];

    protected function initFields(): void
    {
        $this->fields = [
            'menu_id',
            'title',
            'url',
            'parent_menu_item_id',
            'route_id',
            'order_num'
        ];
    }

    protected function setFieldValues(array $data, bool $fromDatabase = false): void
    {
        parent::setFieldValues($data);

        if ($fromDatabase) {
            $this->child_menu_items = $this->getChildMenuItems();
        }
    }

    public function getChildMenuItems(): array
    {
        return MenuItem::getAll($this->dbConn, ['parent_menu_item_id' => $this->id]);
    }

    public function renderMenuItem()
    {
        $html = '<li class="list-group-item" id="' . $this->id . '">';
        $html .= $this->title;
        $html .= ' <a href="/admin/index.php?module=menu&action=editMenuItem&id=' . $this->id . '">Изменить</a> ';
        $html .= ' <a href="/admin/index.php?module=menu&action=deleteMenuItem&id=' . $this->id . '">Удалить</a> ';

        if (!empty($this->child_menu_items)) {

            $html .= '<button class="btn btn-sm btn-primary float-end" data-bs-toggle="collapse" data-bs-target="#submenu' . $this->id . '">Подробнее</button>';
            $html .= '<ul class="menu-list list-group collapse" id="submenu' . $this->id . '">';

            foreach ($this->child_menu_items as $child) {
                $html .= $child->renderMenuItem();
            }
            $html .= '</ul>';
        }
        $html .= '</li>';

        return $html;
    }

    public function update($data): void
    {
        $this->setFieldValues([
            'title' => $data->title,
            'url' => $data->url,
            'parent_menu_item_id' => $data->parent_menu_item_id,
            'route_id' => $data->route_id
        ]);
        $this->save();
    }

    public function updateMenuItemUrl(string $url): void
    {
        $this->url = $url;
        $this->save();
    }
}
