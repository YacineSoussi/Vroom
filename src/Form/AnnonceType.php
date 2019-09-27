<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Form\ImagesType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends ApplicationType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("titre", "Tapez le titre de votre Annonce"))
                  
            ->add('profilImage', UrlType::class, $this->getConfiguration("URL de l'image principale", "Donnez l'adresse d'une image qui donne vraiment envie"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introdction", "Donnez une description global"))
            ->add('contenu', TextareaType::class, $this->getConfiguration("Description détaillée", "Tapez une description qui donne vraiment envie de venir chez vous"))
            ->add('chambres', IntegerType::class, $this->getConfiguration("Année de la voiture", "Le nombre de chambres disponnible"))
            ->add('prix', MoneyType::class, $this->getConfiguration("Prix par jour", "Indiquez le prix que vous voulez pour une nuit"))
            ->add('images', CollectionType::class,
        [
            'entry_type' => ImagesType::class,
            'allow_add' => true
        ]);
        }
     

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
