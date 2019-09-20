<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{adresse}", name="user_show")
     * @return Response
     */
    public function index($adresse, UserRepository $repo)
    {
        $user = $repo->findOneByAdresse($adresse);

        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
