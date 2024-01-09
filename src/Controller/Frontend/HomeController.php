<?php

namespace App\Controller\Frontend;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(
        private EventRepository $eventRepo,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'app_home', methods:['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('Frontend/Home/index.html.twig', [
            'events' => $this->eventRepo->findLastEvents(4),
            'user' => $user
        ]);
    }

    #[Route('/allEvents', name: 'app_all_events')]
    public function showAllEvents(): Response
    {
        $user = $this->getUser();
        if ($user) {
            return $this->render('Frontend/Home/allEvents.html.twig', [
                'events' => $this->eventRepo->findAll(),
                'user' => $user
            ]);
        }
        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/events', name: 'app_events')]
    public function showUserEvents(): Response
    {

        $user = $this->getUser();
        if ($user) {
            $userEvents = $user->getUserParticipate();
            return $this->render('Frontend/Home/events.html.twig', [
                'events' => $userEvents
            ]);
        }
        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }

    // EntityManagerInterface $entityManager
    
    #[Route('/eventParticipate', name: 'app_event_participate', methods:['GET', 'POST'])]
    public function eventParticipate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $event = $this->eventRepo->find($request->request->get('id', 0));
        if ($this->isCsrfTokenValid('participate' . $event->getId(), $request->request->get('token'))) {
            if ($event) {
                $user->addUserParticipate($event);
                $event->setParticipate(True);
                $this->entityManager->flush();
                // $this->addFlash('success', 'Participation prise en compte');
    
                return $this->redirectToRoute('app_events');
            }
        }

        $this->addFlash('error', 'Token CSRF invalide');

        return $this->redirectToRoute('app_events');
    }
    
    #[Route('/eventWithdraw', name: 'app_event_withdraw', methods:['GET', 'POST'])]
    public function eventWithdraw(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $event = $this->eventRepo->find($request->request->get('id', 0));
        if ($this->isCsrfTokenValid('withdraw' . $event->getId(), $request->request->get('token'))) {
            if ($event) {
                $user->removeUserParticipate($event);
                $event->setParticipate(False);
                $this->entityManager->flush();
                // $this->addFlash('success', 'Participation prise en compte');
    
                return $this->redirectToRoute('app_events');
            }
        }

        $this->addFlash('error', 'Token CSRF invalide');

        return $this->redirectToRoute('app_events');
    } 
}
