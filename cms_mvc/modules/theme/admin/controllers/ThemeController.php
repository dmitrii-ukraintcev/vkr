<?php

declare(strict_types=1);

namespace modules\theme\admin\controllers;

use modules\theme\admin\models\Theme;
use src\AdminController;

class ThemeController extends AdminController
{
    function defaultAction(): void
    {
        $theme = new Theme(THEMES_PATH);
        $data['themes'] = $theme->getThemes();

        if ($_POST['action'] ?? 0) {
            $_SESSION['selected_theme'] = $_POST['selected_theme'];
        }

        $this->template->renderView($data, 'theme/admin/views/theme_list');
    }
}
