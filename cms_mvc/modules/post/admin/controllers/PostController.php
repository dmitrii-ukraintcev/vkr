<?php

namespace modules\post\admin\controllers;

use modules\category\models\Category;
use modules\post\models\PostData;
use src\AdminController;
use modules\post\models\Post;

class PostController extends AdminController
{
    function defaultAction(): void
    {
        $data['posts'] = Post::getAll($this->dbConn);

        $this->template->renderView($data, 'post/admin/views/post_list');
    }

    function addPostAction(): void
    {
        if ($_POST['action'] ?? 0 == 1) {
            Post::add($this->dbConn, new PostData(
                $_POST['title'],
                $_POST['content'],
                $_SESSION['current_user_id'],
                $_POST['categories'] ?? [1]
            ));

            header('Location: /admin/index.php?module=post');
            exit();
        }

        $categories = Category::getAll($this->dbConn);
        array_shift($categories);

        $data['categories'] = $categories;
        $this->template->renderView($data, 'post/admin/views/post_add');
    }

    function editPostAction(): void
    {
        $postId = $_GET['id'];
        $post = Post::getByField($this->dbConn, 'id', $postId);

        if ($_POST['action'] ?? 0) {
            $post->update(new PostData(
                $_POST['title'],
                $_POST['content'],
                $_SESSION['current_user_id'],
                $_POST['categories'] ?? [1]
            ));
        }

        $data['post'] = $post;
        $data['post_categories'] = $post->getCategoryIds();

        $categories = Category::getAll($this->dbConn);
        array_shift($categories);

        $data['categories'] = $categories;
        // $page = $pageObj;
        // $pages = $pageObj->getAll();
        // $child_pages = $pageObj->getChildPages();
        $this->template->renderView($data, 'post/admin/views/post_edit');
        // include VIEW_PATH . 'admin/content_editor.php';
    }

    function deletePostAction(): void
    {
        $postId = $_GET['id'];
        $postObj = Post::getByField($this->dbConn, 'id', $postId);
        $postObj->delete();

        header('Location: /admin/index.php?module=post');
    }
}
