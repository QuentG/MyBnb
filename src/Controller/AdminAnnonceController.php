<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminAnnonceController extends AbstractController
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
	 * AdminAnnonceController constructor.
	 *
	 * @param AnnonceRepository $repository
	 * @param ObjectManager $manager
	 */
	public function __construct(AnnonceRepository $repository, ObjectManager $manager)
	{
		$this->repository = $repository;
		$this->manager = $manager;
	}

	/**
	 * Affiche toutes les annonces !
	 *
	 * @return Response
	 */
	public function index()
    {
    	$annonces = $this->repository->findAll();
        return $this->render('admin/annonce/index.html.twig', [
        	'annonces' => $annonces
        ]);
    }
}
