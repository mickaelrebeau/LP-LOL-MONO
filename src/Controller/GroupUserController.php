<?php

namespace App\Controller;

use App\Form\GroupUserType;
use App\Repository\GroupRepository;
use App\Repository\GroupUsersRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupUserController extends AbstractController
{
    #[Route('/group/user', name: 'app_group_user')]
    public function index(): Response
    {
        return $this->render('group_user/index.html.twig', [
            'controller_name' => 'GroupUserController',
        ]);
    }

    #[Route('/group/{id}/user/{idUser}/del', name: 'app_group_del_user')]
    public function delUser(int $id, int $idUser, GroupUsersRepository $groupUsersRepository): Response
    {
        $user = $groupUsersRepository->findOneBy(array( 'user_id' => $idUser, 'group_id' => $id));
        $groupUsersRepository->remove($user, true);
        return $this->redirectToRoute('group_user/index.html.twig', [
            'controller_name' => 'GroupUserController',
        ]);
    }

    #[Route('/group/{id}/user/{idUser}/adddata', name: 'add_data_group_user')]
    public function addDataUser(int $id, int $idUser, Request $request,UserRepository $userRepository, GroupUsersRepository $groupUsersRepository, GroupRepository $groupRepository): Response
    {
        $groups = $groupRepository->findBy(array('user_id' => $id));
        $guy = $userRepository->findOneBy(array('id' => $idUser));
        $groupsUser = array();
        foreach ($groups as $group) {
            $user = $groupUsersRepository->findOneBy(array('user_id' => $idUser, 'group_id' => $group->getId()));
            if($user) {
                array_push($groupsUser, $user->getGroupId());
            }
        }
        // dd($groupsUser);

        $form = $this->createForm(GroupUserType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $idGroup = $request -> request -> get('group');
            $dataS = $request -> request -> all('data_sharing');
            $res = implode(",", $dataS);
            $sendDatas = $groupUsersRepository->findOneBy(array('user_id' => $idUser, 'group_id' => $idGroup));
            $sendDatas->setDataSharingAdditional($res);
            $groupUsersRepository->save($sendDatas, true);
            dd($sendDatas);
        }
        return $this->render('group_user/adddata.html.twig', [
            'groupsUser' => $groupsUser,
            'form' => $form,
            'guy' => $guy
        ]);
}
}
