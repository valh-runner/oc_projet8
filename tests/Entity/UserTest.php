<?php

namespace App\Tests\Entity;


use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testEmailShouldBeEditedTask()
    {
        $user = new User();

        $email = 'hello@example.com';


        $user->setEmail($email);
        self::assertNotNull($user->getEmail());
        self::assertSame($email, $user->getEmail());

        $user->setEmail('moon@example.com');
        self::assertNotNull($user->getEmail());
        self::assertNotSame($email, $user->getEmail());
    }
}
