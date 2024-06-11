<?php

namespace modules\theme\admin\models;

class Theme
{
    private $themesDir;

    public function __construct(string $themesDir)
    {
        $this->themesDir = $themesDir;
    }

    public function getThemes() : array
    {
        $allFilesAndFolders = scandir($this->themesDir);

        $themes = array_filter($allFilesAndFolders, function($item) {
            return is_dir($this->themesDir . DIRECTORY_SEPARATOR . $item) && $item !== '.' && $item !== '..';
        });

        return $themes;
    }
}