<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultControllerTest extends WebTestCase
{

    private ?KernelBrowser $client = null;

    private ?UrlGeneratorInterface  $urlGenerator = null;

    public function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }

    public function tearDown(): void
    {
        unset($this->client);
        unset($this->urlGenerator);
    }

    /**
     * Index test
     *
     * @return void
     */
    public function testIndex(): void
    {
        $userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        $testUser = $userRepository->findOneByEmail('jarvis@example.com'); // Retrieve the test user.

        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('homepage'));
        $this->assertResponseRedirects();

        $this->client->loginUser($testUser); // Simulate the test user being logged in.

        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('homepage'));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List");
    }
}
