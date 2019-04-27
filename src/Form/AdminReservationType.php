<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Reservation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminReservationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
            	'widget' => 'single_text'
			])
            ->add('endDate', DateType::class, [
            	'widget' => 'single_text'
			])
            ->add('comment', TextareaType::class)
            ->add('reserveur', EntityType::class, [
            	'class' => User::class,
				'choice_label' => function ($user) {
            		return $user->getFirstname() . " " . strtoupper($user->getLastname());
				}
			])
            ->add('annonce', EntityType::class, [
            	'class' => Annonce::class,
				'choice_label' => 'title'
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
