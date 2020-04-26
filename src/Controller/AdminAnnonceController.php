<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAnnonceController extends AbstractController
{
    /**
     * @Route("/admin/annonces", name="admin_annonce_index")
     */
    public function index(AnnonceRepository $repo)
    {

        return $this->render('admin/annonce/index.html.twig', [
            'annonce' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/annonces/{id}/edit", name="admin_annonces_edit")
     *
     * @param Annonce $annonce
     * @return Response
     */
    public function edit(Annonce $annonce, Request $request, EntityManagerInterface $manager) {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($annonce);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce <strong>{$annonce->getTitle()}</strong> a bien été enregistrée !"
            );
        }
        return $this->render('admin/annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()
        ]);
    }

        /**
     * Permet de supprimer une annonce
     *
     * @Route("/admin/annonces/{id}/delete", name="admin_annonces_delete")
     * 
     * @param Annonce $annonce
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Annonce $annonce, EntityManagerInterface $manager) {
        if(count($annonce->getReservations()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce <strong>{$annonce->getTitle()}</strong> car elle possède déjà des réservations !"
            );
        } else {
            $manager->remove($annonce);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "L'annonce <strong>{$annonce->getTitle()}</strong> a bien été supprimée !"
            );
        }
        return $this->redirectToRoute('admin_annonce_index');
    }

}
