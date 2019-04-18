<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', IntegerType::class, $this->getConfig("Note sur 5", "Mettre une note sur 5 à votre séjour !", [
            	'attr' => [
            		'min' => 0,
					'max' => 5,
					'step' => 1
				]
			]))
            ->add('content', TextareaType::class, $this->getConfig("Contenu", "Ecrivez un avis pour les futurs voyageurs !"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
