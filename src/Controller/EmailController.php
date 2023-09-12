<?php

namespace App\Controller;

use App\Form\EmailType;
use App\Services\ServiceSendinBlue;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EmailController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function index(Request $request): Response
    {   
       $form = $this->createForm(EmailType::class);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()) {

//         $data = $form->getData();
        
//         $address = $data['email'];
//         $content = $data['content'];
        $id = 4;
        $to = array('email' => "johnb16@hotmail.fr", 'name' => 'jojo');
        $params = array("name" => "jojo");

        ServiceSendinBlue::sendEmail($to, $id, $params);
       }



        return $this->render('email/index.html.twig', [
            'form' => $form
        ]);
    }

    
}
