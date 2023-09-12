<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Controller\RequestAccess;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Utils\ErrorUtils;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }

    #[Route('/user_create_render', name: 'user_create_render')]
    public function create_render(): Response
    {
        return $this->render('user/create.html.twig');
    }

    #[Route('/user_create', name: 'user_create')]
    public function create(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $user->setEmail($request->get('email'));
        $user->setPhoneNumber($request->get('phone_number'));
        $user->setPassword($request->get('password'));
        $user->setFirstname($request->get('firstname'));
        $user->setLastname($request->get('lastname'));
        $user->setCreatedAt(new \DateTimeImmutable('now'));
        $user->setUpdatedAt(new \DateTimeImmutable('now'));
        $userRepository->save($user, true);

        return $this->redirectToRoute('app_user');
    }

    #[Route('/user/{id}', name: 'show_user')]
    public function show(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    
    #[Route('/user/delete/{id}', name: 'user_delete')]
    public function remove(UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                ErrorUtils::UserNotFound($id)
            );
        }
        $userRepository->remove($user);
        $userRepository->flush();

        
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user_search', name: 'app_user_search')]
    public function search(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $data = array();
        // dd($users);
        foreach ($users as $user) {
            $tab = array(
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'phone_number' => $user->getPhoneNumber(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
            );
            array_push($data, $tab);
        };
        // dd($data);
        return new JsonResponse(($data));
    }
}
