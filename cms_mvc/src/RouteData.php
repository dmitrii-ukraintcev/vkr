<?php

namespace src;

class RouteData
{
    public string $url;
    public string $controller;
    public string $entity;
    public int $entity_id;
    public ?string $action;

    public function __construct(string $url, string $controller, string $entity, int $entity_id, string $action = null)
    {
        $this->url = $url;
        $this->controller = $controller;
        $this->entity = $entity;
        $this->entity_id = $entity_id;
        $this->action = $action;
    }
}
