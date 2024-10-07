<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConferenceController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(Request $request): Response
    {
        $greet = '';
        if ($name = $request->query->get('hello')) {
            $greet = sprintf('Hello %s!', htmlspecialchars($name));
        }

        return $this->render('conference/index.html.twig', [
            'controller_name' => 'ConferenceController',
            'greet' => $greet,
        ]);
    }
}
