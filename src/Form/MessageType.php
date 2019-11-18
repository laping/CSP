<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\Utilisateur;
use App\Form\ApplicationType;
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
