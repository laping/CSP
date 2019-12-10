<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Modele;
use App\Entity\Machine;
use App\Entity\Version;
use App\Entity\Categorie;
use App\Entity\Emplacement;
use App\Form\ApplicationType;
use App\Repository\EtatRepository;
use App\Repository\VersionRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\EmplacementRepository;
use App\Repository\ModeleRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MachineType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('id', TextType::class,$this->getConfiguration("ID", "Entrez un ID"), [
            'required' => true
        ])
        ->add('serial', TextType::class, [
            'label' => 'Serial',
            'required' => false,
            'attr' => [
                'placeholder' => "Entrez le numéro de série"
            ]
        ])
        ->add('commentaire', TextAreaType::class, [
            'label' => 'Commentaire',
            'required' => false,
            'attr' => [
                'placeholder' => "Entrez un commentaire"
            ]
        ])
        ->add('ip', TextType::class,$this->getConfiguration("IP", "Entrez une IP"), [
            'required' => false
        ])
        ->add('categorie', EntityType::class, [
            // Choix de l'entité dans laquelle chercher
            'class' => Categorie::class,
        
            'choice_label' => 'nom',
            'query_builder' => function (CategorieRepository $repo_cat) {
                return $repo_cat->createQueryBuilder('cat')
                    ->orderBy('cat.nom', 'ASC');
            }
        
        
        ])
        ->add('etat', EntityType::class, [
            // looks for choices from this entity
            'class' => Etat::class,
        
            'choice_label' => 'nom',
            'query_builder' => function (EtatRepository $repo_etat) {
                return $repo_etat->createQueryBuilder('etat')
                    ->orderBy('etat.nom', 'ASC');
            }
        
            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        ])
        ->add('modele', EntityType::class, [
            // looks for choices from this entity
            'class' => Modele::class,
        
            'choice_label' => function($modele) {
                return $modele->getMarque()->getNom() . " - " . $modele->getNom(); 
            },
            'query_builder' => function (ModeleRepository $repo_modele) {
                return $repo_modele->createQueryBuilder('modele')
                    ->orderBy('modele.marque', 'DESC');
                    
            }
        
            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        ])
        ->add('emplacement', EntityType::class, [
            // looks for choices from this entity
            'class' => Emplacement::class,
    
            'choice_label' => 'nom',
            'query_builder' => function (EmplacementRepository $repo_emp) {
                return $repo_emp->createQueryBuilder('emp')
                    ->orderBy('emp.nom', 'ASC');
            }
        
            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        ])
        ->add('version', EntityType::class, [
            // looks for choices from this entity
            'class' => Version::class,
        
            'choice_label' => function($version) {
                return $version->getSysteme()->getNom() . " - " . $version->getNom(); 
            },
            'query_builder' => function (VersionRepository $repo_ver) {
                return $repo_ver->createQueryBuilder('ver')
                    ->orderBy('ver.systeme', 'ASC');
            }
        
            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        ])
        ->add('clavier')
        ->add('souris')
        ->add('ecran')
        ->add('enceintes')
        ->add('chargeur')
        ->add('telecommande')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Machine::class,
        ]);
    }
}
