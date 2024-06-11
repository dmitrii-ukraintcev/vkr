<?php

namespace modules\category\admin\controllers;

use modules\category\models\Category;
use modules\category\models\CategoryData;
use src\AdminController;

class CategoryController extends AdminController
{
    function defaultAction(): void
    {
        $categories = Category::getAll($this->dbConn);
        array_shift($categories);
        $data['categories'] = $categories;

        $this->template->renderView($data, 'category/admin/views/category_list');
    }

    function editCategoryAction(): void
    {
        $categoryId = $_GET['id'];
        $category = Category::getByField($this->dbConn, 'id', $categoryId);

        if ($_POST['action'] ?? 0) {

            if (empty($_POST['parent_category_id'])) {
                unset($_POST['parent_category_id']);
            }

            $category->update(new CategoryData(
                $_POST['title'],
                $_POST['parent_category_id'] ?? null
            ));
        }

        $data['category'] = $category;
        $data['child_categories'] = $category->getChildCategories();
        $categories = Category::getAll($this->dbConn);
        array_shift($categories);
        $data['categories'] = $categories;
        $this->template->renderView($data, 'category/admin/views/category_edit');
    }

    function addCategoryAction(): void
    {
        if ($_POST['action'] ?? 0) {

            if (empty($_POST['parent_category_id'])) {
                unset($_POST['parent_category_id']);
            }

            Category::add($this->dbConn, new CategoryData(
                $_POST['title'],
                $_POST['parent_category_id'] ?? null
            ));

            header('Location: /admin/index.php?module=category');
            exit();
        }

        $categories = Category::getAll($this->dbConn);
        array_shift($categories);
        $data['categories'] = $categories;
        $this->template->renderView($data, 'category/admin/views/category_add');
    }

    function deleteCategoryAction(): void
    {
        $categoryId = $_GET['id'];
        $category = Category::getByField($this->dbConn, 'id', $categoryId);
        $category->delete();

        header('Location: /admin/index.php?module=category');
    }
}
