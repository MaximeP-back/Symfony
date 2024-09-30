<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/Inscription', name: 'app_Signin')]
    public function Inscription(Request $request): Response
    {
        return $this->render('Signin/Signin.html.twig', [
            'controller_name' => 'SigninController',
        ]);
    }

    #[Route('/Connexion', name: 'app_Login')]
    public function Connexion(Request $request): Response
    {
        return $this->render('Login/Login.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}
