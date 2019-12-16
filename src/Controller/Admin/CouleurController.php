<?php

namespace App\Controller\Admin;

use App\Entity\Couleur;
use App\Form\CouleurType;
use App\Repository\CouleurRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CouleurController extends AbstractController
{
    /**
     * @Route("/couleur", name="couleur")
     * @IsGranted("ROLE_ADMIN")
     */
    public function liste(CouleurRepository $repo)
    {
        $couleurs= $repo->findAll();
        return $this->render('couleur/liste.html.twig', [
            'couleurs' => $couleurs
        ]);
    }

     /**
     *  @route("/couleur/creer", name="couleur_creer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function creer(Request $request, EntityManagerInterface $manager)
    {

        $couleur = new couleur();

        $form = $this-> createForm(CouleurType::class, $couleur);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($couleur);
            $manager->flush();

            $this->addFlash(
                'success',
                "La couleur a bien été enregistrée !"
            );
           
            return $this->redirectToRoute('couleur');
        }

        return $this->render('couleur/creer.html.twig',[
            'form'=> $form->CreateView()
        ]);
    }

    /**
     * Editer une couleur
     * @Route("/couleur/{id}/editer", name="couleur_editer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer(Couleur $couleur,Request $request, EntityManagerInterface $manager){

        $form = $this-> createForm(CouleurType::class, $couleur);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($couleur);
            $manager->flush();

            $this->addFlash(
                'success',
                "La couleur a bien été modifiée !"
            );
           
            return $this->redirectToRoute('couleur');
        }

        return $this->render('couleur/editer.html.twig',[
            'form'=> $form->CreateView(),
            'couleur' => $couleur
        ]);
    }

    /**
     * @Route("/couleur/{id}/supprimer", name="couleur_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Couleur $couleur, EntityManagerInterface $manager){

        $manager->remove($couleur);
        $manager->flush();

        $this->addFlash('success', "La couleur a bien été supprimée !");

        return $this->redirectToRoute('couleur');
    }
}
