<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMessageController extends AbstractController
{
   /**
     * @Route("/admin/message/{id}/editer", name="admin_editer_message")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer($id,MessageRepository $repo_mess, Request $request, EntityManagerInterface $manager)
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
           
            return $this->redirectToRoute('communication');
        }

        return $this->render('admin/message_editer.html.twig',[  /* Renvoie vers une vue avec le formulaire crée */
            'form'=> $form->CreateView(),
            "message" => $message
        ]);
    }

    /**
     * @Route("/admin/message/{id}/supprimer", name="admin_message_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Message $message, EntityManagerInterface $manager){

        $manager->remove($message);
        $manager->flush();

        $this->addFlash('success', "Le message a bien été supprimé !");

        return $this->redirectToRoute('communication');
    }
}
