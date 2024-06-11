<?php

declare(strict_types=1);

namespace src;
use mysqli;

class AdminController extends Controller
{
    public function __construct(mysqli $dbConn) {
        parent::__construct($dbConn);
        $this->template = new Template($dbConn, 'admin/dashboard', 'admin');
    }

    function runBeforeAction(): bool
    {
        if ($_SESSION['logged_in']) {
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

    function runAction(string $action): void
    {
        $runBeforeAction = 'runBeforeAction';
        if (method_exists($this, $runBeforeAction)) {
            if (!$this->$runBeforeAction()) {
                return;
            }
        }

        parent::runAction($action);
    }
}
