<?php

namespace App\Controller;

use App\Entity\RequestLog;
use App\Repository\RequestLogRepository;
use App\Repository\RequestRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RequestAccessController extends AbstractController
{
    #[Route('/request/access/{id}', name: 'app_request_access')]
    public function index(int $id, RequestRepository $requestRepository, UserRepository $userRepository, RequestLogRepository $requestLogRepository): Response
    {
        $req = $requestRepository->findBy(array('user_target' => $id));
        $profil = $userRepository->findOneBy(array('id' => $id));
        $me = $profil->getId();
        $users = array();
        foreach ($req as $el) {
            $datas = $userRepository->findOneBy(array('id' => $el->getUserRequester()));
            $refused = $requestLogRepository->findOneBy(array('request_id' => $el->getId()));
            $user = (object)[
                'id' => $datas->getId(),
                'isRefused' => $refused ? true : false,
                'status' => $el->isStatus(),
                'firstname' => $datas->getFirstname(),
                'lastname' => $datas->getLastname()
            ];
            array_push($users, $user);
        }
        // dd($users);
        return $this->render('request_access/index.html.twig', [
            'users' => $users,
            'me' => $me
        ]);
    }

    #[Route('/request/access/{id}/{idUser}/add', name: 'app_request_access_accept')]
    public function add(int $id, int $idUser, RequestRepository $requestRepository) : Response
    {
        $user = $requestRepository->findOneBy(array('user_requester' => $idUser, 'user_target' => $id));
        $user->setStatus(true);
        $requestRepository->save($user, true);

        return $this->redirectToRoute('app_request_access', array('id' => $id));
    }

    #[Route('/request/access/{id}/{idUser}/deny', name: 'app_request_access_deny')]
    public function deny(int $id, int $idUser, RequestRepository $requestRepository, RequestLogRepository $requestLogRepository) : Response
    {
        $requestLog = new RequestLog();
        $requestLog->setStatus(true);
        $requestLog->setRequestId($requestRepository->findOneBy(array('user_requester' => $idUser, 'user_target' => $id)));
        $requestLogRepository->save($requestLog, true);

        return $this->redirectToRoute('app_request_access', array('id' => $id));
    }

    #[Route('/request/access/{id}/{idUser}/retired', name: 'app_request_access_retired')]
    public function retired(int $id, int $idUser, RequestRepository $requestRepository) : Response
    {
        $user = $requestRepository->findOneBy(array('user_requester' => $idUser, 'user_target' => $id));
        $user->setStatus(false);
        $requestRepository->save($user, true);

        return $this->redirectToRoute('app_request_access', array('id' => $id));
    }

    #[Route('/request/access/{id}/{idUser}/revoked', name: 'app_request_access_revoked')]
    public function revoked(int $id, int $idUser, RequestRepository $requestRepository, RequestLogRepository $requestLogRepository) : Response
    {
        $user = $requestRepository->findOneBy(array('user_requester' => $idUser, 'user_target' => $id));
        $requestLog = $requestLogRepository->findOneBy(array( 'request_id' => $user->getId()));
        $requestLogRepository->remove($requestLog, true);

        return $this->redirectToRoute('app_request_access', array('id' => $id));
    }
}
