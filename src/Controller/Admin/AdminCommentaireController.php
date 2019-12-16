<?php

namespace App\Controller\Admin;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentaireController extends AbstractController
{
    /**
     * @Route("/admin/commentaire/{id}/editer", name="admin_editer_commentaire")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer($id,CommentaireRepository $repo, Request $request, EntityManagerInterface $manager)
    {
        $commentaire= $repo->find($id); /* Accède au message qui a l'ID choisi parmi la liste de tous les messages */

        $form = $this->createForm(CommentaireType::class, $commentaire); /*Création du formulaire d'édition de message */

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /* Validation du formulaire après soumission par l'utilisateur */

            $commentaire->setDateCreation(new \DateTime()); /* Remplit le champ 'dateCreation' avec la date actuelle */
            
            $manager->persist($commentaire);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le message a bien été modifié !"
            );
           
            return $this->redirectToRoute('afficher_message',[  /* Renvoie vers la vue affichant le message concerné */
                'id'=> $commentaire->getMessage()->getId()
            ]);
        }

        return $this->render('admin/commentaire_editer.html.twig',[  /* Renvoie vers une vue avec le formulaire crée */
            'form'=> $form->CreateView(),
            "commentaire" => $commentaire
        ]);
    }

    /**
     * @Route("/admin/commentaire/{id}/supprimer", name="admin_commentaire_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Commentaire $commentaire, EntityManagerInterface $manager){

        $manager->remove($commentaire);
        $manager->flush();

        $this->addFlash('success', "Le message a bien été supprimé !");

        return $this->redirectToRoute('communication');
    }
}
