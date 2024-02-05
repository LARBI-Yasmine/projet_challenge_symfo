<?php

namespace App\Controller\Backend;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/event')]
class EventController extends AbstractController
{
    //List evenements
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository,Request $request,PaginatorInterface $paginator): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $events = $eventRepository->findAll();   
        } else {
            $events = $eventRepository->findBy(['user' => $this->getUser()]);   
        }

        return $this->render('Backend/Admin/adminAllEvents.html.twig', [
            'events' => $events,
        ]);
    }


    #[Route('/index', name: 'event_index', methods: ['GET'])]
    public function event_index(EventRepository $eventRepository,Request $request,PaginatorInterface $paginator): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $events = $eventRepository->findAll();   
        } else {
            $events = $eventRepository->findBy(['user' => $this->getUser()]);   
        }

        return $this->render('Backend/Event/index.html.twig', [
            'events' => $events,
        ]);
    }


    

    //creer un nouveau evenement
    #[Route('/creer', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event->setUser($this->getUser());
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Backend/Event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }



    //detail d'un evenement
    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('Backend/Event/show.html.twig', [
            'event' => $event,
        ]);
    }


    //editer un evenement
    #[Route('/{id}/modifier', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Backend/Event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }


    //supprimer un evenement
    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }
}