<?php

declare(strict_types=1);

namespace src;

use modules\user\admin\models\User;
use mysqli;

class Auth
{
    function verifyLogin(mysqli $dbConn, string $username, string $password): bool
    {
        $user = User::getByField($dbConn, 'username', $username);

        if (!$user->id) {
            return false;
        }
        if (!password_verify($password, $user->password_hash)) {
            return false;
        }

        $_SESSION['current_user_id'] = $user->id;
        $_SESSION['is_admin'] = $user->role == 'admin' ? true : false;
        return true;
    }
}
