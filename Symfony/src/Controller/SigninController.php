<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SigninController extends AbstractController
{
    #[Route('/Inscription', name: 'app_Signin')]
    public function index(Request $request): Response
    {
        return $this->render('Signin/Signin.html.twig', [
            'controller_name' => 'SigninController',
        ]);
    }

    #[Route('/users/create', name: 'app_user_create')]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $username = $request->get('username');
        $mail = $request->get('email');
        $password = $request->get('password');

        switch (true) {
            case !$username || !$mail || !$password:
                throw $this->createNotFoundException('Missing data for user creation');
            case !preg_match('/^([a-zA-Z0-9_\-\.])+@([a-zA-Z0-9_\-\.])+\.([a-zA-Z0-9_\-\.]){2,5}$/', $mail):
                throw $this->createNotFoundException('Invalid Mail');
            case !preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,16}$/', $password):
                throw $this->createNotFoundException('Invalid Password');
        }

        $user = (new Users())
            ->setUsername($username)
            ->setEmail($mail)
            ->setPassword($password)
            ->setCreatedAt(new \DateTimeImmutable());

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_main', ['id' => $user->getId(), 'username' => $user->getUsername()]);
    }
}