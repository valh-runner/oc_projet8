<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TaskFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $task = new Task();
        $task->setTitle('construire une route');
        $task->setContent('avec deux voies séparées');
        $manager->persist($task);

        $manager->flush();
    }
}
