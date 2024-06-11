<?php

declare(strict_types=1);

namespace src;
use mysqli;

class Route extends Entity
{
    protected static string $tableName = 'routes';
    public string $path;
    public string $controller;
    public ?string $action;
    public ?int $page_id = null;
    public ?int $post_id = null;
    public ?int $category_id = null;
    public ?int $tag_id = null;

    protected function initFields(): void
    {
        $this->fields = [
            'path',
            'controller',
            'action',
            'page_id',
            'post_id',
            'category_id',
            'tag_id'
        ];
    }

    public static function add(mysqli $dbConn, $data): static
    {
        $route = new Route($dbConn);
        $route->setFieldValues([
            'path' => $data->url,
            'controller' => $data->controller,
            $data->entity . '_id' => $data->entity_id,
            'action' => $data->action
        ]);
        $route->insert();

        return $route;
    }

    public function updatePath($newPath): void
    {
        $this->path = $newPath;
        $this->save();
    }
}
