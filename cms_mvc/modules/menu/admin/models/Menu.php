<?php

namespace modules\menu\admin\models;

use src\Entity;

class Menu extends Entity
{
    protected static string $tableName = 'menus';
    public string $name;

    protected function initFields(): void
    {
        $this->fields = [
            'name'
        ];
    }

    public function getMenuItems(): array
    {
        $menuItems = MenuItem::getAll($this->dbConn, ['menu_id' => $this->id]);

        usort($menuItems, fn($a, $b) => $a->order_num <=> $b->order_num);

        return $menuItems;
    }

    public function getAddedPageIds(): array
    {
        $sql = "SELECT page_id FROM routes INNER JOIN menu_items ON menu_items.route_id=routes.id WHERE menu_items.menu_id=?";
        // $sql = "SELECT page_id FROM routes INNER JOIN menu_items ON menu_items.url=routes.path WHERE menu_items.menu_id=$menu_id";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $result = $stmt->get_result();

        $addedPageIds = [];
        while ($row = $result->fetch_assoc()) {
            $addedPageIds[] = $row['page_id'];
        }

        return $addedPageIds;
    }

    private function getNextOrderNum(): int
    {
        $sql = "SELECT MAX(order_num) AS max_order_num FROM menu_items WHERE menu_id=?";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $maxOrderNum = $row['max_order_num'];

        return $maxOrderNum !== null ? $maxOrderNum + 1 : 1;
    }

    public function addMenuItem(MenuItemData $data) : MenuItem
    {
        $menuItem = new MenuItem($this->dbConn);

        $menuItem->setFieldValues([
            'menu_id' => $this->id,
            'title' => $data->title,
            'url' => $data->url,
            'order_num' => $this->getNextOrderNum(),
            'route_id' => $data->route_id,
            'parent_menu_item_id' => $data->parent_menu_item_id
        ]);
        $menuItem->insert();

        return $menuItem;
    }

    public function updateMenuItemsOrder($newOrder)
    {
        foreach ($newOrder as $index => $itemId) {
            $itemId = (int)$itemId;
            $menu_item = MenuItem::getByField($this->dbConn, 'id', $itemId);
            $menu_item->setFieldValues(['order_num' => $index + 1]);
            $menu_item->save();
        }
    }
}