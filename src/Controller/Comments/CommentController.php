<?php

namespace App\Controller\Comments;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    private $entityManager;
    private $commentRepository;

    public function __construct(EntityManagerInterface $entityManager, CommentRepository $commentRepository)
    {
        $this->entityManager = $entityManager;
        $this->commentRepository = $commentRepository;
    }

    #[Route('/comments', name: 'comments')]
    public function index(): Response
    {
        $comments = $this->commentRepository->findAll();
        return $this->render('Comments/index.html.twig', [
            'comments' => $comments,
            'controller_name' => 'Page des commentaires',
        ]);
    }

    #[Route('/comments/delete/{id}', name: 'delete_comment')]
    public function delete(int $id): Response
    {
        $comment = $this->commentRepository->find($id);
        if ($comment) {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('comments');
    }
}