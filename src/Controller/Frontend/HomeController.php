<?php

namespace App\Controller\Frontend;

use App\Entity\Event;
use App\Entity\Booking;
use App\Repository\EventRepository;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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

    // 4 evenements à venir
    #[Route('/', name: 'app_home', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('Frontend/Home/accueil.html.twig', [
            'events' => $this->eventRepo->findLastEvents(4),
        ]);
    }


    // //tous les evenements
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


    //evenement pour participer
    #[Route('/events', name: 'app_events')]
    public function showUserEvents(): Response
    {

        $user = $this->getUser();
        if ($user) {
            $userEvents = $user->getUserParticipate();
            return $this->render('Frontend/Home/events.html.twig', [
                'bookings' => $user->getBookings()
            ]);
        }
        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }

    // action  participer
    
    #[Route('/eventParticipate', name: 'app_event_participate', methods:['GET', 'POST'])]
    public function eventParticipate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $event = $this->eventRepo->find($request->request->get('id', 0));
        if ($this->isCsrfTokenValid('participate' . $event->getId(), $request->request->get('token'))) {
            if ($event instanceof Event) {
                $booking = (new Booking())
                    ->setUser($user)
                    ->setEvent($event);
                $this->entityManager->persist($booking);
                return $this->redirectToRoute('app_events');
            }
        }

        $this->addFlash('error', 'Token CSRF invalide');

        return $this->redirectToRoute('app_events');
    }
    
//desister un evenement 
    
    #[Route('/eventWithdraw', name: 'app_event_withdraw', methods:['GET', 'POST'])]
    public function eventWithdraw(Request $request,BookingRepository $bookingRepository, EntityManagerInterface $entityManager): Response
    {
        $booking = $bookingRepository->find($request->request->get('id', 0));
        if ($this->isCsrfTokenValid('withdraw' . $booking->getId(), $request->request->get('token'))) {
            $booking->setStatus(Booking::BOOKING_STATUS_CANCELLED);
            $this->entityManager->flush();
                return $this->redirectToRoute('app_events');
            }
        

        $this->addFlash('error', 'Token CSRF invalide');

        return $this->redirectToRoute('app_events');
    } 
}