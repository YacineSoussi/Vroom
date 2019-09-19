<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\Image;
use App\Entity\Annonce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 1; $i <= 30; $i++) {
            $annonce = new Annonce();

        $title = $faker->sentence();
        
        $profilImage = $faker->imageUrl(1000,350);
        $introduction = $faker->paragraph(2);
        $contenu = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

       $annonce->setTitle($title)
            ->setProfilImage($profilImage)
            ->setIntroduction($introduction)
            ->setContenu($contenu)
            ->setPrix(mt_rand(40, 200))
            ->setChambres(mt_rand(1, 5));
        $manager->persist($annonce);    
        
        for($j = 1; $j <= mt_rand(2, 5); $j++) {
            $image = new Image();
            $image->setUrl($faker->imageUrl())
                  ->setLegend($faker->sentence())
                  ->setAnnonce($annonce);
            $manager->persist($image);
        }
        }
       $manager->flush();
    }
}
