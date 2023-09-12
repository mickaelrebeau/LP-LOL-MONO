<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use App\Services\ServiceSendinBlue;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private EntityManagerInterface $entityManager
    ) {
    }

    /*
     * Display & process form to request a password reset.
     */
    #[Route('', name: 'app_forgot_password_request')]
    public function request(Request $request, UserRepository $userRepository, TokenRepository $tokenRepository): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get("email")->getData();
            // dd($email);
            $user = $userRepository->findOneBy(array("email" => $email));
            if ($user) {
                $rowToken = $tokenRepository->findOneBy(array( "user_id" => $user, "type" => 2));
                if($rowToken) {
                    $numb1 = rand(0,9);
                    $numb2 = rand(0,9);
                    $numb3 = rand(0,9);
                    $numb4 = rand(0,9);
                    $rowToken->setToken($numb1 . $numb2 . $numb3 . $numb4);
                    $tokenRepository->save($rowToken, true);

                    $url = "http://localhost:8000/reset-password/" . $user->getId() . "/" . $rowToken->getToken();

                    $id = 5;
                    $to = array('email' => $email);
                    $params = array("url" => $url);


                    ServiceSendinBlue::sendEmail($to, $id, $params);

                    return $this->render('reset_password/request.html.twig', [
                        'requestForm' => $form->createView(),
                        'res' => 1
                    ]);
                } else {

                    $numb1 = rand(0,9);
                    $numb2 = rand(0,9);
                    $numb3 = rand(0,9);
                    $numb4 = rand(0,9);
                    $token = new Token;
                    $token->setUserId($user);
                    $token->setType(2);
                    $token->setToken($numb1 . $numb2 . $numb3 . $numb4);
                    $tokenRepository->save($token, true);

                    $url = "http://localhost:8000/reset-password/" . $user->getId() . "/" . $token->getToken();
                    
                    $id = 5;
                    $to = array('email' => $email);
                    $params = array("url" => "test");

                    ServiceSendinBlue::sendEmail($to, $id, $params);
                }
               

            } else {
                return $this->render('reset_password/request.html.twig', [
                    'requestForm' => $form->createView(),
                    'res' => 1
                ]);
            }
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
            'res' => 0
        ]);
    }

    #[Route('/{id}/token/{token}', name: 'app_reset_password_request')]
    public function reset(int $id, int $token, Request $request, TokenRepository $tokenRepository, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $form = $this->createForm(ChangePasswordFormType::class);

        $form->handleRequest($request);

        $requestToken = $tokenRepository->findOneBy(array( "user_id" => $id, "token" => $token, "type" => 2));

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy( array("id" => $id));
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $userRepository->save($user, true);
            $tokenRepository->remove($requestToken, true);

            return $this->redirectToRoute('app_login');
        }


        if ($requestToken) {
            return $this->render('reset_password/reset.html.twig', [
                'form' => $form,
            ]);
        } else {
            return $this->render('reset_password/404.html.twig');
        }
    }
}

//     /**
//      * Confirmation page after a user has requested a password reset.
//      */
//     #[Route('/check-email', name: 'app_check_email')]
//     public function checkEmail(): Response
//     {
//         // Generate a fake token if the user does not exist or someone hit this page directly.
//         // This prevents exposing whether or not a user was found with the given email address or not
//         if (null === ($resetToken = $this->getTokenObjectFromSession())) {
//             $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
            
//         }

//         return $this->render('reset_password/check_email.html.twig', [
//             'resetToken' => $resetToken,
//         ]);
//     }

//     /**
//      * Validates and process the reset URL that the user clicked in their email.
//      */
//     #[Route('/reset/{token}', name: 'app_reset_password')]
//     public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator, string $token = null): Response
//     {
//         if ($token) {
//             // We store the token in session and remove it from the URL, to avoid the URL being
//             // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
//             $this->storeTokenInSession($token);

//             return $this->redirectToRoute('app_reset_password');
//         }

//         $token = $this->getTokenFromSession();
//         if (null === $token) {
//             throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
//         }

//         try {
//             $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
//         } catch (ResetPasswordExceptionInterface $e) {
//             $this->addFlash('reset_password_error', sprintf(
//                 '%s - %s',
//                 $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, [], 'ResetPasswordBundle'),
//                 $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
//             ));

//             return $this->redirectToRoute('app_forgot_password_request');
//         }

//         // The token is valid; allow the user to change their password.
//         $form = $this->createForm(ChangePasswordFormType::class);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             // A password reset token should be used only once, remove it.
//             $this->resetPasswordHelper->removeResetRequest($token);

//             // Encode(hash) the plain password, and set it.
//             $encodedPassword = $passwordHasher->hashPassword(
//                 $user,
//                 $form->get('plainPassword')->getData()
//             );

//             $user->setPassword($encodedPassword);
//             $this->entityManager->flush();

//             // The session is cleaned up after the password has been changed.
//             $this->cleanSessionAfterReset();

//             return $this->redirectToRoute('app_login');
//         }

//         return $this->render('reset_password/reset.html.twig', [
//             'resetForm' => $form->createView(),
//         ]);
//     }

//     private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer, TranslatorInterface $translator): RedirectResponse
//     {
//         $user = $this->entityManager->getRepository(User::class)->findOneBy([
//             'email' => $emailFormData,
//         ]);

//         // Do not reveal whether a user account was found or not.
//         if (!$user) {
//             return $this->redirectToRoute('app_check_email');
//         }

//         try {
//             $resetToken = $this->resetPasswordHelper->generateResetToken($user);
//         } catch (ResetPasswordExceptionInterface $e) {
//             // If you want to tell the user why a reset email was not sent, uncomment
//             // the lines below and change the redirect to 'app_forgot_password_request'.
//             // Caution: This may reveal if a user is registered or not.
//             //
//             // $this->addFlash('reset_password_error', sprintf(
//             //     '%s - %s',
//             //     $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
//             //     $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
//             // ));

//             return $this->redirectToRoute('app_check_email');
//         }

//         $email = (new TemplatedEmail())
//             ->from(new Address('lp-lol@email.fr', 'LP-LOL Mail Bot'))
//             ->to($user->getEmail())
//             ->subject('Your password reset request')
//             ->htmlTemplate('reset_password/email.html.twig')
//             ->context([
//                 'resetToken' => $resetToken,
//             ])
//         ;

//         $mailer->send($email);

//         // Store the token object in session for retrieval in check-email route.
//         $this->setTokenObjectInSession($resetToken);

//         return $this->redirectToRoute('app_check_email');
//     }
// }
