<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController {

	/**
	 * @return Response
	 */
	public function Home(): Response
	{
		return $this->render('home.html.twig');
	}

}
