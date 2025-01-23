<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UtilsService;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;


class ApiUserController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
        private readonly UtilsService $utilsService,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly EmailService $emailService,
        private readonly DecoderInterface $decoder,
        private readonly SerializerInterface $serializer,
    ) {}


    //Méthode pour afficher la liste des utilisateurs
    #[Route('/api/users', name: 'app_api_users')]
    public function getAllUsers(): Response
    {
        $users = $this->userRepository->findAll();
        $code = 200;
        if (!$users) {
            $users = ["error" => "Il n'y à pas d'utilisateurs en BDD"];
            $code = 404;
        }
        return $this->json($users, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], ['groups' => "user:all"]);
    }

    //Méthode pour afficher son compte utilisateur
    #[Route('/api/me', name: 'app_api_me')]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function me(): Response
    {
        return $this->json($this->getUser(), 200, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], ["groups" => "user:me"]);
    }

    //Méthode pour créer un compte (POST avec JSON + JWT Token)
    #[Route('/api/user', name: 'app_api_user_add', methods: 'post')]
    public function addUser(Request $request): Response
    {
        $code = Response::HTTP_ACCEPTED;
        //Récupération du json
        $json = $request->getContent();
        //Test si le Json est valide
        if ($json != '""') {
            //Sérialization en Objet User + sanitize
            $user = $this->serializer->deserialize($json, User::class, 'json')->sanitizeUser();
            //test si le compte n'existe pas
            if (!$this->userRepository->findOneBy(["email" => $user->getEmail()])) {
                //Hashage du mot de passe
                $user->setPassword($this->hasher->hashPassword($user, $user->getPassword()));
                //Set du role et de l'activation
                $user->setActived(false);
                $user->setRoles(["ROLE_USER"]);
                //Ajout en BDD
                $this->em->persist($user);
                $this->em->flush();
                //Test sinon le compte existe déja
            } else {
                $code = Response::HTTP_BAD_REQUEST;
                $user = ["Error" => "Le compte existe déja"];
            }
            //Test si le compte n'existe pas
        } else {
            $code = Response::HTTP_BAD_REQUEST;
            $user = ["Error" => "JSON Incorrect"];
        }

        return $this->json(
            $user,
            $code,
            [
                "Access-Control-Allow-Origin" => "*",
                "Content-Type" => "application/json"
            ],
            ["groups" => "user:me"]
        );
    }
}
