<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * Homepage
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * Login check
     *
     * @return void
     */
    #[Route(path: '/login_check', name: 'login_check')]
    public function loginCheck(): void
    {
        // This code is never executed.
    }

    /**
     * Logout
     *
     * @return void
     */
    #[Route(path: '/logout', name: 'logout')]
    public function logoutCheck(): void
    {
        // This code is never executed.
    }
}
