<?php

namespace App\Controller;


use App\Entity\Annonce;
use App\Entity\Commentaire;
use App\Entity\Reservation;
use App\Form\CommentaireType;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    /**
     * @Route("/annonces/{adresse}/reservation", name="reservation_create")
     * @IsGranted("ROLE_USER")
     */
    public function Reservation(Annonce $annonce, Request $request, EntityManagerInterface $manager)
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
                $user = $this->getUser();

                $reservation->setBooker($user)
                            ->setAnnonce($annonce);
                            
                $manager->persist($reservation);
                $manager->flush();

                return $this->redirectToRoute('reservation_show',['id' => $reservation->getId(), 
                'Alert' => true]);
        }


        return $this->render('reservation/reservation.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()
        ]);
    }

    /**
     * Undocumented function
     * 
     * @Route("/reservation/{id}", name="reservation_show")
     *
     * @return Response
     */
    public function show(Reservation $reservation, EntityManagerInterface $manager, Request $request){

        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $commentaire->setAnnonce($reservation->getAnnonce())
                    ->setAuteur($this->getUser());
            $manager->persist($commentaire);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre commentaire a bien été pris en compte !"
            );
        }
            return $this->render('reservation/show.html.twig',[
                'reservation' => $reservation,
                'form'  => $form->createView()
                
            ]);
    }
}
