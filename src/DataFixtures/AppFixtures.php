<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Etat;
use App\Entity\Role;
use App\Entity\Machine;
use App\Entity\Message;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Proxies\__CG__\App\Entity\Personnel;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        #--------------Création d'un admin personnalisé---------------------#
        $adminRole= new Role();
        $adminRole->setNom('ROLE_ADMIN');

        $manager->persist($adminRole);

        $administrateur = new Utilisateur();
        $administrateur->setNom('David Delette')
                        ->setHash($this->encoder->encodePassword($administrateur, 'password'))
                        ->addUtilisateurRole($adminRole);

        $manager->persist($administrateur);

        #-----------------------Génération d'utilisateurs---------------------------#
        $utilisateurs = [];
        $genres = ['male', 'female'];

        for($i=1; $i <= 2; $i++){

            $utilisateur= new Utilisateur;

            $genre = $faker->randomElement($genres);
            $hash = $this->encoder->encodePassword($utilisateur, 'password');

            $utilisateur->setNom($faker->name($genre))
                        ->setHash($hash);

            $manager->persist($utilisateur);
            $utilisateurs[]= $utilisateur;
        }
         #-----------------------Génération du personnel---------------------------#
         $personnels = [];
         $genres = ['male', 'female'];
 
         for($i=1; $i <= 20; $i++){
 
             $personnel= new Personnel();
 
             $genre = $faker->randomElement($genres);
             
 
             $personnel->setNom($faker->name($genre));
                      
 
             $manager->persist($personnel);
             $personnels[]= $personnel;
         }

        #-------------------Génération de messages avec commentaires---------------------------#
        
        for($i = 1; $i <= 30; $i++){

            $message = new Message();

            $titre=$faker->sentence();
            $contenu='<p>' . join('</p><p>',$faker->paragraphs(3)) . '</p>';
            $date_creation=$faker->dateTimeBetween('-3 months');
            $date_creation_com=$faker->dateTimeBetween('-3 months');


            $utilisateur=$utilisateurs[mt_rand(0, count($utilisateurs) -1)];
            $utilisateur_com=$utilisateurs[mt_rand(0, count($utilisateurs) -1)];
            
            $personnel=$personnels[mt_rand(0, count($personnels) -1)];
            $personnel_com=$personnels[mt_rand(0, count($personnels) -1)];

            $message-> setTitre($titre)
                    -> setAuteur($utilisateur)
                    -> setContenu($contenu)
                    -> setIsArchived($faker->boolean($chanceOfGettingTrue = 50))
                    -> setSignature($personnel)
                    -> setDateCreation($date_creation);

            for($j = 1; $j <= mt_rand(0, 10); $j++){

                $commentaire = new Commentaire();
                $contenu='<p>' . join('</p><p>',$faker->paragraphs(mt_rand(1,2))) . '</p>';

                $commentaire->setAuteur($utilisateur_com)
                            ->setContenu($contenu)
                            ->setMessage($message)
                            -> setSignature($personnel_com)
                            ->setDateCreation($date_creation_com);

                $manager->persist($commentaire);

            }
            
            $manager->persist($message);
        }

        $manager->flush();
    }
}
