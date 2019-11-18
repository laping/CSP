<?php

namespace App\Controller\Admin;

use App\Repository\MachineRepository;
use App\Repository\PeripheriqueRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin/liste", name="admin_liste")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(MachineRepository $repo_machine, PeripheriqueRepository $repo_periph)
    {
        $machines= $repo_machine->findAll(); /* Permet d'obtenir un tableau rassemblant toutes les machines */
        $periphs= $repo_periph->findAll(); /* Permet d'obtenir un tableau rassemblant tous les périphériques */

        return $this->render('admin/liste.html.twig', [
            'machines' => $machines,
            'periphs'=> $periphs
        ]);
    }

    
}
