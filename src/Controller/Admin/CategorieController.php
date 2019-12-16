<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
     /**
     * @Route("/categorie", name="categorie")
     * @IsGranted("ROLE_ADMIN")
     */
    public function liste(CategorieRepository $repo)
    {
        $categories= $repo->findAll(); /*Permet de rassembler toutes les catégories en un tableau */

        return $this->render('categorie/liste.html.twig', [ /*Renvoie vers une vue avec le tableau des catégories en paramètre */
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/categorie/creer", name="categorie_creer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function creer(Request $request, EntityManagerInterface $manager)
    {

        $categorie = new categorie();

        $form = $this-> createForm(CategorieType::class, $categorie); /* Création du formulaire de nouvelle catégorie */

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /* Validation du formulaire après soumission par l'utilisateur */
            
            $manager->persist($categorie);
            $manager->flush(); /* Valide et effectue les changements dans la base de données */

            $this->addFlash(
                'success',
                "La catégorie a bien été enregistrée !"
            );
           
            return $this->redirectToRoute('categorie');
        }

        return $this->render('categorie/creer.html.twig',[
            'form'=> $form->CreateView()
        ]);
    }

    /**
     * Editer une catégorie
     * @Route("/categorie/{id}/editer", name="categorie_editer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer(Categorie $categorie,Request $request, EntityManagerInterface $manager){

        $form = $this-> createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($categorie);
            $manager->flush();

            $this->addFlash(
                'success',
                "La catégorie a bien été modifiée !"
            );
           
            return $this->redirectToRoute('categorie');
        }

        return $this->render('categorie/editer.html.twig',[
            'form'=> $form->CreateView(),
            'categorie' => $categorie
        ]);
    }

    /**
     * @Route("/categorie/{id}/supprimer", name="categorie_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Categorie $categorie, EntityManagerInterface $manager){

        $manager->remove($categorie);
        $manager->flush();

        $this->addFlash('success', "La catégorie a bien été supprimée !");

        return $this->redirectToRoute('categorie');
    }
}
