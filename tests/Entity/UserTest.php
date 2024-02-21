<?php

namespace App\Tests\Entity;

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
}
