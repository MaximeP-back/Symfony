<?php

namespace App\Controller;

use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PostController extends AbstractController
{

    #[Route('/posts', name: 'app_post_view_all')]
    public function showall(Request $request, PostsRepository $PostsRepository ): Response
    {
        $posts = $PostsRepository->findAll();
        return $this->render('Posts/PostPage.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts
        ]);
    }

    #[Route('/posts/create', name: 'app_post_create')]
    public function create(Request $request, PostsRepository $postsRepository, SluggerInterface $slugger): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $title = $request->request->get('title');
        $content = $request->request->get('content');
        $author = $_SESSION['username'];
        $images = [];

        if (!$title || !$content || !$author) {
            var_dump($title, $content, $author);
            throw $this->createNotFoundException('Missing data for post creation');
        }

        $uploadedFiles = $request->files->get('img');
        if ($uploadedFiles) {
            foreach ($uploadedFiles as $uploadedFile) {

                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), flags:PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename)->toString();
                var_dump ($safeFilename);

                try {
                    $uploadedFile->move(
                        $this->getParameter(name:'images_directory'),
                        $safeFilename
                    );
                    $images[] = $safeFilename;
                } catch (FileException $e) {
                    throw $this->createNotFoundException($e);
                }
            }
        }
        $postsRepository->createPost($title, $content, $author, $images);

        return $this->redirectToRoute('app_post_view_all');
    }

    #[Route('/posts/new', name: 'app_post_create_form')]
    public function showForm(Request $request): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start ();
            var_dump ($_SESSION);
        }

        var_dump ($_SESSION);
        return $this->render('Posts/CreatePost.html.twig', [
            'controller_name' => 'PostController',

        ]);
    }




}
