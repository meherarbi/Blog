<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;

class HomeController extends AbstractController
{
    #[Route('/{page}', name: 'home', requirements: ['page' => '\d+'])]
    public function index(ArticleRepository $articleRepository, int $page = 1): Response
    {
        $article = $articleRepository->findForHome($page);
        $total = count($article);
        $hasNext = ($page * 2) < $total;

        return $this->render('home/index.html.twig', [
            'article' => $article,
            'currentPage' => $page,
            'hasPrevious' => $page > 1,
            'hasNext' => $hasNext,
        ]);
    }
}
