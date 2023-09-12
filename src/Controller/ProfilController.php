<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil')]
class ProfilController extends AbstractController
{
    #[Route('/{id}', name: 'app_profil')]
    public function index(int $id, Request $request, UserRepository $userRepository): Response
    {   
        $user = $userRepository->find($id);
        // $firstname = $request -> request -> get('firstname');
        // $lastname =$request -> request -> get('lastname');
        // $pseudo = $request -> request -> get('pseudo');
        // $digicode = $request -> request -> get('digicode');
        // $address_1 = $request -> request -> get('address_1');
        // $address_2 = $request -> request -> get('address_2');
        // $address_3 = $request -> request -> get('address_3');
        $cb1 = $request -> request -> all('cb_1');
        
        if ($cb1) {
            if(count($cb1) === 4) {
                $cb1['name'];
            } else {
                return $this->redirectToRoute('app_profil_edit', [
                    "err" => "cb_1leng",
                ]);
            }
        }
        $cb2 = $request -> request -> all('cb_2');
        if ($cb2) {
            if(count($cb2) === 4) {
                $cb2['name'];
            } else {
                return $this->redirectToRoute('app_profil_edit', [
                    "err" => "cb_2leng",
                ]);
            }
        }
        

        return $this->render('profil/profil.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_profil_edit')]
    public function edit(int $id, Request $request, UserRepository $userRepository): Response  
    {

        $user = $userRepository->find($id);

        return $this->render('profil/editprofil.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
            'err' => 0
        ]);
    }

    #[Route('/SecurityProfil/{id}', name: 'app_profil_security')]
    public function security(int $id, Request $request, UserRepository $userRepository): Response  
    {

        $user = $userRepository->find($id);

        return $this->render('profil/securityprofil.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
            'err' => 0
        ]);
    }
}
