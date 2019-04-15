<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
	 * Voir toutes les annonces !
	 *
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
	 * Voir une annonce en fonction de son slug !
	 *
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
	 * Ajouter une annonce !
	 *
	 * @param Request $request
	 * @return Response
	 *
	 * @IsGranted("ROLE_USER")
	 */
	public function createAnnonce(Request $request)
	{
		$annonce = new Annonce();

		$form = $this->createForm(AnnonceType::class, $annonce);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// Toutes les images
			foreach ($annonce->getImages() as $img)
			{
				$img->setAnnonce($annonce);
				$this->manager->persist($img);
			}

			$annonce->setAuthor($this->getUser());

			$this->manager->persist($annonce);
			$this->manager->flush();

			$this->addFlash('success',
				"L'annonce <strong>{$annonce->getTitle()}</strong> a bien été enregistrée !"
			);

			return $this->redirectToRoute('annonce', [
				'slug' => $annonce->getSlug()
			]);
		}

		return $this->render('annonce/add.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * Modifier une annonce !
	 *
	 * @param Request $request
	 * @param Annonce $annonce (ParamConverter)
	 * @return Response
	 *
	 * @Security("is_granted('ROLE_USER') and user === annonce.getAuthor()",
	 *      message="Vous ne pouvez pas modifier les annonces des autres utilisateurs !")
	 */
	public function editAnnonce(Request $request, Annonce $annonce)
	{
		$form = $this->createForm(AnnonceType::class, $annonce);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			// Toutes les images
			foreach ($annonce->getImages() as $img)
			{
				$img->setAnnonce($annonce);
				$this->manager->persist($img);
			}

			$this->manager->persist($annonce);
			$this->manager->flush();

			$this->addFlash('success',
				"L'annonce <strong>{$annonce->getTitle()}</strong> a bien été modifiée !"
			);

			return $this->redirectToRoute('annonce', [
				'slug' => $annonce->getSlug()
			]);
		}

		return $this->render('annonce/edit.html.twig', [
			'annonce' => $annonce,
			'form' => $form->createView()
		]);
	}

	/**
	 * Permet de supprimer une annonce
	 *
	 * @return Response
	 *
	 * @Security("is_granted('ROLE_USER') and user === annonce.getAuthor()", message="Vous ne pouvez pas supprimer les annonces d'autres utilisateurs !")
	 */
	public function deleteAnnonce(Annonce $annonce)
	{
		$this->manager->remove($annonce);
		$this->manager->flush();

		$this->addFlash('success',
			"L'annonce <strong>{$annonce->getTitle()}</strong> a bien été supprimer !");

		return $this->redirectToRoute("annonces");
	}
}
