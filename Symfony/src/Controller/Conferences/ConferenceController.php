<?php

namespace App\Controller\Conferences;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use App\SpamChecker;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConferenceController extends AbstractController
{
    private $entityManager;

    private $conferenceRepository;

    private $commentRepository;

    private $photoDir;

    private $logger;

    public function __construct(EntityManagerInterface $entityManager, ConferenceRepository $conferenceRepository, CommentRepository $commentRepository, string $photoDir, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->conferenceRepository = $conferenceRepository;
        $this->commentRepository = $commentRepository;
        $this->photoDir = $photoDir;
        $this->logger = $logger;
    }

    #[Route('/conferences/new', name: 'new_conference')]
    public function new(): Response
    {
        return $this->render('Conferences/new.html.twig', [
            'controller_name' => 'Page de création de Conference',
        ]);
    }

    #[Route('/conference/{id}', name: 'conference_show')]
    public function show(int $id, Request $request, Conference $conference, CommentRepository $commentRepository, SpamChecker $spamChecker): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setConference($this->conferenceRepository->find($id));
            $comment->setCreatedAt(new \DateTime());
            $comment->setEmail($form['email']->getData());
            $comment->setAuthor($form['author']->getData());
            $comment->setText($form['text']->getData());

            if ($photo = $form['photo']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                $photo->move($this->photoDir, $filename);
                $comment->setPhotoFilename($filename);
            }

            $context = [
                'user_ip'    => $request->getClientIp(),
                'user_agent' => $request->headers->get('user-agent'),
                'referrer'   => $request->headers->get('referer'),
                'permalink'  => $request->getUri(),
            ];

            $this->logger->info('Spam check context', $context);

            $spamScore = $spamChecker->getSpamScore($comment, $context);

            if (0 === $spamScore) {
                $this->addFlash('success', 'Comment was submitted for moderation');
                $this->entityManager->persist($comment);
                $this->entityManager->flush();

                return $this->redirectToRoute('conference_show', ['id' => $id]);
            }
            if (1 === $spamScore) {
                $this->addFlash('success', 'Comment was marked as spam');

                return $this->redirectToRoute('conference_show', ['id' => $id]);
            }
            if (2 === $spamScore) {
                $this->addFlash('error', 'Blatant spam, go away!');

                return $this->redirectToRoute('conference_show', ['id' => $id]);
            }

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('conference_show', ['id' => $id]);
        }

        $conference = $this->conferenceRepository->find($id);
        $comments = $this->commentRepository->findBy(['conference' => $id]);

        if (!$conference) {
            throw $this->createNotFoundException('The conference does not exist');
        }

        return $this->render('dashboard/OneConference.html.twig', [
            'conference'   => $conference,
            'comments'     => $comments,
            'comment_form' => $form,
        ]);
    }

    #[Route('/conferences', name: 'conferences')]
    public function index(): Response
    {
        $conferences = $this->conferenceRepository->findAll();

        return $this->render('Conferences/index.html.twig', [
            'conferences'     => $conferences,
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
        $conference->setIsInternational($isInternational !== null ? (bool) $isInternational : null);

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
            'conference'      => $conference,
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
            $conference->setIsInternational($isInternational !== null ? (bool) $isInternational : null);

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
