<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser1 = $this->createUser('jarvis', '0Todoco*', 'jarvis@example.com', $manager);
        $user1 = $this->createUser('barry', '8Todoco+', 'barry@example.com', $manager);
        $user2 = $this->createUser('stephen', '5Todoco+', 'stephen@example.com', $manager);
        $user3 = $this->createUser('amy', '4Todoco+', 'amy@example.com', $manager);
        $manager->flush();

        // share users objects with other fixture
        $this->addReference('admin-user-1', $adminUser1);
        $this->addReference('user-1', $user1);
        $this->addReference('user-2', $user2);
        $this->addReference('user-3', $user3);
    }

    private function createUser(string $username, string $password, string $email, ObjectManager $manager): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $hashedPassword = $this->userPasswordHasherInterface->hashPassword($user, $password); // hash the password
        $user->setPassword($hashedPassword);

        $manager->persist($user);
        return $user;
    }
}
