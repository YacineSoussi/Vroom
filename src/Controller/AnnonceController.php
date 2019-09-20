<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Annonce;
use App\Form\AnnonceType;
use Doctrine\Common\Annotations;
use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces_index")
     */
    public function index(AnnonceRepository $repo, SessionInterface $session)
    {
        // $repo = $this->getDoctrine()->getRepository(Annonce::class);

        $annonces = $repo->findAll();

        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces
        ]);
    }
    /**
     * Permet de créer une annonce
     * @Route("/annonces/new", name="annonces_create")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $annonce  = new Annonce();
        

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        
        

        if ($form->isSubmitted() && $form->isValid()) {
                foreach($annonce->getImages() as $image){
                    $image->setAnnonce($annonce);
                    $manager->persist($image);
                }
                    $annonce->setAuteur(($this->getUser()));

            $manager->persist($annonce);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$annonce->getTitle()}</strong> a bien été enregistré"
            );


            return $this->redirectToRoute('annonces_show', [
                'adresse' => $annonce->getAdresse()
            ]);
        }

        return $this->render('annonce/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

  /**
     * Permet d'afficher le formulaire d'edition
     *
     * @Route("/annonces/{adresse}/edit", name="annonces_edit")
     * @Security("is_granted('ROLE_USER') and user === annonce.getAuteur()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     * @return Response
     */
    public function edit(Request $request, Annonce $annonce)
    {
        
        
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        

        return $this->render('annonce/edit.html.twig', [
            'form' => $form->createView(),
            'annonce' => $annonce
        ]);
}


    /**
     * Permet d'afficher une annonce
     * 
     * @Route("/annonces/{adresse}", name="annonces_show")
     * 
     *  
     */
    public function show($adresse, AnnonceRepository $repo)
    {
        // Je recupère l'annonce qui correspond a l'adresse 
        $annonce = $repo->findOneByAdresse($adresse);

        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * 
     * @Route("/annonces/{adresse}/delete", name="annonces_delete")
     * @Security("is_granted('ROLE_USER') and user == annonce.getAuteur()", message="Vous n'avez pas le droit d'accéder à cette ressource")
     *
     * @param Annonce $annonce
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Annonce $annonce, ObjectManager $manager) {
        $manager->remove($annonce);
        $manager->flush();
        $this->addFlash(
            'success',
            "L'annonce <strong>{$annonce->getTitle()}</strong> a bien été supprimée !"
        );
        return $this->redirectToRoute("annonces_index");
    }

  
}
