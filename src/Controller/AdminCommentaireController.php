<?php
namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\AdminCommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminCommentaireController extends Controller
{
    /**
     * @Route("/admin/commentaires", name="admin_commentaire_index")
     */
    public function index(CommentaireRepository $repo)
    {
        
        return $this->render('admin/commentaire/index.html.twig', [
            'commentaire' => $repo->findAll()
        ]);
    }
    /**
     * Permet de modifier un commentaire
     *
     * @Route("/admin/commentaires/{id}/edit", name="admin_commentaire_edit")
     * 
     * @return Response
     */
    public function edit(Commentaire $commentaire, Request $request, ObjectManager $manager) {

        $form = $this->createForm(AdminCommentaireType::class, $commentaire);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($commentaire);
            $manager->flush();
            $this->addFlash(
                'success',
                "Le commentaire numéro {$commentaire->getId()} a bien été modifié !"
            );
        }
        return $this->render('admin/commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/admin/commentaires/{id}/delete", name="admin_commentaire_delete")
     *
     * @param Commentaire $commentaire
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Commentaire $commentaire, ObjectManager $manager) {
        $manager->remove($commentaire);
        $manager->flush();
        $this->addFlash(
            'success',
            "Le commentaire de {$commentaire->getAuteur()->getFullName()} a bien été supprimé !"
        );
        return $this->redirectToRoute('admin_commentaire_index');
    }
}