<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\EmailService;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
          
        ]);
    }

    #[Route('/test', name:'app_home_test')]
    public function testSmtp(EmailService $emailService) :Response {
        $emailService->sendEmail("mathieumithridate@adrar-formation.com", "Test email", "<h1>Test d'envoi de mail</h1>");
        return new Response();
    }
}
