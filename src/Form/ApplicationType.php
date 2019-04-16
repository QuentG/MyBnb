<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType {

	/**
	 * @param $label
	 * @param $placeholder
	 * @param $options
	 * @return array
	 */
	protected function getConfig($label, $placeholder, $options = [])
	{
		return array_merge_recursive([
			'label' => $label,
			'attr' => [
				'placeholder' => $placeholder
			]
		], $options);
	}

}