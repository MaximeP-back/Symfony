<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use http\Env\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Users>
 */

class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function show(usersRepository $repository): Response
    {
        $users = $repository->findAll();
        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }

    public function edit (EntityManagerInterface $em, Request $request): Response
    {
        $em->flush();
        return $this->redirectToRoute('app_user');
    }

public function remove(EntityManagerInterface $em, int $id): Response
{
    $user = $em->getRepository(Users::class)->find($id);
    if ($user) {
        $em->remove($user);
        $em->flush();
    }
    return $this->redirectToRoute('app_user');
}

}
