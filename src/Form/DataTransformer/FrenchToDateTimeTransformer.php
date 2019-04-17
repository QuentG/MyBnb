<?php

namespace App\Form\DataTransformer;

use DateTime;
use Exception;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface
{

	/**
	 * @param mixed $date The value in the original representation
	 * @return mixed The value in the transformed representation
	 * @throws TransformationFailedException when the transformation fails
	 */
	public function transform($date)
	{
		if ($date === null)
		{
			return '';
		}

		return $date->format('d/m/Y');
	}

	/**
	 * @param mixed $frenchDate The value in the transformed representation
	 * @return mixed The value in the original representation
	 * @throws Exception
	 */
	public function reverseTransform($frenchDate)
	{
		if($frenchDate === null) {
			throw new TransformationFailedException("Veuillez fournir une date");
		}

		$date = date_create_from_format('d/m/Y', $frenchDate);

		if($date === false) {
			throw new TransformationFailedException("Le format de date n'est pas le bon");
		}

		return $date;
	}
}