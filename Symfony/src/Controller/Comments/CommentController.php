<?php

namespace App\Controller\Comments;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentController extends AbstractController
{
    private $entityManager;

    private $commentRepository;

    public function __construct(EntityManagerInterface $entityManager, CommentRepository $commentRepository)
    {
        $this->entityManager = $entityManager;
        $this->commentRepository = $commentRepository;
    }

    #[Route('/Comment/new', name: 'new_comment')]
    public function new(): Response
    {
        return $this->render('Comments/new.html.twig', [
            'controller_name' => 'Page de création de Commentaire',
        ]);
    }

    #[Route('/Comment/edit/{id}', name: 'edit_comment')]
    public function edit(int $id): Response
    {
        $comment = $this->commentRepository->find($id);
        if (!$comment) {
            throw $this->createNotFoundException('The comment does not exist');
        }

        return $this->render('Comments/edit.html.twig', [
            'comment'         => $comment,
            'controller_name' => 'Page de modification de Commentaires',
        ]);
    }

    #[Route('/comments', name: 'comments')]
    public function index(): Response
    {
        $comments = $this->commentRepository->findAll();

        return $this->render('Comments/index.html.twig', [
            'comments'        => $comments,
            'controller_name' => 'Page des commentaires',
        ]);
    }

    #[Route('/comments/create', name: 'create_comment', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator, ConferenceRepository $conferenceRepository): Response
    {
        $comment = new Comment();
        $comment->setAuthor($request->request->get('author'));
        $comment->setEmail($request->request->get('email'));
        $comment->setPhotoFilename($request->request->get('photoFilename'));
        $comment->setCreatedAt(new \DateTime());

        $conference = $conferenceRepository->find($request->request->get('conference_id'));
        if ($conference) {
            $comment->setConference($conference);
        } else {
            return $this->render('Comments/new.html.twig', [
                'errors' => ['conference' => 'Conference not found'],
            ]);
        }

        $errors = $validator->validate($comment);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->render('Comments/new.html.twig', [
                'errors' => $errorMessages,
            ]);
        }

        $this->commentRepository->save($comment);

        return $this->redirectToRoute('comments');
    }

    #[Route('/comments/update/{id}', name: 'update_comment', methods: ['POST'])]
    public function update(int $id, Request $request, ValidatorInterface $validator, ConferenceRepository $conferenceRepository): Response
    {
        $comment = $this->commentRepository->find($id);
        if ($comment) {
            $comment->setAuthor($request->request->get('author'));
            $comment->setEmail($request->request->get('email'));
            $comment->setPhotoFilename($request->request->get('photoFilename'));

            $conference = $conferenceRepository->find($request->request->get('conference_id'));
            if ($conference) {
                $comment->setConference($conference);
            } else {
                // Handle the case where the conference is not found
                return $this->render('Comments/edit.html.twig', [
                    'comment' => $comment,
                    'errors'  => ['conference' => 'Conference not found'],
                ]);
            }

            $errors = $validator->validate($comment);

            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return $this->render('Comments/edit.html.twig', [
                    'comment' => $comment,
                    'errors'  => $errorMessages,
                ]);
            }

            $this->commentRepository->save($comment);
        }

        return $this->redirectToRoute('comments');
    }

    #[Route('/comments/delete/{id}', name: 'delete_comment')]
    public function delete(int $id): Response
    {
        $comment = $this->commentRepository->find($id);
        if ($comment) {
            $this->commentRepository->delete($comment);
        }

        return $this->redirectToRoute('comments');
    }
}
