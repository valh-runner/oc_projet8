<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityControllerTest extends WebTestCase
{

    private ?KernelBrowser $client = null;

    private ?UrlGeneratorInterface  $urlGenerator = null;

    private ?EntityRepository $userRepository = null;

    public function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
    }

    public function tearDown(): void
    {
        unset($this->client);
        unset($this->urlGenerator);
        unset($this->userRepository);
    }

    public function testLogin(): void
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('login'));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('button', 'Se connecter');
    }

    public function testLoginCheck(): void
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('login_check'));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testLogout(): void
    {
        $testUser = $this->userRepository->findOneByEmail('jarvis@example.com'); // Retrieve the test user.
        $this->client->loginUser($testUser); // Simulate the test user being logged in.

        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('logout'));
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
    }
}
