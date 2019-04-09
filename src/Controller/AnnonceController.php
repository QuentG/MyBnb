<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

	/**
	 * @return Response
	 */
	public function index()
    {
    	$annonces = $this->repository->findAll();
        return $this->render('annonce/index.html.twig', [
        	'annonces' => $annonces
        ]);
    }

	/**
	 * @param string $slug
	 * @return Response
	 */
    public function showOneAnnonce(string $slug)
	{
		// Recup d'une annonce en fonction du slug
		$annonce = $this->repository->findOneBySlug($slug);

		return $this->render('annonce/show.html.twig', [
			'annonce' => $annonce
		]);
	}

	/**
	 * @param Request $request
	 * @return Response
	 */
	public function createAnnonce(Request $request)
	{
		$annonce = new Annonce();

		$form = $this->createForm(AnnonceType::class, $annonce);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			$this->manager->persist($annonce);
			$this->manager->flush();

			$this->addFlash('success',
				"L'annonce <strong>{$annonce->getTitle()}</strong>a bien été enregistrée !"
			);

			return $this->redirectToRoute('annonce', [
				'slug' => $annonce->getSlug()
			]);
		}

		return $this->render('annonce/add.html.twig', [
			'form' => $form->createView()
		]);
	}
}
