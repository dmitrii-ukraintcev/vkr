<?php

use src\DatabaseConnection;
use src\Route;

session_start();

define('ROOT_PATH', dirname(__FILE__) . '/../');
define('VIEW_PATH', ROOT_PATH . 'view' . '/');
define('THEMES_PATH', ROOT_PATH . 'themes' . '/');
define('MODULES_PATH', ROOT_PATH . 'modules' . '/');

// Автоподключение файлов
spl_autoload_register(function ($class_name) {
    $file = ROOT_PATH . str_replace('\\', '/', $class_name) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Подключение к БД
DatabaseConnection::connect('localhost', 'root', '', 'cms_db');
$db_conn = DatabaseConnection::getInstance()->getConnection();

// Маршутизация
// echo $_SERVER['REQUEST_URI'];
$section = $_GET['section'] ?? $_POST['section'] ?? '';

// $route = new Route($db_conn);
// $route->getByField('path', $section);
// Entity::setConnection($db_conn);

$route = Route::getByField($db_conn, 'path', '/' . $section);

$moduleName = $route->controller;
$controllerName = "modules\\$moduleName\controllers\\" . ucfirst($moduleName) . 'Controller';
$controllerName = "modules\\$moduleName\controllers\\" . ucfirst($moduleName) . 'Controller';
$action = $route->action ?? 'default';

// $template = new Template('templates/default');
$entityId = $route->page_id ?? $route->post_id ?? $route->category_id ?? $route->tag_id;

$controller = new $controllerName($db_conn, $entityId);
$controller->runAction($action);
