<?php

declare(strict_types=1);

namespace modules\menu\admin\controllers;

use modules\menu\admin\models\Menu;
use src\AdminController;;
use modules\menu\admin\models\MenuItem;
use modules\menu\admin\models\MenuItemData;
use modules\page\models\Page;
use src\Route;

class MenuController extends AdminController
{
    function defaultAction(): void
    {
        $data['menus'] = Menu::getAll($this->dbConn);

        $this->template->renderView($data, 'menu/admin/views/menu_list');
    }

    function editMenuAction(): void
    {
        $menuId = $_GET['id'];

        $menu = Menu::getByField($this->dbConn, 'id', $menuId);

        $data['menu'] = $menu;
        $data['menu_items'] = $menu->getMenuItems();

        $this->template->renderView($data, 'menu/admin/views/menu_edit');
    }

    function editMenuItemAction(): void
    {
        $menuItemId = $_GET['id'];

        $menuItem = MenuItem::getByField($this->dbConn, 'id', $menuItemId);

        if ($_POST['action'] ?? 0) {

            $parentMenuItemId = (int)$_POST['parent_menu_item_id'];
            if ($parentMenuItemId === 0) {
                $parentMenuItemId = null;
            }
            $menuItem->update(new MenuItemData($_POST['title'], $menuItem->url, $parentMenuItemId));
        }

        $data['menu_item'] = $menuItem;
        $data['menu_items'] = MenuItem::getAll($this->dbConn, ['menu_id' => $menuItem->menu_id]);

        $this->template->renderView($data, 'menu/admin/views/menu_item_edit');
    }

    function addMenuItemAction(): void
    {
        $menuId = $_GET['id'];

        $menu = Menu::getByField($this->dbConn, 'id', $menuId);

        if ($_POST['action'] ?? 0) {

            if ($_GET['type'] == 'page') {
                foreach ($_POST['page_ids'] as $pageId) {
                    $page = Page::getByField($this->dbConn, 'id', $pageId);
                    $route = Route::getByField($this->dbConn, 'page_id', $pageId);

                    $menu->addMenuItem(new MenuItemData(
                        $page->title,
                        $page->url,
                        route_id: $route->id
                    ));
                }
            } else if ($_GET['type'] == 'link') {
                $parentMenuItemId = (int)$_POST['parent_menu_item_id'];
                if ($parentMenuItemId === 0) {
                    $parentMenuItemId = null;
                }
                $menu->addMenuItem(new MenuItemData(
                    $_POST['title'],
                    $_POST['url'],
                    $parentMenuItemId
                ));
            }
            header("Location: /admin/index.php?module=menu&action=editMenu&id=$menuId");
            exit();
        }

        $data['pages'] = Page::getAll($this->dbConn);
        $data['menu_items'] = $menu->getMenuItems();
        $data['added_page_ids'] = $menu->getAddedPageIds();

        if ($_GET['type'] == 'page') {
            $this->template->renderView($data, 'menu/admin/views/menu_item_add_page');
        } else if ($_GET['type'] == 'link') {
            $this->template->renderView($data, 'menu/admin/views/menu_item_add');
        }
    }

    function deleteMenuItemAction(): void
    {
        $menuItemId = $_GET['id'];
        $menuItem = MenuItem::getByField($this->dbConn, 'id', $menuItemId);
        $menuItem->delete();

        header("Location: /admin/index.php?module=menu&action=editMenu&id=$menuItem->menu_id");
    }

    function updateMenuOrderAction(): void
    {
        $newOrder = $_POST['order'];
        $menu = new Menu($this->dbConn);
        $menu->updateMenuItemsOrder($newOrder);
    }
}
