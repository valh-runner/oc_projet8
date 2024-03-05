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

    /**
     * User list test
     *
     * @return void
     */
    public function testList(): void
    {
        $this->client->loginUser($this->testUser); // Simulate the test user being logged in.

        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_list'));
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextContains('th', "Nom d'utilisateur");
        $this->assertAnySelectorTextContains('th', "Adresse d'utilisateur");
    }

    /**
     * User create test
     *
     * @return void
     */
    public function testCreate(): void
    {
        $this->client->loginUser($this->testUser); // Simulate the test user being logged in.

        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create'));

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'vision';
        $form['user[password][first]'] = '3v0lv3D*';
        $form['user[password][second]'] = '3v0lv3D*';
        $form['user[email]'] = 'vision@example.com';
        $form['user[roles]'] = 'ROLE_USER';

        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert.alert-success', "Superbe ! L'utilisateur a bien été ajouté");
    }

    /**
     * User edit test
     *
     * @return void
     */
    public function testEdit(): void
    {
        $this->client->loginUser($this->testUser); // Simulate the test user being logged in.

        $userToEdit = $this->userRepository->findOneBy(['username' => 'barry']); // Retrieve a user.

        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $userToEdit->getId()]));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'barryOne';
        $form['user[password][first]'] = '8T0d0c0';
        $form['user[password][second]'] = '8T0d0c0';
        $form['user[email]'] = 'barry_one@example.com';
        $form['user[roles]'] = 'ROLE_ADMIN';
        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertSelectorTextContains('div.alert.alert-success', "Superbe ! L'utilisateur a bien été modifié");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $editedUser = $this->userRepository->findOneByEmail('barry_one@example.com'); // Retrieve the test user.
        $this->assertSame('barryOne', $editedUser->getUsername());
    }
}
