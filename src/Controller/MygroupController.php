<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MygroupController extends AbstractController
{
    #[Route('/mygroup', name: 'app_mygroup')]
    public function index(): Response
    {
        return $this->render('group/mygroup.html.twig', [
            'controller_name' => 'MygroupController',
        ]);
    }
}
