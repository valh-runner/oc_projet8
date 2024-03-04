<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    /**
     * User list
     *
     * @param EntityManagerInterface $entityManager Entity manager
     * @return Response
     */
    #[Route(path: '/users', name: 'user_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('user/list.html.twig', ['users' => $entityManager->getRepository(User::class)->findAll()]);
    }

    /**
     * User create
     *
     * @param Request $request Request
     * @param UserPasswordHasherInterface $userPasswordHasher Password hasher tool
     * @return Response
     */
    #[Route(path: '/users/create', name: 'user_create')]
    public function create(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $userPasswordHasher->hashPassword($user, $user->getPassword());

            $user->setPassword($password);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * User edit
     *
     * @param User $user User
     * @param Request $request Request
     * @param UserPasswordHasherInterface $userPasswordHasher Password hasher tool
     * @param EntityManagerInterface $entityManager Entity manager
     * @return Response
     */
    #[Route(path: '/users/{id}/edit', name: 'user_edit')]
    public function edit(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
