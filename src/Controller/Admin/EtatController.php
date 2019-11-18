<?php

namespace App\Controller\Admin;

use App\Entity\Etat;
use App\Form\EtatType;
use App\Repository\EtatRepository;
use App\Repository\CouleurRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EtatController extends AbstractController
{
    /**
     * @Route("/etat", name="etat")
     * @IsGranted("ROLE_ADMIN")
     */
    public function liste(EtatRepository $repo)
    {
        $etats= $repo->findAll();
        return $this->render('etat/liste.html.twig', [
            'etats' => $etats
        ]);
    }

    /**
     * @Route("/etat/creer", name="etat_creer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function creer(Request $request, ObjectManager $manager)
    {
        

        $etat = new Etat();

        $form = $this-> createForm(EtatType::class, $etat);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($etat);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'état a bien été enregistré !"
            );
           
            return $this->redirectToRoute('etat');
        }

        return $this->render('etat/creer.html.twig',[
            'form'=> $form->CreateView(),
            
        ]);
    }

    /**
     * Editer une catégorie
     * @Route("/etat/{id}/editer", name="etat_editer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer(Etat $etat,Request $request, ObjectManager $manager){

        $form = $this-> createForm(EtatType::class, $etat);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($etat);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'état' a bien été modifié !"
            );
           
            return $this->redirectToRoute('etat');
        }

        return $this->render('etat/editer.html.twig',[
            'form'=> $form->CreateView(),
            'etat' => $etat
        ]);
    }

    /**
     * @Route("/etat/{id}/supprimer", name="etat_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Etat $etat, ObjectManager $manager){

        $manager->remove($etat);
        $manager->flush();

        $this->addFlash('success', "L'etat a bien été supprimé !");

        return $this->redirectToRoute('etat');
    }
}
