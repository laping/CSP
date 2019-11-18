<?php

namespace App\Controller\Admin;

use App\Entity\Version;
use App\Form\VersionType;
use App\Repository\VersionRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VersionController extends AbstractController
{
    /**
     * @Route("/version", name="version")
     * @IsGranted("ROLE_ADMIN")
     */
    public function liste(VersionRepository $repo)
    {
        $versions= $repo->findAll();
        return $this->render('version/liste.html.twig', [
            'versions' => $versions
        ]);
    }

    /**
     * @Route("/version/creer", name="version_creer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function creer(Request $request, ObjectManager $manager)
    {
        

        $version = new Version();

        $form = $this-> createForm(VersionType::class, $version);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($version);
            $manager->flush();

            $this->addFlash(
                'success',
                "La version a bien été enregistrée !"
            );
           
            return $this->redirectToRoute('version');
        }

        return $this->render('version/creer.html.twig',[
            'form'=> $form->CreateView(),
            
        ]);
    }

    /**
     * Editer une version
     * @Route("/version/{id}/editer", name="version_editer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer(Version $version,Request $request, ObjectManager $manager){

        $form = $this-> createForm(VersionType::class, $version);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($version);
            $manager->flush();

            $this->addFlash(
                'success',
                "La version a bien été modifiée !"
            );
           
            return $this->redirectToRoute('version');
        }

        return $this->render('version/editer.html.twig',[
            'form'=> $form->CreateView(),
            'version' => $version
        ]);
    }

     /**
     * @Route("/version/{id}/supprimer", name="version_supprimer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer(Version $version, ObjectManager $manager){

        $manager->remove($version);
        $manager->flush();

        $this->addFlash('success', "La version a bien été supprimée !");

        return $this->redirectToRoute('version');
    }
}
