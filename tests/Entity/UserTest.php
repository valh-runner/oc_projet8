<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testIdInitializedWithNull()
    {
        $user = new User();
        self::assertNull($user->getId());
    }

    public function testUsernameInitializedWithNull()
    {
        $user = new User();
        self::assertNull($user->getUsername());
    }

    public function testPasswordInitializedWithNull()
    {
        $user = new User();
        self::assertNull($user->getPassword());
    }

    public function testEmailInitializedWithNull()
    {
        $user = new User();
        self::assertNull($user->getEmail());
    }

    public function testUsernameAssigned()
    {
        $user = new User();
        $user->setUsername('Dupont');
        self::assertSame('Dupont', $user->getUsername());
    }

    public function testPasswordAssigned()
    {
        $user = new User();
        $user->setPassword('P4ssw0rd*');
        self::assertSame('P4ssw0rd*', $user->getPassword());
    }

    public function testEmailAssigned()
    {
        $user = new User();
        $user->setEmail('user@example.com');
        self::assertSame('user@example.com', $user->getEmail());
    }

    public function testRolesReturned()
    {
        $user = new User();
        self::assertSame(['ROLE_USER'], $user->getRoles());
    }

    public function testRolesAssigned()
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        self::assertSame(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }

    public function testTaskAssigned()
    {
        $user = new User();

        $task = new Task();
        $task->setTitle('A title');
        $task->setContent('A content');

        $user->addTask($task);

        $tasks = $user->getTasks();
        self::assertSame(1, count($tasks));
        self::assertSame('A title', $tasks[0]->getTitle());
        self::assertSame('A content', $tasks[0]->getContent());
    }

    public function testTaskRemoved()
    {
        $user = new User();

        $task = new Task();
        $task->setTitle('A title');
        $task->setContent('A content');

        $user->addTask($task);

        $tasks = $user->getTasks();
        self::assertSame(1, count($tasks));

        $user->removeTask($task);

        $tasks = $user->getTasks();
        self::assertSame(0, count($tasks));
    }
}
