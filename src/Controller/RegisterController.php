<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\RegisterType;
use App\Service\UtilsService;
use Doctrine\ORM\EntityManagerInterface;

final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em,
        private readonly UtilsService $utilsService
    ){}

    #[Route('/register', name: 'app_register')]
    public function addUser(Request $request): Response
    {
        //Créer un objet User
        $user = new User();
        //Créer un formulaire
        $form = $this->createForm(RegisterType::class, $user);
        //Lier l'objet Request au formulaire
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            dd($form->getData());
            $user->setRoles(["ROLE_USER"]);
            $user->setActived(false);
            /* dd($user); */
        }

        return $this->render('register/addUser.html.twig', [
            'formulaire' => $form,
        ]);
    }
}
