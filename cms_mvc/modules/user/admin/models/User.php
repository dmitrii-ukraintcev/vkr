<?php

namespace modules\user\admin\models;

use src\Entity;

class User extends Entity
{
    protected static string $tableName = 'users';
    public $username;
    public $password_hash;
    public $role;

    protected function initFields(): void
    {
        $this->fields = [
            'username',
            'password_hash',
            'role'
        ];
    }
}
