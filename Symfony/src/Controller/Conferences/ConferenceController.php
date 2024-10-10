<?php

namespace App\Controller\Conferences;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{
    private $conferenceRepository;

    public function __construct(ConferenceRepository $conferenceRepository)
    {
        $this->conferenceRepository = $conferenceRepository;
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

    #[Route('/conference/{id}', name: 'conference_show')]
    public function show(int $id): Response
    {
        $conference = $this->conferenceRepository->find($id);
        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
        ]);
    }
}