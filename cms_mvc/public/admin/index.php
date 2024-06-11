<?php

use src\DatabaseConnection;

session_start();

define('ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
define('VIEW_PATH', ROOT_PATH . 'view' . DIRECTORY_SEPARATOR);
define('THEMES_PATH', ROOT_PATH . 'themes' . '/');
define('MODULES_PATH', ROOT_PATH . 'modules' . DIRECTORY_SEPARATOR);

spl_autoload_register(function ($class_name) {
    $file = ROOT_PATH . str_replace('\\', '/', $class_name) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

DatabaseConnection::connect('localhost', 'root', '', 'cms_db');
$db_conn = DatabaseConnection::getInstance()->getConnection();

$module = $_GET['module'] ?? $_POST['module'] ?? 'dashboard';
$action = $_GET['action'] ?? $_POST['action'] ?? 'default';

$_SESSION['selected_theme'] ??= 'theme1';

$controllerName = "modules\\$module\admin\controllers\\" . ucfirst($module) . 'Controller';
$controller = new $controllerName($db_conn);
$controller->runAction($action);