<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{

    private UserPasswordHasherInterface $userPasswordHasherInterface;

    /**
     * Constructor
     * 
     * @param UserPasswordHasherInterface $userPasswordHasherInterface
     * @return list<class-string<FixtureInterface>>
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    /**
     * Fixture main logic
     *
     * @param ObjectManager $manager Manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $adminUser1 = $this->createUser('jarvis', '0Todoco*', 'jarvis@example.com', $manager, ['ROLE_ADMIN']);
        $user1 = $this->createUser('anonym', '4Todoco+', 'anonym@example.com', $manager);
        $user2 = $this->createUser('barry', '8Todoco+', 'barry@example.com', $manager);
        $user3 = $this->createUser('stephen', '5Todoco+', 'stephen@example.com', $manager);
        $user4 = $this->createUser('amy', '4Todoco+', 'amy@example.com', $manager);
        $manager->flush();

        // Share users objects with other fixture.
        $this->addReference('admin-user-1', $adminUser1);
        $this->addReference('user-1', $user1);
        $this->addReference('user-2', $user2);
        $this->addReference('user-3', $user3);
        $this->addReference('user-4', $user4);
    }

    /**
     * User creation logic
     *
     * @param string $username Username
     * @param string $password Password
     * @param string $email Email
     * @param ObjectManager $manager Manager
     * @param array $roles Roles
     * @return void
     */
    private function createUser(string $username, string $password, string $email, ObjectManager $manager, array $roles = []): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setRoles($roles);
        $hashedPassword = $this->userPasswordHasherInterface->hashPassword($user, $password); // Hash the password.
        $user->setPassword($hashedPassword);

        $manager->persist($user);
        return $user;
    }
}
