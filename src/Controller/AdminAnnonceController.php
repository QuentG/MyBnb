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
	 * @param int $page
	 * @return Response
	 */
	public function index(int $page)
    {
    	$limit = 10;
    	// 1 * 10 = 10 - 10 = 0 && 2 * 10 = 20 - 10 = 10 etc..
    	$start = $page * $limit - $limit;
    	$total = count($this->repository->findAll());

    	$pages = ceil($total / $limit); // 2,4 => ceil() = 3

        return $this->render('admin/annonce/index.html.twig', [
        	'annonces' => $this->repository->findBy([], [], $limit, $start),
			'page' => $page,
			'pages' => $pages
        ]);
    }
}
