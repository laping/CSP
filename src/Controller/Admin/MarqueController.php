<?php

namespace App\Controller\Admin;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MarqueController extends AbstractController
{
     /**
     * @Route("/marque", name="marque")
     * @IsGranted("ROLE_ADMIN")
     */
    public function liste(MarqueRepository $repo)
    {
        $marques= $repo->findAll();
        return $this->render('marque/liste.html.twig', [
            'marques' => $marques
        ]);
    }

    /**
     * @Route("/marque/creer", name="marque_creer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function creer(Request $request, ObjectManager $manager)
    {

        $marque = new Marque();

        $form = $this-> createForm(MarqueType::class, $marque);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($marque);
            $manager->flush();

            $this->addFlash(
                'success',
                "La marque a bien été enregistrée !"
            );
           
            return $this->redirectToRoute('marque');
        }

        return $this->render('marque/creer.html.twig',[
            'form'=> $form->CreateView()
        ]);
    }

    /**
     * Editer une marque
     * @Route("/marque/{id}/editer", name="marque_editer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer(Marque $marque,Request $request, ObjectManager $manager){

        $form = $this-> createForm(MarqueType::class, $marque);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($marque);
            $manager->flush();

            $this->addFlash(
                'success',
                "La marque a bien été modifiée !"
            );
           
            return $this->redirectToRoute('marque');
        }

        return $this->render('marque/editer.html.twig',[
            'form'=> $form->CreateView(),
            'marque' => $marque
        ]);
    }

     /**
     * @Route("/marque/{id}/supprimer", name="marque_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Marque $marque, ObjectManager $manager){

        $manager->remove($marque);
        $manager->flush();

        $this->addFlash('success', "La marque a bien été supprimée !");

        return $this->redirectToRoute('marque');
    }
}
