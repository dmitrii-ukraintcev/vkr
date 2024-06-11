<?php

namespace modules\dashboard\admin\controllers;

use src\Controller;
use src\Auth;

class DashboardController extends Controller
{
    function defaultAction()
    {
        header('Location: /admin/index.php?module=page');
        exit();
    }

    function loginAction()
    {
        if ($_POST['postAction'] ?? 0) {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $auth = new Auth();
            if ($auth->verifyLogin($this->dbConn, $username, $password)) {
                $_SESSION['logged_in'] = true;
                header('Location: /admin/');
                exit();
            }
            // var_dump($password);
            $_SESSION['validation']['error'] = "Username or password is incorrect";
        }

        include VIEW_PATH . 'admin/login.php';
        unset($_SESSION['validation']['error']);
    }
}
