<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/Connexion', name: 'app_Login')]
    public function index(Request $request): Response
    {
        return $this->render('Login/Login.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    #[Route('/users/login', name: 'app_user_login')]
    public function login(Request $request, UsersRepository $usersRepository): Response
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $user = $usersRepository->findOneBy(['username' => $username, 'password' => $password]);

        if ($username === 'admin' && $password === 'admin') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['admin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            return $this->redirectToRoute('app_main');
        }elseif ($user) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['admin'] = false;
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['email'] = $user -> getEmail();
            $_SESSION['created_at'] = $user -> getCreatedAt();
            $_SESSION['id'] = $user->getId ();
            return $this->redirectToRoute('app_main');
        }else {
            return $this->redirectToRoute('app_Login');
        }
    }

}
