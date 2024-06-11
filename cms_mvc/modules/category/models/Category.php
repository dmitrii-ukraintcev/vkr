<?php

namespace modules\category\models;

use src\ContentEntity;

class Category extends ContentEntity
{
    protected static string $tableName = 'categories';
    protected string $entityName = 'category';
    protected string $controllerName = 'post';
    protected ?string $updateAction = 'showByCategory';

    public ?int $parent_category_id;

    protected function initFields(): void
    {
        parent::initFields();
        $this->fields[] = 'parent_category_id';
    }

    protected function afterInsert($data): void
    {
    }

    protected function afterUpdate($data): void
    {
        $childCategories = $this->getChildCategories();
        foreach ($childCategories as $category) {
            $category->manageRoute();
        }
    }

    public function getChildCategories(): array
    {
        return Category::getAll($this->dbConn, ['parent_category_id' => $this->id]);
    }
}
