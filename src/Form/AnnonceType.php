<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
	/**
	 * @param $label
	 * @param $placeholder
	 * @param bool $required
	 * @return array
	 */
	private function getConfig($label, $placeholder, $required = true)
	{
		return [
			'label' => $label,
			'attr' => [
				'placeholder' => $placeholder
			],
			'required' => $required
		];
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfig("Titre", "Titre de l'annonce"))
            ->add('slug', TextType::class, $this->getConfig("Slug", "Url automatique", false))
            ->add('price', MoneyType::class, $this->getConfig("Prix par nuit", "Indiquez le prix pour une nuit"))
            ->add('introduction', TextType::class, $this->getConfig("Introduction", "Ecrire une description globale de votre annonce"))
            ->add('content', TextareaType::class, $this->getConfig("Description", "Ecrire une description de votre annonce"))
			->add('rooms', IntegerType::class, $this->getConfig("Nombre de chambres", "Entrez le nombres de chambres disponibles"))
			->add('coverImage', UrlType::class, $this->getConfig("Url de l'image principale", "Donnez l'adresse de votre image principale"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
