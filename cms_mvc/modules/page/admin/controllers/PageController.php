<?php

declare(strict_types=1);

namespace modules\page\admin\controllers;

use modules\page\models\PageData;
use src\AdminController;
use modules\page\models\Page;

class PageController extends AdminController
{
    function defaultAction(): void
    {
        $data['pages'] = Page::getAll($this->dbConn);

        $this->template->renderView($data, 'page/admin/views/page_list');
    }

    function editPageAction(): void
    {
        $pageId = $_GET['id'];
        $page = Page::getByField($this->dbConn, 'id', $pageId);

        if ($_POST['action'] ?? 0) {
            $page->update(new PageData(
                $_POST['title'],
                $_POST['content'],
                (int)$_POST['parent_page_id']
            ));
        }

        $data['page'] = $page;
        $data['pages'] = Page::getAll($this->dbConn);
        $data['child_pages'] = $page->getChildPages();

        $pages = Page::getAll($this->dbConn);
        $child_pages = $page->getChildPages();
        // $this->template->renderView($data, 'page/admin/views/page_edit');
        include VIEW_PATH . 'admin/content_editor.php';
    }

    function addPageAction(): void
    {
        if ($_POST['action'] ?? 0) {
            Page::add($this->dbConn, new PageData(
                $_POST['title'],
                $_POST['content'],
                (int)$_POST['parent_page_id']
            ));

            header('Location: /admin/');
            exit();
        }

        $data['pages'] = Page::getAll($this->dbConn);
        $this->template->renderView($data, 'page/admin/views/page_add');
    }

    function deletePageAction(): void
    {
        $pageId = $_GET['id'];
        $page = Page::getByField($this->dbConn, 'id', $pageId);
        $page->delete();

        header('Location: /admin/');
    }
}
