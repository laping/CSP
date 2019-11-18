<?php

namespace App\Controller\Admin;

use App\Form\CompteType;
use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use App\Repository\RoleRepository;
use App\Entity\AdminPasswordEdition;
use App\Form\AdminPasswordEditionType;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminCompteController extends AbstractController
{
    /**
     * @Route("/compte/liste", name="compte_liste")
     * @IsGranted("ROLE_ADMIN")
     */
    public function compte_liste(UtilisateurRepository $repo, RoleRepository $repo_role)
    {
        $utilisateurs = $repo->findAll();
        
        return $this->render('compte/liste.html.twig',[
            'utilisateurs'=> $utilisateurs
            
        ]);
    }

    /**
     * @Route("/compte/inscription", name="compte_inscription")
     * @IsGranted("ROLE_ADMIN")
     */
    public function inscription(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $utilisateur = new Utilisateur();

        $form = $this->createForm(InscriptionType::class, $utilisateur);  /* Création du formulaire d'inscription */

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /* Validation du formulaire après soumission de l'utilisateur */
            
            $hash = $encoder->encodePassword($utilisateur,$utilisateur->getHash()); /* Hashage du mot de passe avec Bcrypt */
            $utilisateur->setHash($hash);
            $manager->persist($utilisateur);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'utilisateur a bien été enregistré, vous pouvez vous connecter !"
            );
           
            return $this->redirectToRoute('compte_connexion');
        }

        return $this->render('compte/inscription.html.twig', [  /* Renvoie vers une vue en y ajoutant le formulaire créé */
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/compte/{id}/supprimer", name="compte_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Utilisateur $utilisateur, ObjectManager $manager){

        $manager->remove($utilisateur);
        $manager->flush();

        $this->addFlash('success', "L'utilisateur' a bien été supprimé !");

        return $this->redirectToRoute('compte_liste');
    }

    /**
     * @Route("/compte/{id}/edition", options={"expose"=true}, name="admin_compte_edition")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editionCompte(Utilisateur $utilisateur,Request $request, ObjectManager $manager)
    {

        $form = $this->createForm(CompteType::class, $utilisateur);/* Création du formulaire d'édition */

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /*Validation du formulaire après soumission */
            
            $manager->persist($utilisateur);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'utilisateur a bien été modifié !"
            );
           
        }
        return $this->render('compte/edition.html.twig', [
            'form' => $form->createView() /* Renvoie vers une vue en y ajoutant le formulaire créé */
        ]);

    }

     /**
     * @Route("/compte/{id}/password", name="admin_compte_password")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editionPassword(Utilisateur $utilisateur,Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $passwordEdition = new AdminPasswordEdition();

        $form = $this->createForm(AdminPasswordEditionType::class, $passwordEdition); /* Création du formulaire d'édition de mot de passe */
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /* Validation du formulaire renvoyé par l'utilisateur */

            
            $nouveauPassword = $passwordEdition->getNouveauPassword();
            $hash = $encoder->encodePassword($utilisateur, $nouveauPassword); /* Hashage du nouveau mot de passe */

            $utilisateur->setHash($hash); /* Prépare le remplacement de l'ancien hash par le nouveau dans la base de données */

            $manager->persist($utilisateur);
            $manager->flush(); /* Valide et effectue les modifications dans la base */

            $this->addFlash(
                'success',
                "Le mot de passe a bien été modifié !"
            );
            return $this->redirectToRoute('home');
             
        }
        return $this->render('compte/password.html.twig',[ /* Renvoie vers une vue avec le formulaire créé en paramètre */
            'form' => $form->createView()
        ]);
    }
}
