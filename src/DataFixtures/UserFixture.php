<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('jarvis');
        $user->setPassword('3xN1h1l0');
        $user->setEmail('jarvis@example.com');
        $manager->persist($user);

        $manager->flush();
    }
}
