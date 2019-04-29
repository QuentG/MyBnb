<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController {

	/**
	 * Affiche la page d'accueil
	 *
	 * @param AnnonceRepository $annonceRepository
	 * @param UserRepository $userRepository
	 * @return Response
	 */
	public function Home(AnnonceRepository $annonceRepository, UserRepository $userRepository): Response
	{
		return $this->render('home.html.twig', [
			'bestAnnonces' => $annonceRepository->findBestAnnonces(3),
			'bestUsers' => $userRepository->findBestUsers()
		]);
	}

}
