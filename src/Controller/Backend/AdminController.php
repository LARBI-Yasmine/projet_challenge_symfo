<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




#[Route('/admin')]


class AdminController extends AbstractController
{






    #[Route('/utilisateurs', name: 'app_utilisateurs')]
    public function listUsers(UserRepository  $users,Request $request, PaginatorInterface $paginator): Response
    {

        $pagination =  $paginator->paginate(
            $users->paginationQuery(),$request->query->get('page',1),5);
        return $this->render('/Backend/Admin/user/index.html.twig',[

            'pagination' => $pagination,
            'users'=> $users->findAll(),
           
            
        ]);

    }




    #[Route('/utilisateur/modifier/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateurs', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Backend/Admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/user/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('Backend/Admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateurs', [], Response::HTTP_SEE_OTHER);
    }






}