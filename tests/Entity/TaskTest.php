<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testCreatedAtIsInitializedWithDateNowWhenCreatingNewTask()
    {
        $task = new Task();
        self::assertNotNull($task->getCreatedAt());
        $date = (new DateTime())->format('Y-m-d H:i');
        self::assertSame($date, $task->getCreatedAt()->format('Y-m-d H:i'));
    }

    //fare pareil pour getters et setters en vérifiant que valeur bien settée

    // tester isDone initialisé à false ..

    //tester aussi class user
}
