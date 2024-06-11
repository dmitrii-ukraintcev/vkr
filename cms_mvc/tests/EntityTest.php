<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use src\Entity;

class User extends Entity
{
    public string $name;
    public string $email;
    protected static string $tableName = 'users';

    protected function initFields(): void
    {
        $this->fields = ['name', 'email'];
    }
}

class EntityTest extends TestCase
{
    private $dbConn;

    protected function setUp(): void
    {
        $this->dbConn = new mysqli('localhost', 'root', '', 'test_db');

        $this->dbConn->query("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL
            )
        ");
    }

    protected function tearDown(): void
    {
        $this->dbConn->query("DROP TABLE IF EXISTS users");
        $this->dbConn->close();
    }

    public function testAdd(): void
    {
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $user = User::add($this->dbConn, $data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertNotNull($user->id);
    }

    public function testUpdate(): void
    {
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $user = User::add($this->dbConn, $data);
        $user->update(['name' => 'Jane Doe']);

        $updatedUser = User::getByField($this->dbConn, 'id', $user->id);
        $this->assertEquals('Jane Doe', $updatedUser->name);
    }

    public function testGetAll(): void
    {
        User::add($this->dbConn, ['name' => 'John Doe', 'email' => 'john@example.com']);
        User::add($this->dbConn, ['name' => 'Jane Doe', 'email' => 'jane@example.com']);

        $users = User::getAll($this->dbConn);

        $this->assertCount(2, $users);
    }

    public function testGetByField(): void
    {
        $user = User::add($this->dbConn, ['name' => 'John Doe', 'email' => 'john@example.com']);

        $retrievedUser = User::getByField($this->dbConn, 'email', 'john@example.com');
        $this->assertInstanceOf(User::class, $retrievedUser);
        $this->assertEquals('John Doe', $retrievedUser->name);
    }

    public function testDelete(): void
    {
        $user = User::add($this->dbConn, ['name' => 'John Doe', 'email' => 'john@example.com']);
        $user->delete();

        $deletedUser = User::getByField($this->dbConn, 'id', $user->id);
        $this->assertNull($deletedUser);
    }
}
