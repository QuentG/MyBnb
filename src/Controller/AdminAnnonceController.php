<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use App\Service\Pagination;
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
	 * @param Pagination $pagination
	 * @return Response
	 */
	public function index(int $page, Pagination $pagination)
    {
    	$pagination->setEntityClass(Annonce::class)
			->setCurrentPage($page);

        return $this->render('admin/annonce/index.html.twig', [
        	'pagination' => $pagination
        ]);
    }
}
