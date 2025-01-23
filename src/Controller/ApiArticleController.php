<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Service\UtilsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiArticleController extends AbstractController
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly EntityManagerInterface $em,
        private readonly UtilsService $utilsService,
        private readonly SerializerInterface $serializer
    ) {}

    //Méthode pour afficher les articles avec les commentaires, catégories, auteur
    #[Route('/api/articles', name: 'app_articles')]
    #[IsGranted("ROLE_USER")]
    public function getArticles(): Response
    {
        $articles = $this->articleRepository->findAll();
        $code = 200;
        //test si il y a des articles en BDD
        if (!$articles) {
            $articles = ["erreur" => "la liste est vide"];
            $code = 400;
        }
        return $this->json($articles, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], ['groups' => 'article:fullAll']);
    }

    #[Route('/api/article', name: 'app_api_add_article')]
    #[IsGranted('ROLE_USER')]
    public function addArticle(Request $request)
    {
        $json = $request->getContent();
        return $this->json(["ok"]);
    }
}
