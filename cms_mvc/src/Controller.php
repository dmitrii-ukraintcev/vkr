<?php

declare(strict_types=1);

namespace src;

use modules\page\models\Page;
use mysqli;

class Controller
{
    protected mysqli $dbConn;
    protected Template $template;

    function __construct(mysqli $dbConn)
    {
        $this->dbConn = $dbConn;
    }

    function runAction(string $action): void
    {
        $action .= 'Action';
        if (method_exists($this, $action)) {
            $this->$action();
        } else {
            // 404
            // $pageObj = new Page();
            // $pageObj->getByField('id', 6);
            // $data['page'] = $pageObj;

            // $this->updateView('page/views/static_page', $data);
        }
    }
}
