<?php

namespace App\Controller\Admin;

use App\Repository\ConferenceRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    private ConferenceRepository $conferenceRepository;
    private CommentRepository $commentRepository;

    public function __construct(ConferenceRepository $conferenceRepository, CommentRepository $commentRepository)
    {
        $this->conferenceRepository = $conferenceRepository;
        $this->commentRepository = $commentRepository;
    }

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(): Response
    {
        $conferences = $this->conferenceRepository->findAll();
        $comments = $this->commentRepository->findAll();

        return $this->render('Admin/dashboard.html.twig', [
            'conferences' => $conferences,
            'comments' => $comments,
            'controller_name' => 'AdminDashboardController',
        ]);
    }
}