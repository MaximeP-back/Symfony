<?php

namespace App\Repository;

use App\Entity\Posts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Posts>
 */
class PostsRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder(alias:'p')
            ->orderBy(sort:'p.id', order: 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function createPost(string $title, string $content, string $author, array $images): Posts
    {
        $post = (new Posts())
            ->setTitle($title)
            ->setText($content)
            ->setAuthor($author)
            ->setCreatedDate(new \DateTimeImmutable())
            ->setPhoto($images);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

}
