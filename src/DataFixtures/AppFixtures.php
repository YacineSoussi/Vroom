<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Annonce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    public function __construct(UserPasswordEncoderInterface $encoder )
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminuser = new User();
        $adminuser->setFirstName('Yacine')
                  ->setLastName('Soussi')
                  ->setEmail('Yacine17@gmail.com')
                  ->setPassword($this->encoder->encodePassword($adminuser, 'password'))
                  ->setAvatar('https://via.placeholder.com/150')
                  ->setIntroduction($faker->sentence())
                 ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                 ->addUserRole($adminRole);

                 $manager->persist($adminuser);

        // Nous gérons les users

            $users = [];
            $genres = ['male', 'female'];


            for($i = 1; $i <= 10; $i++) {

                $user = new User();

                $genre = $faker->randomElement($genres);

                $picture = 'https://randomuser.me/api/portraits/';
                $pictureId = $faker->numberBetween(1, 99) . '.jpg';

                $picture .= $picture . ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

                $hash = $this->encoder->encodePassword($user, 'password');

                $user->setFirstName($faker->firstname($genres))
                     ->setLastName($faker->lastname)
                     ->setEmail($faker->email)
                     ->setIntroduction($faker->sentence())
                    ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                    ->setPassword($hash)
                    ->setAvatar($picture);

                    $manager->persist($user);
                    $users[] = $user;
            }

        // Nous gérons les annonces

        for($i = 1; $i <= 30; $i++) {
            $annonce = new Annonce();

        $title = $faker->sentence();
        
        $profilImage = $faker->imageUrl(1000,350);
        $introduction = $faker->paragraph(2);
        $contenu = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

                $user = $users[mt_rand(0, count($users) - 1)];

       $annonce->setTitle($title)
            ->setProfilImage($profilImage)
            ->setIntroduction($introduction)
            ->setContenu($contenu)
            ->setPrix(mt_rand(40, 200))
            ->setChambres(mt_rand(1, 5))
            ->setAuteur($user);

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
