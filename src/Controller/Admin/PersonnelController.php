<?php

namespace App\Controller\Admin;

use App\Entity\Personnel;
use App\Form\PersonnelType;
use App\Repository\PersonnelRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PersonnelController extends AbstractController
{
    /**
     * @Route("/personnel/liste", name="personnel_liste")
     * @IsGranted("ROLE_ADMIN")
     */
    public function compte_liste(PersonnelRepository $repo)
    {
        $personnels = $repo->findAll();
        
        return $this->render('personnel/liste.html.twig',[
            'personnels'=> $personnels
            
        ]);
    }

    /**
     * @Route("/personnel/inscription", name="personnel_inscription")
     * @IsGranted("ROLE_ADMIN")
     */
    public function inscription(Request $request, EntityManagerInterface $manager)
    {
        $personnel = new Personnel();

        $form = $this->createForm(PersonnelType::class, $personnel);  /* Création du formulaire d'inscription */

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /* Validation du formulaire après soumission de l'utilisateur */
            
            $manager->persist($personnel);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le personnel a bien été enregistré !"
            );
           
            return $this->redirectToRoute('personnel_liste');
        }

        return $this->render('compte/inscription.html.twig', [  /* Renvoie vers une vue en y ajoutant le formulaire créé */
            'form' => $form->createView()
        ]);
    }

      /**
     * @Route("/personnel/{id}/supprimer", name="personnel_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Personnel $personnel, EntityManagerInterface $manager){

        $manager->remove($personnel);
        $manager->flush();

        $this->addFlash('success', "Le personnel a bien été supprimé !");

        return $this->redirectToRoute('personnel_liste');
    }

    /**
     * @Route("/personnel/{id}/edition", name="personnel_edition")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editionCompte(Personnel $personnel,Request $request, EntityManagerInterface $manager)
    {

        $form = $this->createForm(PersonnelType::class, $personnel);/* Création du formulaire d'édition */

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /*Validation du formulaire après soumission */
            
            $manager->persist($personnel);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le personnel a bien été modifié !"
            );

            return $this->redirectToRoute('personnel_liste');
           
        }
        return $this->render('personnel/edition.html.twig', [
            'form' => $form->createView() /* Renvoie vers une vue en y ajoutant le formulaire créé */
        ]);

    }

   
}
