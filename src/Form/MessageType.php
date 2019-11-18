<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\Personnel;
use App\Entity\Utilisateur;
use App\Form\ApplicationType;
use App\Repository\PersonnelRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('titre',TextType::class,$this->getConfiguration("Titre", "Ecrivez un titre"))
            ->add('contenu',TextareaType::class,$this->getConfiguration("Contenu", "Ecrivez votre message"))
            ->add('signature', EntityType::class, [
                // Choix de l'entité dans laquelle chercher
                'class' => Personnel::class,
                'placeholder' => 'Sélectionnez votre nom',
                
                'empty_data' => null,
            
                'choice_label' => 'nom',
                'query_builder' => function (PersonnelRepository $repo_perso) {
                    return $repo_perso->createQueryBuilder('perso')
                        ->orderBy('perso.nom', 'ASC');
                }
            
            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
