<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\MessageRepository;
use App\Repository\CommentaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommunicationController extends AbstractController
{
     /**
     * @Route("/communication/message/{id}/archive", name="archiver_message")
     * @IsGranted("ROLE_ADMIN") 
     */
    public function archiver($id, MessageRepository $repo_mess, EntityManagerInterface $manager)
    {
        $message= $repo_mess->find($id); /* Récupère un message spécifique (via l'Id) dans la table concernée */

        $message->setIsArchived(true);  /* Passe le booléen gérant le status d'archivage à 1 */

        $manager->persist($message);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le message a bien été archivé !"
        );

        return $this->redirectToRoute('communication');
    }

     /**
     * @Route("/communication/message/{id}/desarchive", name="desarchiver_message")
     * @IsGranted("ROLE_ADMIN") 
     */
    public function desarchiver($id, MessageRepository $repo_mess, EntityManagerInterface $manager)
    {
        $message= $repo_mess->find($id);

        $message->setIsArchived(false);

        $manager->persist($message);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le message a bien été désarchivé !"
        );

        return $this->redirectToRoute('communication');
    }

     /**
     * @Route("/archives", name="archives")
     * @IsGranted("ROLE_ADMIN")
     */
    public function archives(MessageRepository $repo_message, PaginatorInterface $paginator, Request $request)
    {
         // Retrieve the entity manager of Doctrine
         $em = $this->getDoctrine()->getManager();
        
         // Get some repository of data, in our case we have an Appointments entity
         $repo_message = $em->getRepository(Message::class);
                 

        // Find all the data on the Appointments table, filter your query as you need
        $allMessagesQuery = $repo_message->createQueryBuilder('m')
            ->where('m.IsArchived = true')
            ->orderBy('m.dateCreation', 'DESC')
            ->getQuery();
        
        // Paginate the results of the query
        $messages = $paginator->paginate(
            // Doctrine Query, not results
            $allMessagesQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );

        return $this->render('communication/archives.html.twig',[
            'messages'=> $messages
        ]);
    }

    /**
     * @Route("/archives/message/{id}", name="afficher_message_archives")
     * @IsGranted("ROLE_ADMIN")
     */
    public function afficherMessage($id, MessageRepository $repo_mess, CommentaireRepository $repo_comm, Request $request, EntityManagerInterface $manager)
    {
        $message= $repo_mess->find($id);
        $commentaires= $repo_comm->findAll(); /* Récupère un message spécifique (via l'Id) dans la table concernée ainsi que les commentaires liés */

        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire); /* Crée le formulaire Symfony de création de commentaire */

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ /* Vérification du formulaire soumis par l'utilisateur */

            $commentaire->setDateCreation(new \DateTime()); /* Ajout automatique de la date/heure de création du commentaire*/
            $commentaire->setMessage($message); /* Permet de lier le commentaire crée au message affiché*/
            $commentaire->setAuteur($this->getUser()); /* Définit l'utilisateur connecté en tant qu'auteur */
            
            $manager->persist($commentaire);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire a bien été enregistré !"
            );
           
            return $this->redirectToRoute('afficher_message',[
                'id'=> $message->getId()
            ]);
        }

        return $this->render('communication/message.html.twig',[
            'message' => $message,
            'commentaires' => $commentaires,
            'form' => $form->createView()
        ]);

    }

}
