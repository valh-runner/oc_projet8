<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route(path: '/', name: 'homepage')]
    public function index() : \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('default/index.html.twig');
    }
}
