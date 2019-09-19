<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Form\ImagesType;
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

class AnnonceType extends AbstractType
{
    /**
     * permet d'avoir la config de base d'un champ 
     *
     * @param [string] $label
     * @param [string] $placeholder
     * @param array $options
     * @return array
     */
    private function getConfiguation($label, $placeholder, $options = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]

        ], $options);
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguation("titre", "Tapez le titre de votre Annonce"))
            ->add('adresse', TextType::class, $this->getConfiguation("Adresse web", "Tapez l'adresse web (automatique)",[ 'required' => false]))          
            ->add('profilImage', UrlType::class, $this->getConfiguation("URL de l'image principale", "Donnez l'adresse d'une image qui donne vraiment envie"))
            ->add('introduction', TextType::class, $this->getConfiguation("Introdction", "Donnez une description global"))
            ->add('contenu', TextareaType::class, $this->getConfiguation("Description détaillée", "Tapez une description qui donne vraiment envie de venir chez vous"))
            ->add('chambres', IntegerType::class, $this->getConfiguation("Nombre de chambre", "Le nombre de chambres disponnible"))
            ->add('prix', MoneyType::class, $this->getConfiguation("Prix par nuit", "Indiquez le prix que vous voulez pour une nuit"))
            ->add('images', CollectionType::class,
        [
            'entry_type' => ImagesType::class,
            'allow_add' => true,
            'allow_delete' => true
        ]);
        }
     

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
