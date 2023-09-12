<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TokenRepository;
use App\Form\TokenType;
use App\Repository\UserRepository;

class TokenController extends AbstractController
{
    #[Route('/token/{id}/{token}', name: 'app_token')]
    public function index(int $id, int $token,TokenRepository $tokenRepository, Request $request, UserRepository $userRepository): Response
    {
        $access = $tokenRepository->findOneBy(array('user_id' => $id, "token" => $token, 'type' => 1));
        if ($access) {
            $otpCode = $access->getOtpCode();
            
            $form = $this->createForm(TokenType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $checkOtpCode = $request->request->all('token')['otp_code'];
                if ( $otpCode === $checkOtpCode) {
                    $user = $userRepository->findOneBy(array('id' => $id));
                    $user->setStatus(1);
                    $userRepository->save($user, true);
                    $tokenRepository->remove($access, true);
                    



                    return $this->redirectToRoute('app_user', [
                    ]);
                } else {
                    return $this->render('token/index.html.twig', [
                        'form' => $form,
                        'res' => 2 // nécessaire pour définir que le code n'est pas correct
                    ]);
                }
            }

            return $this->render('token/index.html.twig', [
                'form' => $form,
                'res' => 0 // non nécessaire mais plante si il ne l'a pas
            ]);
        } else {
            return $this->render('err/404.html.twig', [
                'controller_name' => 'TokenController',
            ]);
        }
    }

    #[Route('/token/new_otp/{id}/{token}', name: 'app_token_new_otp')]
    public function newOtp(int $id, int $token, TokenRepository $tokenRepository): Response
    {
        $access = $tokenRepository->findOneBy(array('user_id' => $id, "token" => $token, 'type' => 1));

        if ($access) {
            $numb1 = rand(0,9);
            $numb2 = rand(0,9);
            $numb3 = rand(0,9);
            $numb4 = rand(0,9);
            $access->setOtpCode($numb1 . $numb2 . $numb3 . $numb4);
            $tokenRepository->save($access, true);

            return $this->redirectToRoute('app_token', ["id" => $id, "token" => $token]);
        } else {
            return $this->render('err/404.html.twig', [
                'controller_name' => 'TokenController',
            ]);
        }
    }
}
