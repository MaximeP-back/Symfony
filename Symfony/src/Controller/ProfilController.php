<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{
#[Route('/profil', name: 'app_profil')]
public function index(Request $request): Response
{
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }

    return $this->render('Profil/profil.html.twig', [
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'password' => $_SESSION['password'],
        'created_at' => $_SESSION['created_at'],
        'id' => $_SESSION['id'],
        'admin' => $_SESSION['admin'],
        ]);
    }
}