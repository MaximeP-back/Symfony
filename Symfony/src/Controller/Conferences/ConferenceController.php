<?php

namespace App\Controller\Conferences;

use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConferenceController extends AbstractController
{
    private $entityManager;
    private $conferenceRepository;
    private $CommentRepository;

    public function __construct(EntityManagerInterface $entityManager, ConferenceRepository $conferenceRepository, CommentRepository $commentRepository)
    {
        $this->entityManager = $entityManager;
        $this->conferenceRepository = $conferenceRepository;
        $this->CommentRepository = $commentRepository;
    }

    #[Route('/conferences/new', name: 'new_conference')]
    public function new(): Response
    {
        return $this->render('Conferences/new.html.twig', [
            'controller_name' => 'Page de création de Conference'
        ]);
    }

    #[Route('/conference/{id}', name: 'conference_show')]
    public function show(int $id): Response
    {
        $conference = $this->conferenceRepository->find($id);
        $comments = $this->CommentRepository->findBy(['conference' => $id]);

        if (!$conference) {
            throw $this->createNotFoundException('The conference does not exist');
        }

        return $this->render('dashboard/OneConference.html.twig', [
            'conference' => $conference,
            'comments' => $comments,
        ]);
    }

    #[Route('/conferences', name: 'conferences')]
    public function index(): Response
    {
        $conferences = $this->conferenceRepository->findAll();
        return $this->render('Conferences/index.html.twig', [
            'conferences' => $conferences,
            'controller_name' => 'Page des Conferences',
        ]);
    }

    #[Route('/conferences/create', name: 'create_conference', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        $conference = new Conference();
        $conference->setCity($request->request->get('city'));
        $conference->setYear($request->request->get('year'));

        $isInternational = $request->request->get('isInternational');
        $conference->setIsInternational($isInternational !== null ? (bool)$isInternational : null);

        $errors = $validator->validate($conference);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->render('Conferences/new.html.twig', [
                'errors' => $errorMessages,
            ]);
        }

        $this->conferenceRepository->save($conference);

        return $this->redirectToRoute('conferences');
    }


    #[Route('/conferences/edit/{id}', name: 'edit_conference')]
    public function edit(int $id): Response
    {
        $conference = $this->conferenceRepository->find($id);
        if (!$conference) {
            throw $this->createNotFoundException('The conference does not exist');
        }

        return $this->render('Conferences/edit.html.twig', [
            'conference' => $conference,
            'controller_name' => 'Page de modification de Conference',
        ]);
    }

    #[Route('/conferences/update/{id}', name: 'update_conference', methods: ['POST'])]
    public function update(int $id, Request $request): Response
    {
        $conference = $this->conferenceRepository->find($id);

        if ($conference) {
            $conference->setCity($request->request->get('city'));
            $conference->setYear($request->request->get('year'));

            $isInternational = $request->request->get('isInternational');
            $conference->setIsInternational($isInternational !== null ? (bool)$isInternational : null);

            $this->conferenceRepository->update($conference);
        }

        return $this->redirectToRoute('conferences');
    }

    #[Route('/conferences/delete/{id}', name: 'delete_conference')]
    public function delete(int $id): Response
    {
        $conference = $this->conferenceRepository->find($id);
        if ($conference) {
            $this->conferenceRepository->delete($conference);
        }

        return $this->redirectToRoute('conferences');
    }
}