<?php

declare(strict_types=1);

namespace modules\post\controllers;

use modules\post\models\Post;
use src\EntityController;
use src\Template;

class PostController extends EntityController
{
    function defaultAction(): void
    {
        $post = Post::getByField($this->dbConn, 'id', $this->entity_id);
        $data['page'] = $post;

        $this->template = new Template('templates/default');
        $this->template->renderView($data);
    }

    function showByCategoryAction(): void
    {
        $posts = POST::getPostsByCategory($this->dbConn, $this->entity_id);
        $data['posts'] = $posts;

        $this->template = new Template('templates/default');
        $this->template->renderView($data);
    }
}
