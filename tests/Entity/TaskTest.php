<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    public function testCreatedAtInitializedWithDateNow()
    {
        $task = new Task();
        self::assertNotNull($task->getCreatedAt());
        $date = (new DateTime())->format('Y-m-d H:i');
        self::assertSame($date, $task->getCreatedAt()->format('Y-m-d H:i'));
    }

    public function testTitleInitializedWithNull()
    {
        $task = new Task();
        self::assertNull($task->getTitle());
    }

    public function testContentInitializedWithNull()
    {
        $task = new Task();
        self::assertNull($task->getContent());
    }

    public function testIsDoneInitializedWithFalse()
    {
        $task = new Task();
        self::assertFalse($task->isDone());
    }

    public function testCreatedAtAssigned()
    {
        $task = new Task();
        $date = new DateTime('2030-01-01 00:00:00');
        $task->setCreatedAt($date);
        self::assertSame($date, $task->getCreatedAt());
    }

    public function testTitleAssigned()
    {
        $task = new Task();
        $task->setTitle('A title');
        self::assertSame('A title', $task->getTitle());
    }

    public function testContentAssigned()
    {
        $task = new Task();
        $task->setContent('A content');
        self::assertSame('A content', $task->getContent());
    }

    public function testIsDoneAssigned()
    {
        $task = new Task();
        $task->toggle(true);
        self::assertTrue($task->isDone());
    }
}
