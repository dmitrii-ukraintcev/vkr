<?php

declare(strict_types=1);

namespace modules\user\admin\controllers;

use src\AdminController;
use modules\user\admin\models\User;

class UserController extends AdminController
{
    function runBeforeAction(): bool
    {
        if ($_SESSION['logged_in'] && $_SESSION['is_admin']) {
            return true;
        }
        $action = $_GET['action'] ?? $_POST['action'] ?? 'default';
        if ($action != 'login') {
            header('Location: /admin/index.php?module=dashboard&action=login');
            return false;
        } else {
            return true;
        }
    }

    function defaultAction(): void
    {
        $data['users'] = User::getAll($this->dbConn);

        $this->template->renderView($data, 'page/admin/views/user_list');
    }
}
