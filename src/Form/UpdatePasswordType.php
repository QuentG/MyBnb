<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdatePasswordType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getConfig("Ancien mot de passe", "Entrez votre ancien mot de passe"))
			->add('newPassword', PasswordType::class, $this->getConfig("Nouveau mot de passe", "Entrez votre nouveau mot de passe"))
			->add('confirmPassword', PasswordType::class, $this->getConfig("Confirmer le nouveau mot de passe", "Confirmez votre nouveau mot de passe"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
