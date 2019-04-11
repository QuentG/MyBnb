<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType {

	/**
	 * @param $label
	 * @param $placeholder
	 * @param bool $required
	 * @return array
	 */
	protected function getConfig($label, $placeholder, $required = true)
	{
		return [
			'label' => $label,
			'attr' => [
				'placeholder' => $placeholder
			],
			'required' => $required
		];
	}

}