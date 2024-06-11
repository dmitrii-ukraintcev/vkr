<?php

declare(strict_types=1);

namespace src;

use modules\page\models\Page;
use mysqli;

class EntityController extends Controller
{
    protected int $entity_id;

    public function __construct(mysqli $dbConn, int $entity_id) {
        parent::__construct($dbConn);
        $this->entity_id = $entity_id;
    }
}
