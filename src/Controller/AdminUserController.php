<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_user_index")
     */
    public function index(UserRepository $repo)
    {

        return $this->render('admin/user/index.html.twig', [
            'user' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/users/{id}/edit", name="admin_user_edit")
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user, Request $request, ObjectManager $manager) {

        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'user <strong>{$user->getfullName()}</strong> a bien été enregistrée !"
            );
        }
        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

        /**
     * Permet de supprimer une user
     *
     * @Route("/admin/users/{id}/delete", name="admin_user_delete")
     * 
     * @param User $user
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(User $user, ObjectManager $manager) {
        if(count($user->getReservations()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce <strong>{$user->getFullName()}</strong> car elle possède déjà des réservations !"
            );
        } else {
        $manager->remove($user);
        $manager->flush();
        $this->addFlash(
            'success',
            "La réservation a bien été supprimée"
        );
    }
        return $this->redirectToRoute("admin_user_index");
    }

}