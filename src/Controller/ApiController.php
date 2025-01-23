<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ApiController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ArticleRepository $articleRepository
    ) {}
    
    //Méthode pour afficher les articles avec les commentaires, catégories, auteur
    #[Route('/api/articles', name:'app_articles_full')]
    #[IsGranted("ROLE_USER")]
    public function getArticleFull() :Response {
        $articles = $this->articleRepository->findAll();
        $code = 200;
        //test si il y a des articles en BDD
        if(!$articles) {
            $articles = ["erreur"=>"la liste est vide"];
            $code = 400;
        }
        return $this->json($articles, $code, [
            "Access-Control-Allow-Origin"=> "*",
            "Content-Type"=>"application/json"
        ], ['groups'=>'article:fullAll']);
    }
}
