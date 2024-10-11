<?php

namespace App\Repository;

use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Conference|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conference|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conference[]    findAll()
 * @method Conference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    public function save(Conference $conference): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($conference);
        $entityManager->flush();
    }

    public function update(Conference $conference): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($conference);
        $entityManager->flush();
    }

    public function delete(Conference $conference): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($conference);
        $entityManager->flush();
    }
}