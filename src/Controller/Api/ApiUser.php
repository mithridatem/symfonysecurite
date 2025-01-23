<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UtilsService;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class ApiUser extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
        private readonly UtilsService $utilsService,
        private readonly EmailService $emailService
    ) {}


    //Méthode pour afficher la liste des utilisateurs
    #[Route('/api/users', name:'app_api_users')]
    public function getAllUsers() :Response {
        $users = $this->userRepository->findAll();
        $code = 200;
        if(!$users){
            $users = ["error"=>"Il n'y à pas d'utilisateurs en BDD"];
            $code = 404;
        }
        return $this->json($users, $code ,[
            "Access-Control-Allow-Origin"=> "*",
            "Content-Type"=>"application/json"
        ], ['groups'=>"user:all"]);
    }

    //Méthode pour afficher son compte utilisateur
    #[Route('/api/me', name:'app_api_me')]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function me() :Response{
        return $this->json($this->getUser(),200,[
            "Access-Control-Allow-Origin"=> "*",
            "Content-Type"=>"application/json"
        ], ['groups'=>'user:me']);
    }

    
}
