<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, $this->getConfig("Prénom", "Votre prénom"))
            ->add('lastname', TextType::class, $this->getConfig("Nom", "Votre nom"))
            ->add('email', EmailType::class, $this->getConfig("Adresse email", "Votre adresse mail"))
            ->add('picture', UrlType::class, $this->getConfig("Photo de profil", "Url de votre avatar"))
            ->add('introduction', TextType::class, $this->getConfig("Introduction", "Présentez vous en quelques mots"))
            ->add('description', TextareaType::class, $this->getConfig("Description détaillée", "Présentez vous en détails"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
