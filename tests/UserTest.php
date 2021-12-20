<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use app\Connection;
use app\DB;
use app\User;
use \Exception;

final class UserTest extends TestCase
{
    public $dsn = 'mysql:host=localhost;dbname=wisebits';
    public $user = 'user';
    public $pass = 'pass';

    /** @test */
    public function findUserById()
    {
        $connection = new Connection($this->dsn, $this->user, $this->pass);
        $db = new DB($connection);
        $user =  new User($db);
        $user->getOne(5);

        $this->assertIsObject($user);
    }

    /** @test */
    public function notFoundUserById()
    {
        $connection = new Connection($this->dsn, $this->user, $this->pass);
        $db = new DB($connection);
        $user =  new User($db);

        $this->expectException(Exception::class);
        $user->getOne(77);
    }


    /** @test */
    public function createUserSuccess()
    {
        $connection = new Connection($this->dsn, $this->user, $this->pass);
        $db = new DB($connection);
        $user =  new User($db);

        $params = [
            'name' => 'alexander',
            'email' => 'ivan@super.com',
            'created' => '2021-07-01 12:45:45',
            'deleted' => null,
            'notes' => 'Test user',
        ];
        $result = $user->create($params);

        $this->assertEquals($result, true);
    }

    /** @test */
    public function createUserFailureWithIncorrectName()
    {
        $connection = new Connection($this->dsn, $this->user, $this->pass);
        $db = new DB($connection);
        $user =  new User($db);

        $params = [
            'name' => 'Alma',
            'email' => 'ivan@super.com',
            'created' => '2021-07-01 12:45:45',
            'deleted' => null,
            'notes' => 'Test user',
        ];

        $this->expectException(Exception::class);
        echo $user->create($params);
    }

    /** @test */
    public function createUserFailureForbiddenName()
    {
        $connection = new Connection($this->dsn, $this->user, $this->pass);
        $db = new DB($connection);
        $user =  new User($db);

        $params = [
            'name' => 'joshephina',
            'email' => 'ivan@super.com',
            'created' => '2021-07-01 12:45:45',
            'deleted' => null,
            'notes' => 'Test user',
        ];

        $this->expectException(Exception::class);
        echo $user->create($params);
    }

    /** @test */
    public function createUserFailureWithIncorrectEmail()
    {
        $connection = new Connection($this->dsn, $this->user, $this->pass);
        $db = new DB($connection);
        $user =  new User($db);

        $params = [
            'name' => 'alexander',
            'email' => 'ivan@school.edu',
            'created' => '2021-07-01 12:45:45',
            'deleted' => null,
            'notes' => 'Test user',
        ];

        $this->expectException(Exception::class);
        $user->create($params);
    }


    /** @test */
    public function deleteUserSuccess()
    {
        $connection = new Connection($this->dsn, $this->user, $this->pass);
        $db = new DB($connection);
        $user =  new User($db);

        $result = $user->delete(5);
        $this->assertEquals($result, true);
    }
}
