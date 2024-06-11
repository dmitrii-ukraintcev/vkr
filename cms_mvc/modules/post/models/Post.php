<?php

namespace modules\post\models;

use modules\category\models\Category;
use modules\post\models\PostCategories;
use modules\user\admin\models\User;
use mysqli;
use src\ContentEntity;

class Post extends ContentEntity
{
    protected static string $tableName = 'posts';
    protected string $entityName = 'post';
    protected string $controllerName = 'post';

    public string $content;
    public int $author_id;
    public User $author;
    public $updated_datetime;
    public array $categories = [];

    protected function initFields(): void
    {
        parent::initFields();
        $this->fields = array_merge($this->fields, [
            'content',
            'author_id',
            'updated_datetime'
        ]);
    }

    protected function setFieldValues(array $data, bool $fromDatabase = false): void
    {
        parent::setFieldValues($data, $fromDatabase);

        if ($fromDatabase) {
            $this->categories = $this->getCategories();
            $this->author = User::getByField($this->dbConn, 'id', $this->author_id);
        }
    }

    protected function afterInsert($data): void
    {
        $this->updatePostCategories($data->categories);
    }


    protected function afterUpdate($data): void
    {
        $this->updatePostCategories($data->categories);
    }

    private function updatePostCategories(array $categories): void
    {
        $postId = $this->id;
        $currentCategories = PostCategories::getAll($this->dbConn, ['post_id' => $postId]);

        foreach ($currentCategories as $category) {
            $category->delete();
        }

        foreach ($categories as $categoryId) {
            $data = (object)['post_id' => $postId, 'category_id' => (int)$categoryId];
            PostCategories::add($this->dbConn, $data);
        }

        $this->categories = $this->getCategories();
    }

    public function getCategories(): array
    {
        $categoryIds = $this->getCategoryIds();

        $categories = [];
        foreach ($categoryIds as $categoryId) {
            $category = Category::getByField($this->dbConn, 'id', $categoryId);
            $categories[] = $category;
        }

        return $categories;
    }

    public function getCategoryIds(): array
    {
        $postCategories = PostCategories::getAll($this->dbConn, ['post_id' => $this->id]);
        return array_map(fn ($postCategory) => $postCategory->category_id, $postCategories);
    }

    public static function getPostsByCategory(mysqli $dbConn, int $categoryId): array
    {
        $postCategories = PostCategories::getAll($dbConn, ['category_id' => $categoryId]);
        $postIds = array_map(fn ($postCategory) => $postCategory->post_id, $postCategories);

        $posts = [];
        foreach ($postIds as $postId) {
            $post = Post::getByField($dbConn, 'id', $postId);
            $posts[] = $post;
        }

        return $posts;
    }
}
