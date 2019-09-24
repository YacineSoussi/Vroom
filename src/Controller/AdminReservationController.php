<?php
namespace App\Controller;

use App\Entity\Reservation;
use App\Form\AdminReservationType;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminReservationController extends Controller
{
    /**
     * @Route("/admin/reservations", name="admin_reservation_index")
     */
    public function index(ReservationRepository $repo)
    {        
        
        return $this->render('admin/reservation/index.html.twig', [
            'reservation' => $repo->findAll()
        ]); 
    }
    /**
     * Permet d'éditer une réservation
     * 
     * @Route("/admin/reservations/{id}/edit", name="admin_reservation_edit")
     * 
     * @return Response
     */
    public function edit(Reservation $reservation, Request $request, ObjectManager $manager) {    

        $form = $this->createForm(AdminReservationType::class, $reservation);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $reservation->setMontant(0);
            $manager->persist($reservation);
            $manager->flush();
            $this->addFlash(
                'success', 
                "La réservation n°{$reservation->getId()} a bien été modifiée" 
            );
            return $this->redirectToRoute("admin_reservation_index");
        }
        return $this->render('admin/reservation/edit.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation
        ]);
    }
    /**
     * Permet de supprimer une réservation
     * 
     * @Route("/admin/reservations/{id}/delete", name="admin_reservation_delete")
     *
     * @return Response
     */
    public function delete(Reservation $reservation, ObjectManager $manager) {

        $manager->remove($reservation);
        $manager->flush();
        $this->addFlash(
            'success',
            "La réservation a bien été supprimée"
        );
        return $this->redirectToRoute("admin_reservation_index");
    }
}