<?php
namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends Controller{

  

    /**
     * @Route("/", name="homepage")
     */
    public function home(AnnonceRepository $repo, UserRepository $repository){

        $user = $repository->findAll();
        $annonce = $repo->findBy(['chambres' => '2019']);

        return $this->render('home.html.twig', [
            'annonces' => $annonce,
            'user' => $user
        ]);
           
    }
}
?>