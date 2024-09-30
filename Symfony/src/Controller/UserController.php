<?php
//
//namespace App\Controller\bannis;
//
//use App\Entity\Users;
//use App\Repository\UsersRepository;
//use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//
//class UserController extends AbstractController
//{
//    #[Route('/users', name: 'app_user')]
//    public function index(UsersRepository $usersRepository): Response
//    {
//        $users = $usersRepository->findAll();
//
//        return $this->render('users/index.html.twig', [
//            'controller_name' => 'UserController',
//            'Repository_name' => 'UsersRepository',
//            'users' => $users
//        ]);
//    }
//
//    #[Route('/users/create', name: 'app_user_create')]
//    public function create( EntityManagerInterface $em , Request $request ): Response
//    {
//        $username = $request->get('username');
//        $mail = $request->get('email');
//        $password = $request->get('password');
//
//        switch (true) {
//            case !$username || !$mail || !$password:
//                throw $this->createNotFoundException('Missing data for user creation');
//            case !preg_match('/^([a-zA-Z0-9_\-\.])+@([a-zA-Z0-9_\-\.])+\.([a-zA-Z0-9_\-\.]){2,5}$/', $mail):
//                throw $this->createNotFoundException('Invalid Mail');
//            case !preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,16}$/', $password):
//                throw $this->createNotFoundException('Invalid Password');
//        }
//
//        $user = (new Users())
//            ->setUsername( $username,'John Doe')
//            ->setEmail( $mail,'john@john.com')
//            ->setPassword($password,'password@pass-/*@')
//            ->setCreatedAt(new \DateTimeImmutable());
//
//        $em->persist($user);
//        $em->flush();
//
//        return $this->redirectToRoute('app_user');
//    }
//
//    #[Route('/users/edit/{id}', name: 'app_user_edit')]
//    public function edit(int $id, UsersRepository $usersRepository, EntityManagerInterface $em, Request $request): Response
//    {
//        $user = $usersRepository->find($id);
//        if (!$user) {
//            throw $this->createNotFoundException('No user found for id ' . $id);
//        }
//
//        $username = $request->get('username');
//        $mail = $request->get('email');
//        $password = $request->get('password');
//
//        if (!$username || !$mail || !$password) {
//            throw $this->createNotFoundException('Missing data for user creation');
//        }
//
//        $user->setUsername($username);
//        $user->setEmail($mail);
//        $user->setPassword($password);
//
//        $em->persist($user);
//        $em->flush();
//
//        return $this->redirectToRoute('app_user');
//    }
//
//    #[Route('/users/delete/{id}', name: 'app_user_delete')]
//    public function delete(int $id, UsersRepository $usersRepository, EntityManagerInterface $em): Response
//    {
//        $user = $usersRepository->find($id);
//        if (!$user) {
//            throw $this->createNotFoundException('No user found for id ' . $id);
//        }
//
//        $em->remove($user);
//        $em->flush();
//
//        return $this->redirectToRoute('app_user');
//    }
//}