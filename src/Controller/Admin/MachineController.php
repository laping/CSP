<?php

namespace App\Controller\Admin;

use App\Entity\Machine;
use App\Form\MachineType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MachineController extends AbstractController
{
   /**
     * @Route("/machine/creer", name="machine_creer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function creer(Request $request, EntityManagerInterface $manager)
    {
        
        $machine = new Machine();

        $form = $this-> createForm(MachineType::class, $machine); /* Crée le formulaire  */

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /*Validation du formulaire renvoyé par l'utilisateur */
            
            $manager->persist($machine);
            $manager->flush();

            $this->addFlash(
                'success',
                "La machine a bien été enregistrée !"
            );
           
            return $this->redirectToRoute('admin_liste');
        }

        return $this->render('machine/creer.html.twig',[ /*Renvoie vers une vue avec le formulaire en paramètre */
            'form'=> $form->CreateView(),
            
        ]);
    }

    /**
     * Editer une machine
     * @Route("/machine/{id}/editer", name="machine_editer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer(Machine $machine,Request $request, EntityManagerInterface $manager){

        $form = $this-> createForm(MachineType::class, $machine);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($machine);
            $manager->flush();

            $this->addFlash(
                'success',
                "La machine a bien été modifiée !"
            );
           
            return $this->redirectToRoute('admin_liste');
        }

        return $this->render('machine/editer.html.twig',[
            'form'=> $form->CreateView(),
            'machine' => $machine
        ]);
    }

    /**
     * @Route("/machine/{id}/supprimer", name="machine_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Machine $machine, EntityManagerInterface $manager){

        $manager->remove($machine);
        $manager->flush();

        $this->addFlash('success', "La machine a bien été supprimée !");

        return $this->redirectToRoute('admin_liste');
    }
}
