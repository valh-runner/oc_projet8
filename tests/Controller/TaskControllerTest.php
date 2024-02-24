<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TaskControllerTest extends WebTestCase
{
    private ?KernelBrowser $client = null;
    private ?UrlGeneratorInterface  $urlGenerator = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }

    public function tearDown(): void
    {
        $this->client = null;
        $this->urlGenerator = null;
    }

    public function testList()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_list'));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreate()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_create'));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_edit'));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testToggleTask()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_toggle'));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteTask()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_delete'));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
