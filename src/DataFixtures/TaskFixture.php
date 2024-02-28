<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TaskFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // retrieve users objects from other fixture
        $adminUser1 = $this->getReference('admin-user-1');
        $user1 = $this->getReference('user-1');
        $user2 = $this->getReference('user-2');
        $user3 = $this->getReference('user-3');


        $this->createTask('administrer todoco', 'et éventuellement supprimer des tâches utilisateur', $adminUser1, $manager);
        $this->createTask('construire un abri de jardin', 'en bois exotique', $user1, $manager);
        $this->createTask('rédiger le rapport', 'ainsi que les annexes', $user2, $manager);
        $this->createTask('faire un cake salé', 'avec de la feta et des olives vertes', $user3, $manager);

        $manager->flush();
    }
}
