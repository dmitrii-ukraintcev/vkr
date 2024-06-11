<?php

namespace src;
use mysqli;

abstract class ContentEntity extends Entity
{
    public string $title;
    public string $url;
    protected string $entityName;
    protected string $controllerName;
    protected ?string $updateAction = null;

    protected function initFields(): void
    {
        $this->fields = [
            'title'
        ];
    }

    protected function setFieldValues(array $data, bool $fromDatabase = false): void
    {
        parent::setFieldValues($data);

        if ($fromDatabase) {
            $this->url = Route::getByField($this->dbConn, $this->entityName . '_id', $this->id)->path;
        }
    }

    public static function add(mysqli $dbConn, $data): static
    {
        $object = parent::add($dbConn, $data);
        $object->manageRoute();
        $object->afterInsert($data);

        return $object;
    }

    public function update($data): void
    {
        parent::update($data);
        $this->manageRoute();
        $this->afterUpdate($data);
    }

    protected function manageRoute(): void
    {
        $this->url = $this->generateUrl();
        $route = Route::getByField($this->dbConn, $this->entityName . '_id', $this->id);

        if ($route) {
            $route->updatePath($this->url);
        } else {
            Route::add($this->dbConn, new RouteData($this->url, $this->controllerName, $this->entityName, $this->id, $this->updateAction));
        }
    }

    protected function generateUrl(): string
    {
        $url = strtolower(str_replace(' ', '-', $this->title));

        $parentUrl = $this->getParentUrlPath();
        if ($parentUrl) {
            $url = $parentUrl . '/' . $url;
        } else {
            $url = '/' . $this->entityName . '/' . $url;
        }

        return $url;
    }

    private function getParentUrlPath(): ?string
    {
        $parent = 'parent_' . $this->entityName . '_id';
        if (property_exists($this, $parent) && $this->$parent !== null && $this->$parent != 1) {
            return Route::getByField($this->dbConn, $this->entityName . '_id', $this->$parent)->path;
        }
        return null;
    }

    abstract protected function afterInsert($data): void;
    abstract protected function afterUpdate($data): void;
}
