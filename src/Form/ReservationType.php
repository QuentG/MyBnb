<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::class, $this->getConfig("Date d'arivée", "Choisissez une date d'arrivée pour votre séjour"))
            ->add('endDate', TextType::class, $this->getConfig(" Date de fin", "Choisissez une date de fin pour votre séjour"))
			->add('comment', TextareaType::class, $this->getConfig(false, "Ecrit moi dont un petit commentaire 😎", [
				'required' => false
			]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
