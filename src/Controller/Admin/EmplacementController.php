<?php

namespace App\Controller\Admin;

use App\Entity\Emplacement;
use App\Form\EmplacementType;
use App\Repository\EmplacementRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmplacementController extends AbstractController
{
     /**
     * @Route("/emplacement", name="emplacement")
     * @IsGranted("ROLE_ADMIN")
     */
    public function liste(EmplacementRepository $repo)
    {
        $emplacements= $repo->findAll();
        return $this->render('emplacement/liste.html.twig', [
            'emplacements' => $emplacements
        ]);
    }

    /**
     * @Route("/emplacement/creer", name="emplacement_creer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function creer(Request $request, ObjectManager $manager)
    {

        $emplacement = new Emplacement();

        $form = $this-> createForm(EmplacementType::class, $emplacement); /*Création du formulaire */

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /*Validation du formulaire soumis par l'utilisateur */
            
            $manager->persist($emplacement); /* Inscription du nouvel emplacement dans la base de données */
            $manager->flush();

            $this->addFlash(
                'success',
                "L'emplacement a bien été enregistré !"
            );
           
            return $this->redirectToRoute('emplacement');
        }

        return $this->render('emplacement/creer.html.twig',[
            'form'=> $form->CreateView()
        ]);
    }

    /**
     * Editer un emplacement
     * @Route("/emplacement/{id}/editer", name="emplacement_editer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer(Emplacement $emplacement,Request $request, ObjectManager $manager){

        $form = $this-> createForm(EmplacementType::class, $emplacement);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($emplacement);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'emplacement' a bien été modifié !"
            );
           
            return $this->redirectToRoute('emplacement');
        }

        return $this->render('emplacement/editer.html.twig',[
            'form'=> $form->CreateView(),
            'emplacement' => $emplacement
        ]);
    }

    /**
     * @Route("/emplacement/{id}/supprimer", name="emplacement_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Emplacement $emplacement, ObjectManager $manager){

        $manager->remove($emplacement); /* Prépare la suppression de l'emplacement */
        $manager->flush(); /* Exécute les changements */

        $this->addFlash('success', "L'emplacement a bien été supprimé !");

        return $this->redirectToRoute('emplacement');
    }
}
