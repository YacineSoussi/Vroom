<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le forumulaire de connexion
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        
        return $this->render('account/login.html.twig', [
            'Error' => $error !== null,
            'username' => $username
        ]);
    } 

    /**
     *  Permet de se déconnecter
     *  @return void
     * @Route("/logout", name="account_logout")
     */
    public function logout(){
            // rien ! 
    }

    /**
     * Permet d'afficher le formulaire d'inscription
     *
     * @Route("/register", name="account_register"
     * )
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success', 
                "Votre compte a bien été crée ! Vous pouvez maintenant vous connecter"
            );

            return $this->redirectToRoute("account_login");
        }

        return $this->render('account/inscription.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher et de traiter le formulaire de modification de profil
     * 
     * @Route("/compte/profil", name="account_profil")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function profil(Request $request, ObjectManager $manager){
            $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les donnés ont bien été modifié"
            );
        }

        return $this->render('account/profil.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet de modifier le mot de passe
     *
     * @Route("/compte/update-password", name="account_password")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function updatePassword(){

        $passwordUpdate = new PasswordUpdateType();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher son profil
     * 
     * @Route("/compte", name="account_index")
     * @IsGranted("ROLE_USER")
     * 
     *
     * @return Response
     */
    public function monCompte(){

        return $this->render('user/index.html.twig',[
            'user' => $this->getUser()
        ]);
    }

    /**
     * Permet d'afficher la liste des réservations fait par l'user
     * 
     * @Route("/compte/reservations", name="compte_reservations")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function reservation(){

        return $this->render('account/reservations.html.twig');

    }
    
}
