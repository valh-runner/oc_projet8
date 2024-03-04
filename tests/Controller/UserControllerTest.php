<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserControllerTest extends WebTestCase
{

    private ?KernelBrowser $client = null;
    private ?UrlGeneratorInterface  $urlGenerator = null;
    private ?EntityRepository $userRepository = null;
    private ?User $testUser = null;

    public function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        $this->testUser = $this->userRepository->findOneByEmail('jarvis@example.com'); // Retrieve the test user.
    }

    public function tearDown(): void
    {
        unset($this->client, $this->urlGenerator, $this->urlGenerator, $this->testUser);
    }

    public function testList(): void
    {
        $this->client->loginUser($this->testUser); // Simulate the test user being logged in.

        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_list'));
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextContains('th', "Nom d'utilisateur");
        $this->assertAnySelectorTextContains('th', "Adresse d'utilisateur");
    }

    public function testCreate(): void
    {
        $this->client->loginUser($this->testUser); // Simulate the test user being logged in.

        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create'));

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'vision';
        $form['user[password][first]'] = '3v0lv3D*';
        $form['user[password][second]'] = '3v0lv3D*';
        $form['user[email]'] = 'vision@example.com';

        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert.alert-success', "Superbe ! L'utilisateur a bien été ajouté");
    }

    public function testEdit(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $this->testUser->getId()]));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'vision';
        $form['user[password][first]'] = '3v0lv3D*';
        $form['user[password][second]'] = '3v0lv3D*';
        $form['user[email]'] = 'vision@example.com';
        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertSelectorTextContains('div.alert.alert-success', "Superbe ! L'utilisateur a bien été modifié");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $editedUser = $this->userRepository->findOneByEmail('vision@example.com'); // Retrieve the test user.
        $this->assertSame('vision', $editedUser->getUsername());
        $this->assertSame('vision@example.com', $editedUser->getEmail());
    }
}
