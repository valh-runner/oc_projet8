<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TaskControllerTest extends WebTestCase
{

    private ?KernelBrowser $client = null;

    private ?UrlGeneratorInterface  $urlGenerator = null;

    private ?EntityRepository $taskRepository = null;

    private ?EntityRepository $userRepository = null;

    private ?User $testUser = null;

    public function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');

        $this->taskRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Task::class);
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        $this->testUser = $this->userRepository->findOneByEmail('jarvis@example.com'); // Retrieve the test user.
    }

    public function tearDown(): void
    {
        unset($this->client, $this->urlGenerator);
        unset($this->taskRepository, $this->userRepository, $this->testUser);
    }

    /**
     * Task list test
     *
     * @return void
     */
    public function testList(): void
    {
        $this->client->loginUser($this->testUser); // Simulate the test user being logged in.
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_list'));
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextContains('a', 'Créer une tâche');
    }

    /**
     * Task create test
     *
     * @return void
     */
    public function testCreate(): void
    {
        $this->client->loginUser($this->testUser); // Simulate the test user being logged in.
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_create'));
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'construire un mur';
        $form['task[content]'] = 'avec des briques rouges et des piliers blanc';
        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! La tâche a été bien été ajoutée.');
    }

    /**
     * Task edit test
     *
     * @return void
     */
    public function testEdit(): void
    {
        $testTask = $this->taskRepository->findAll(['limit' => 1])[0]; // Retrieve a task.

        $this->client->loginUser($this->testUser); // Simulate the test user being logged in.
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_edit', ['id' => $testTask->getId()]));
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'construire un pont';
        $form['task[content]'] = 'doté de trois voûtes';
        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertSelectorTextContains('div.alert.alert-success', "Superbe ! La tâche a bien été modifiée");
        $this->assertResponseIsSuccessful();

        $task2 = $this->taskRepository->findOneByTitle('construire un pont'); // Retrieve the test task.
        $this->assertSame('doté de trois voûtes', $task2->getContent());
    }

    /**
     * Task toggle test
     *
     * @return void
     */
    public function testToggleTask(): void
    {
        $testTask = $this->taskRepository->findAll(['limit' => 1])[0]; // Retrieve a task.

        $this->client->loginUser($this->testUser); // Simulate the test user being logged in.
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_toggle', ['id' => $testTask->getId()]));
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert.alert-success', "a bien été marquée comme faite");
    }

    /**
     * Task delete test
     *
     * @return void
     */
    public function testDeleteTask(): void
    {
        $simpleUser = $this->userRepository->findOneByEmail('barry@example.com'); // Retrieve a simple user to log in with.
        $owner = $this->userRepository->findOneBy(['username' => 'anonym']); // Retrieve an owner.
        $testTask = $this->taskRepository->findAll(['owner' => $owner, 'limit' => 1])[0]; // Retrieve a task of this owner.

        $this->client->loginUser($simpleUser); // Log in as the simple user.

        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_delete', ['id' => $testTask->getId()]));
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN); // Not authentificated as the owner.

        $this->client->loginUser($this->testUser); // Log in as the admin user.

        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_delete', ['id' => $testTask->getId()]));
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful(); // Admin can delete a task when the owner is the anonym user.
        $this->assertSelectorTextContains('div.alert.alert-success', "Superbe ! La tâche a bien été supprimée");
    }
}
