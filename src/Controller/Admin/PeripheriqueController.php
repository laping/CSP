<?php

namespace App\Controller\Admin;

use App\Entity\Peripherique;
use App\Form\PeripheriqueType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PeripheriqueController extends AbstractController
{
    /**
     * @Route("/peripherique/creer", name="peripherique_creer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function creer(Request $request, EntityManagerInterface $manager)
    {
        
        $peripherique = new Peripherique();

        $form = $this-> createForm(PeripheriqueType::class, $peripherique);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($peripherique);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le périphérique a bien été enregistré !"
            );
           
            return $this->redirectToRoute('admin_liste');
        }

        return $this->render('peripherique/creer.html.twig',[
            'form'=> $form->CreateView(),
            
        ]);
    }

    /**
     * Editer un périphérique
     * @Route("/peripherique/{id}/editer", name="peripherique_editer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer(Peripherique $peripherique,Request $request, EntityManagerInterface $manager){

        $form = $this-> createForm(PeripheriqueType::class, $peripherique); /* Crée le formulaire d'édition */

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /*Validation du formulaire après soumission par l'utilisateur */
            
            $manager->persist($peripherique);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le périphérique a bien été modifié !"
            );
           
            return $this->redirectToRoute('admin_liste');
        }

        return $this->render('peripherique/editer.html.twig',[
            'form'=> $form->CreateView(),
            'peripherique' => $peripherique
        ]);
    }

    /**
     * @Route("/peripherique/{id}/supprimer", name="peripherique_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Peripherique $peripherique, EntityManagerInterface $manager){

        $manager->remove($peripherique);
        $manager->flush();

        $this->addFlash('success', "Le périphérique a bien été supprimé !");

        return $this->redirectToRoute('admin_liste');
    }
}
