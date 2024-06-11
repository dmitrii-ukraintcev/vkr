<?php

declare(strict_types=1);

namespace src;

use mysqli;
use src\Entity;

class User extends Entity
{
    public string $name;
    public string $email;
    protected static string $tableName = 'users';

    public function __construct(mysqli $dbConn)
    {
        parent::__construct($dbConn);
        $this->initFields();
    }

    protected function initFields(): void
    {
        $this->fields = ['name', 'email'];
    }
}