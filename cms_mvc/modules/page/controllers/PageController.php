<?php

declare(strict_types=1);

namespace modules\page\controllers;

use modules\page\models\Page;
use src\EntityController;
use src\Template;

class PageController extends EntityController
{
    // function defaultAction(): void
    // {
    //     $page = Page::getByField($this->dbConn, 'id', $this->entity_id);
    //     $data['page'] = $page;

    //     $template = new Template('templates/default');
    //     $template->renderView('page/views/static_page', $data);
    // }

    function defaultAction(): void
    {
        $page = Page::getByField($this->dbConn, 'id', $this->entity_id);
        $data['page'] = $page;

        if ($this->entity_id === 1) {
            $template = new Template($this->dbConn, 'templates/pages/index');
        } else {
            $template = new Template($this->dbConn, 'templates/pages/page');
        }

        $template->renderView($data, selected_theme: $_SESSION['selected_theme']);
    }
}
