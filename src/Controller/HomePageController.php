<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/home')]
class HomePageController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'home')]
    public function index(Request $request, UserRepository $users): Response
    {
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        $users = $users->findAll();

        $results = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->getData()['query'];

            // récupérer les résultats de recherche en utilisant votre logique d'application
            $results = $this->entityManager
                ->getRepository(User::class)
                ->search($query);

        }

        return $this->render('home/index.html.twig', [
            'contoller_name' => 'UserController',
            'users' => $users,
            'form' => $form->createView(),
            'results' => $results,
        ]);
    }

}