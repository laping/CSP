<?php

namespace App\Controller\Admin;

use App\Entity\Modele;
use App\Form\ModeleType;
use App\Repository\ModeleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModeleController extends AbstractController
{
     /**
     * @Route("/modele", name="modele")
     * @IsGranted("ROLE_ADMIN")
     */
    public function liste(ModeleRepository $repo)
    {
        $modeles= $repo->findAll();
        return $this->render('modele/liste.html.twig', [
            'modeles' => $modeles
        ]);
    }

    /**
     * @Route("/modele/creer", name="modele_creer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function creer(Request $request, EntityManagerInterface $manager)
    {

        $modele = new Modele();

        $form = $this-> createForm(ModeleType::class, $modele);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($modele);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le modèle a bien été enregistré !"
            );
           
            return $this->redirectToRoute('modele');
        }

        return $this->render('modele/creer.html.twig',[
            'form'=> $form->CreateView()
        ]);
    }

    /**
     * Editer un modèle
     * @Route("/modele/{id}/editer", name="modele_editer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer(Modele $modele,Request $request, EntityManagerInterface $manager){

        $form = $this-> createForm(ModeleType::class, $modele);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($modele);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le modèle a bien été modifié !"
            );
           
            return $this->redirectToRoute('modele');
        }

        return $this->render('modele/editer.html.twig',[
            'form'=> $form->CreateView(),
            'modele' => $modele
        ]);
    }

      /**
     * @Route("/modele/{id}/supprimer", name="modele_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Modele $modele, EntityManagerInterface $manager){

        $manager->remove($modele);
        $manager->flush();

        $this->addFlash('success', "Le modèle a bien été supprimé !");

        return $this->redirectToRoute('modele');
    }
}
