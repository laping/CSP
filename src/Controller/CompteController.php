<?php

namespace App\Controller;

use App\Form\CompteType;
use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use App\Entity\PasswordEdition;
use App\Form\PasswordEditionType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\UtilisateurRepository;
use App\Repository\RoleRepository;

class CompteController extends AbstractController
{
    /**
     * @Route("/compte/connexion", name="compte_connexion")
     * 
     * @return Response
     */
    public function connexion(AuthenticationUtils $utils)
    {
        $erreur = $utils->getLastAuthenticationError();
        $nom = $utils->getLastUsername();

        return $this->render('compte/connexion.html.twig', [
            'hasErreur' => $erreur !== null,
            'nom_utilisateur' => $nom
        ]);
    }

    /**
     * @Route("/compte/deconnexion", name="compte_deconnexion")
     */
    public function deconnexion()
    {

    }

    

    /**
     * @Route("/compte/edition", name="compte_edition")
     * @IsGranted("ROLE_USER")
     */
    public function editionCompte(Request $request, ObjectManager $manager)
    {

        $utilisateur = $this->getUser();

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
     * @Route("/compte/password", name="compte_password")
     * @IsGranted("ROLE_USER")
     */
    public function editionPassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $passwordEdition = new PasswordEdition();
        $utilisateur= $this->getUser(); /* Récupération de l'utilisateur connecté */

        $form = $this->createForm(PasswordEditionType::class, $passwordEdition); /* Création du formulaire d'édition de mot de passe */
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /* Validation du formulaire renvoyé par l'utilisateur */

            if(!password_verify($passwordEdition -> getAncienPassword(), $utilisateur->getHash())){ /* Vérifie que l'ancien mot de passe saisi est le bon */

                $form->get('ancienPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            }else{
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
        }
        return $this->render('compte/password.html.twig',[ /* Renvoie vers une vue avec le formulaire créé en paramètre */
            'form' => $form->createView()
        ]);
    }

    
}
