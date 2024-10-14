<?php

namespace App\Controller\Dashboard;

use App\Repository\ConferenceRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    private ConferenceRepository $conferenceRepository;
    private CommentRepository $commentRepository;

    public function __construct(ConferenceRepository $conferenceRepository, CommentRepository $commentRepository)
    {
        $this->conferenceRepository = $conferenceRepository;
        $this->commentRepository = $commentRepository;
    }

    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        $conferences = $this->conferenceRepository->findAll();
        $conferenceComments = [];

        foreach ($conferences as $conference) {
            $conferenceComments[$conference->getId()] = $this->commentRepository->findBy(
                ['conference' => $conference->getId()],
                null,
                2
            );
        }

        return $this->render('dashboard/index.html.twig', [
            'conferences' => $conferences,
            'conferenceComments' => $conferenceComments,
        ]);
    }
}