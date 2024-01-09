<?php

namespace App\Controller\Frontend;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(
        private EventRepository $eventRepo,
    ) {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        
        return $this->render('Frontend/Home/index.html.twig', [
            'events' => $this->eventRepo->findLastEvents(4)
        ]);
    }
}
