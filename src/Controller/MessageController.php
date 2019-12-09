<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\MessageRepository;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route("/message/creer", name="creer_message")
     * @IsGranted("ROLE_USER")
     */
    public function creer(Request $request, ObjectManager $manager)
    {
        $message = new Message();

        $ip = $request->getClientIp();

        $form = $this->createForm(MessageType::class, $message); /* Création du formulaire de nouveau message */

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /* Validation du formulaire après soumission par l'utilisateur */

            $message->setDateCreation(new \DateTime()); /* Remplit le champ 'dateCreation' avec la date actuelle */
            $message->setIsArchived(false); /* Passe le booléen relatif à l'archivage à 0 */
            $message->setAuteur($this->getUser()); /* Définit l'utilisateur connecté en tant qu'auteur */
            $message->setIp($ip);
            
            $manager->persist($message);
            $manager->flush(); /* Valide et effectue les changements dans la base de données */

            $this->addFlash(
                'success',
                "Le message a bien été enregistré !"
            );
           
            return $this->redirectToRoute('communication');
        }

        return $this->render('message/creer.html.twig',[ /* Renvoie vers une vue avec le formulaire créé en paramètre */
            'form'=> $form->CreateView(),
            'ip' => $ip
            
        ]);
    }

    /**
     * @Route("/message/{id}/editer", name="editer_message")
     * @IsGranted("ROLE_USER")
     */
    public function editer($id,MessageRepository $repo_mess, Request $request, ObjectManager $manager)
    {
        $message= $repo_mess->find($id); /* Accède au message qui a l'ID choisi parmi la liste de tous les messages */

        $ip = $request->getClientIp();
       

        $form = $this->createForm(MessageType::class, $message); /*Création du formulaire d'édition de message */

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /* Validation du formulaire après soumission par l'utilisateur */

            $message->setDateCreation(new \DateTime()); /* Remplit le champ 'dateCreation' avec la date actuelle */
            $message->setIp($ip);
            
            
            $manager->persist($message);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le message a bien été modifié !"
            );
           
            return $this->redirectToRoute('afficher_message',[  /* Renvoie vers la vue affichant le message concerné */
                'id'=> $message->getId()
            ]);
        }

        return $this->render('message/editer.html.twig',[  /* Renvoie vers une vue avec le formulaire crée */
            'form'=> $form->CreateView(),
            "message" => $message
        ]);
    }

    /**
     * @Route("/message/{id}/supprimer", name="message_supprimer")
     * @IsGranted("ROLE_USER")
     */
    public function supprimer(Message $message, ObjectManager $manager){

        $manager->remove($message);
        $manager->flush();

        $this->addFlash('success', "Le message a bien été supprimé !");

        return $this->redirectToRoute('communication');
    }

}
