<?php

declare(strict_types=1);
namespace src;

use modules\menu\admin\models\Menu;
use modules\menu\admin\models\MenuItem;
use mysqli;

class Template
{
    private string $templatePath;
    private string $context;
    private mysqli $dbConn;

    function __construct(mysqli $dbConn, string $templatePath, string $context = 'site')
    {
        // $this->dbConn = $dbConn;
        $this->templatePath = $templatePath;
        $this->context = $context;
    }

    function renderView(array $data, ?string $view = null, ?string $selected_theme = null) : void
    {
        extract($data);


        if ($this->context === 'admin'){
            include VIEW_PATH . $this->templatePath . ".php";
        }
        else{
            $menus = Menu::getAll($GLOBALS['db_conn']);
            $index = array_search('header_menu', array_column($menus, 'name'));
            $headerMenu = $index !== false ? $menus[$index] : null;
            include THEMES_PATH . $selected_theme . '/' . $this->templatePath . ".php";
        }
    }
}
