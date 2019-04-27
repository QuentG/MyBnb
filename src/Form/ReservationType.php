<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends ApplicationType
{

	/**
	 * @var FrenchToDateTimeTransformer
	 */
	private $transformer;

	public function __construct(FrenchToDateTimeTransformer $transformer)
	{
		$this->transformer = $transformer;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::class, $this->getConfig("Date d'arivée", "Choisissez une date d'arrivée pour votre séjour"))
            ->add('endDate', TextType::class, $this->getConfig(" Date de fin", "Choisissez une date de fin pour votre séjour"))
			->add('comment', TextareaType::class, $this->getConfig(false, "Ecrit moi dont un petit commentaire 😎", [
				'required' => false
			]))
        ;

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
			// Permet de préciser les groups de validation qu'on veut executer
			'validation_groups' => ['Default', 'front']
        ]);
    }
}
