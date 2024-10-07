<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/accueil', name: 'app_main')]
    public function index(Request $request): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start ();
        }
        return $this->render('Blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'user' => [
                'id' => $_SESSION['id'],
                'username' => $_SESSION['username'],
                'admin' => $_SESSION['admin'] ? 'admin' : 'user'
            ]
        ]);

    }
}