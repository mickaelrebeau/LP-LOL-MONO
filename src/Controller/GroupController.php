<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Group;
use App\Entity\User;
use App\Entity\GroupUsers;
use App\Repository\GroupRepository;
use App\Repository\GroupUsersRepository;
use App\Repository\RequestRepository;
use App\Form\GroupType;
use Doctrine\Persistence\ObjectManager;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;



class GroupController extends AbstractController
{
    #[Route('/group', name: 'app_group', methods:['GET'])]
    public function index(GroupRepository $groupRepository): Response
    {
        $group = $groupRepository->findAll();
     

        return $this->render('group/index.html.twig', [
            'group' => $group,
        ]);
        
    }

    #[Route('/group_create_render/{id}', name: 'group_create_render')]
    public function create_render(): Response
    {
        return $this->render('group/create.html.twig');
    }

    #[Route('/group_create', name: 'group_create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        GroupRepository $groupRepository,
        UserRepository $userRepository, 
        GroupUsersRepository $groupUsersRepository,
        RequestRepository $requestRepository
      ): Response
    {
            // $user = $this->getUser();
            $user = $userRepository -> find(1); //A enlever une fois qu'on pourra connecter les utilisateurs
            $requestAcces = $requestRepository -> findBy(array('user_target' => $user,
            'status' => 1 ));

            $contacts=[];
           foreach ($requestAcces as $contact) {
               
                $friend = $userRepository -> find($contact-> getUserRequester());
                array_push($contacts, $friend);

           }

                   

            $group = new Group();
            $form = $this->createForm(GroupType::class, $group);
            
           
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
              
                $dataS = $request -> request -> all('data_sharing');
                $dataSharing = implode( ',', $dataS);
                $group = $form->getData();
                $group -> setDataSharing($dataSharing);
                $group -> setUserId($user);
            
                // $group -> getGroupUsers();
                $dataContacts = $request -> request -> all('contacts');
                foreach ($dataContacts as $el) {
                    $groupUser = new GroupUsers();
                    $groupUser -> setUserId($userRepository -> find($el));
                    $groupUser -> setGroupId($group);
                    $groupUser -> setDataSharingAdditional($group -> getDataSharing());
                    // $groupUsersRepository->save($groupUser, true);
                    //save ? groupUser
                    $group -> addGroupUser($groupUser);
                }
                
                $groupRepository->save($group, true);

                $this->addFlash(
                    'success',
                    'Le groupe a bien été créé.'
                );

                return $this->redirectToRoute('app_group');
            }
        
            return $this->render('group/create.html.twig', [
                'form' => $form->createView(),
                'requestAcess' => $requestAcces,
                'user' => $user,
                'group' => $group,
                'contacts' => $contacts,
               
            ]);
        
    }
    

    #[Route('/group/{id}', name: 'edit_group', methods: ['GET', 'POST'])]
    public function edit (int $id, Group $group,
    GroupRepository $groupRepository, 
    UserRepository $userRepository, 
    RequestRepository $requestRepository, 
    GroupUsersRepository $groupUsersRepository,
    TranslatorInterface $translator,
    Request $request): Response
    {
         // $user = $this->getUser();
         
         $user = $this->getUser();
        $user = $userRepository -> find(1); //A enlever une fois qu'on pourra connecter les utilisateurs
        //Récupération des contacts
        $requestAcces = $requestRepository -> findBy(array('user_target' => $user,
        'status' => 1 ));
        $contacts=[];
        foreach ($requestAcces as $contact) {
            
            $friend = $userRepository -> find($contact-> getUserRequester());
            array_push($contacts, $friend);

        }
        $dataContacts = $request -> request -> all('contacts'); //Récupération des contacts validés

         //Traitement des datas sharing   
          $dataUserValide = explode(',',$group->getDataSharing());
         
          $dataUserNonValide = [];
          $dataSharingTotal = ['firstname','lastname','pseudo', 'phoneNumber', 'fixNumber', 'email', 'digicode', 'address1', 'address2', 'address3', 'cb1', 'cb2'];
          foreach ($dataSharingTotal as $d) {
            if (!(in_array($d, $dataUserValide))){
                array_push($dataUserNonValide, $d);
            }
          }  //fin traitement des datas sharing

        //   dd( $translator, $translator->trans("datas.firstname"), $translator->trans('messages.firstname'));
        
          //Traitement pour les contacts dans le group ou pas
          $groupUserActif = $groupUsersRepository->findBy(['group_id'=> $group -> getId()]); 
          $usersGroupUserActif = [];
          foreach ($groupUserActif as $userActif) { 
            $userActifPast = $userRepository -> find($userActif->getUserId());
            array_push($usersGroupUserActif, $userActifPast);
          }   
          
       
          $groupUserInactif = [];
         
          foreach ($dataContacts as $c) {
            if (!(in_array($d, $usersGroupUserActif))){
                array_push($groupUserInactif, $c);
            }
          }
      
         
        $form = $this->createForm(GroupType::class, $group)
            ->add('expiration_date', DateType::class, [
                // renders it as a single text box
                'widget' => 'single_text',
                'label' => 'Date d\'expiration du groupe :',
                'required' => false,
                'empty_data' => null,
                'data' => $group ->getExpirationDate()
            ])
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dataS = $request -> request -> all('data_sharing');
            $dataSharing = implode( ',', $dataS);
            $group = $form->getData();
            $group -> setDataSharing($dataSharing);
            $group -> setUserId($user);
         
           
            foreach ($dataContacts as $el) {    //création des groupUsers à partir des contacts validés
                $groupUser = new GroupUsers();
                $groupUser -> setUserId($userRepository -> find($el));
                $groupUser -> setGroupId($group);
                $groupUser -> setDataSharingAdditional($group -> getDataSharing());
                // $groupUsersRepository->save($groupUser, true);
                //save ? groupUser
                $group -> addGroupUser($groupUser);
            }
            $groupRepository->save($group, true);

            $this->addFlash(
                'success',
                'Le groupe a bien été modifié.'
            );

            return $this->redirectToRoute('app_group', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('group/edit.html.twig', [
            'form' => $form->createView(),
            'requestAcess' => $requestAcces,
            'user' => $user,
            'group' => $group,
            'contacts' => $contacts,
            'dataUserValide' => $dataUserValide,
            'dataUserNonValide' => $dataUserNonValide,
            'usersGroupUserActif'=> $usersGroupUserActif,
            'groupUserInactif' => $groupUserInactif,
            'translator' => $translator,
        ]);
    }

   
    // #[Route('/group/delete/{id}', name: 'group_delete',)]
    // public function remove(GroupRepository $groupRepository, int $id, Group $group): Response
    // {
    //     // if ($this->isCsrfTokenValid('delete'.$group->getId(), $request->request->get('_token'))) {
    //     //     $groupRepository->remove($group, true);
    //     // }
          
    //     $groupThis = $groupRepository->find($id);
    //     $groupRepository->remove($groupThis);
    //     $groupRepository->flush();
    //         // throw $this->createNotFoundException(
    //         //     'No group found for id '.$id
    //         // );
    
    //         $this->addFlash(
    //             'success',
    //             'Le groupe a bien été supprimé.'
    //         );
        
    //     return $this->render('group/index.html.twig', [
    //         'group' => $group
    //     ]);
    // }

    #[Route('/group/delete/{id}', name: 'group_delete')]
    public function remove(GroupRepository $groupRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $group = $groupRepository->find($id);

        if (!$group) {
            throw $this->createNotFoundException('No group found for id '.$id);
        }

        $entityManager->remove($group);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Le groupe a bien été supprimé.'
        );

        return $this->redirectToRoute('app_group');
    }
}
