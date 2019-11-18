<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Machine;
use App\Entity\Categorie;
use App\Entity\Peripherique;
use App\Repository\EtatRepository;
use App\Repository\MachineRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PeripheriqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('id', TextType::class, [
            'label' => 'ID',
            'required' => true,
            'attr' => [
                'placeholder' => "Entrez l'ID'"
            ]
        ])
        ->add('serial', TextType::class, [
            'label' => 'Serial',
            'required' => false,
            'attr' => [
                'placeholder' => "Entrez le numéro de série"
            ]
        ])
        ->add('machine', EntityType::class, [
            // looks for choices from this entity
            'class' => Machine::class,
            'required' => false,
        
            'choice_label' => 'id',
            'query_builder' => function (MachineRepository $repo_mach) {
                return $repo_mach->createQueryBuilder('mach')
                    ->orderBy('mach.id', 'ASC');
            }
        
            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        
        ])
        ->add('categorie', EntityType::class, [
            // looks for choices from this entity
            'class' => Categorie::class,
            'required' => false,
        
            'choice_label' => 'nom',
            'query_builder' => function (CategorieRepository $repo_cat) {
                return $repo_cat->createQueryBuilder('cat')
                    ->orderBy('cat.nom', 'ASC');
            }
        
            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        
        ])
        ->add('etat', EntityType::class, [
            // looks for choices from this entity
            'class' => Etat::class,
            'required' => false,
        
            'choice_label' => 'nom',
            'query_builder' => function (EtatRepository $repo_etat) {
                return $repo_etat->createQueryBuilder('etat')
                    ->orderBy('etat.nom', 'ASC');
            }
        
            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Peripherique::class,
        ]);
    }
}
