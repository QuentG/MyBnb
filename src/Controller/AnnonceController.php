<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnonceController extends AbstractController
{
	/**
	 * @var AnnonceRepository
	 */
	private $repository;

	/**
	 * @var ObjectManager
	 */
	private $manager;

	/**
	 * AnnonceController constructor.
	 * @param AnnonceRepository $repository
	 * @param ObjectManager $manager
	 */
	public function __construct(AnnonceRepository $repository, ObjectManager $manager)
	{
		$this->repository = $repository;
		$this->manager = $manager;
	}

	public function index()
    {
    	$annonces = $this->repository->findAll();
        return $this->render('annonce/index.html.twig', [
        	'annonces' => $annonces
        ]);
    }

    public function showOneAnnonce(string $slug)
	{
		// Recup d'une annonce en fonction du slug
		$annonce = $this->repository->findOneBySlug($slug);

		return $this->render('annonce/show.html.twig', [
			'annonce' => $annonce
		]);
	}
}
