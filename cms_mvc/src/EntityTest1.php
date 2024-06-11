<?php

declare(strict_types=1);

use modules\page\models\PageData;
use PHPUnit\Framework\TestCase;
use modules\page\models\Page;

class DatabaseConnection
{
    function prepare()
    {
        return new Statement();
    }
}

class Statement
{
    function bind_param()
    {
    }

    function execute()
    {
    }

    function close()
    {
    }

    function get_result()
    {
        return new Data();
    }
}

class Data
{
    function fetch_all()
    {
        return  [
            ['id' => 1, 'title' => 'Home Page', 'content' => 'Home page content'],
            ['id' => 2, 'title' => 'About Us Page', 'content' => 'About us page content']
        ];
    }
}

final class EntityTest1 extends TestCase
{
    // public function testGetAll(): void
    // {
    //     $db_conn = new DatabaseConnection();
    //     $pages = Page::getAll($db_conn);

    //     $this->assertEquals(2, count($pages));
    //     $this->assertEquals(1, $pages[0]->id);
    // }

    // public function testGetByField(): void
    // {
    //     $db_conn = new DatabaseConnection();
    //     $page = Page::getByField($db_conn, 'id', 1);

    //     $this->assertEquals(1, $page->id);
    // }

    // public function testAdd(): void
    // {
    //     $db_conn = new DatabaseConnection();
    //     $page = Page::add($db_conn, new PageData('New page', '', 1));

    //     $this->assertEquals($page->title, 'New page');
    // }
}
