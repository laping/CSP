<?php

namespace App\Controller\Admin;

use App\Entity\Systeme;
use App\Form\SystemeType;
use App\Repository\SystemeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SystemeController extends AbstractController
{
    /**
     * @Route("/systeme", name="systeme")
     * @IsGranted("ROLE_ADMIN")
     */
    public function liste(SystemeRepository $repo)
    {
        $systemes= $repo->findAll();
        return $this->render('systeme/liste.html.twig', [
            'systemes' => $systemes
        ]);
    }

    /**
     * @Route("/systeme/creer", name="systeme_creer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function creer(Request $request, ObjectManager $manager)
    {

        $systeme = new Systeme();

        $form = $this-> createForm(SystemeType::class, $systeme);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($systeme);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le système a bien été enregistré !"
            );
           
            return $this->redirectToRoute('systeme');
        }

        return $this->render('systeme/creer.html.twig',[
            'form'=> $form->CreateView()
        ]);
    }

    /**
     * Editer un système
     * @Route("/systeme/{id}/editer", name="systeme_editer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer(Systeme $systeme,Request $request, ObjectManager $manager){

        $form = $this-> createForm(SystemeType::class, $systeme);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($systeme);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le système a bien été modifié !"
            );
           
            return $this->redirectToRoute('systeme');
        }

        return $this->render('systeme/editer.html.twig',[
            'form'=> $form->CreateView(),
            'systeme' => $systeme
        ]);
    }

     /**
     * @Route("/systeme/{id}/supprimer", name="systeme_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Systeme $systeme, ObjectManager $manager){

        $manager->remove($systeme);
        $manager->flush();

        $this->addFlash('success', "Le système a bien été supprimé !");

        return $this->redirectToRoute('systeme');
    }
}
