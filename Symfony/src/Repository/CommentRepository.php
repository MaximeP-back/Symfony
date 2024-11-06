<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function save(Comment $comment): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    public function update(Comment $comment): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    public function delete(Comment $comment): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($comment);
        $entityManager->flush();
    }

    public function findPublishedComments(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.state = :state')
            ->setParameter('state', 'published')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPublishedCommentsByConference(int $conferenceId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.conference = :conferenceId')
            ->andWhere('c.state = :state')
            ->setParameter('conferenceId', $conferenceId)
            ->setParameter('state', 'published')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
