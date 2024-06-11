<?php

namespace modules\page\models;

use modules\menu\admin\models\MenuItem;
use src\ContentEntity;
use src\Route;

class Page extends ContentEntity
{
    public function __construct($dbConn)
    {
        parent::__construct($dbConn);
    }
    
    protected static string $tableName = 'pages';
    protected string $entityName = 'page';
    protected string $controllerName = 'page';

    public string $content;
    public ?int $parent_page_id;

    protected function initFields(): void
    {
        parent::initFields();
        $this->fields[] = 'content';
        $this->fields[] = 'parent_page_id';
    }

    protected function afterInsert($data): void
    {
    }

    protected function afterUpdate($data): void
    {
        $this->updateMenuItems();

        $childPages = $this->getChildPages();
        foreach ($childPages as $page) {
            $page->manageRoute();
            $page->updateMenuItems();
        }
    }

    private function updateMenuItems(): void
    {
        $route = Route::getByField($this->dbConn, 'page_id', $this->id);
        $menuItem = MenuItem::getByField($this->dbConn, 'route_id', $route->id);

        if ($menuItem) {
            $menuItem->updateMenuItemUrl($this->url);
        }
    }

    public function getChildPages(): array
    {
        return Page::getAll($this->dbConn, ['parent_page_id' => $this->id]);
    }
}
