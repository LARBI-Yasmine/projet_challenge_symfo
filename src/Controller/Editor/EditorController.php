<?php

namespace App\Controller\Editor;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditorController extends AbstractController
{
    #[Route('/editor', name: 'app_editor')]
    public function index(): Response
    {
        return $this->render('Editor/index.html.twig');
    }
}
