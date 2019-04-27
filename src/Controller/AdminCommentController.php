<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminCommentController extends AbstractController
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
	 * @param CommentRepository $repository
	 * @param ObjectManager $manager
	 */
	public function __construct(CommentRepository $repository, ObjectManager $manager)
	{
		$this->repository = $repository;
		$this->manager = $manager;
	}

	/**
	 * Affiche tous les commentaires de toutes les annonces
	 *
	 * @return Response
	 */
	public function index()
    {
    	$comments = $this->repository->findAll();
        return $this->render('admin/comment/index.html.twig', [
			'comments' => $comments
        ]);
    }
}
