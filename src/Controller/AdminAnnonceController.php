<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use App\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

	/**
	 * Editer une annonce d'un utilisateur
	 *
	 * @param Annonce $annonce
	 * @param Request $request
	 * @return Response
	 */
	public function editAnnonce(Annonce $annonce, Request $request)
	{
		$form = $this->createForm(AnnonceType::class, $annonce);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {

			$this->manager->persist($annonce);
			$this->manager->flush();

			$this->addFlash('success', "L'annonce {$annonce->getTitle()} a bien été modifiée !");

			return $this->redirectToRoute('admin_annonces_index');
		}

		return $this->render('admin/annonce/edit.html.twig', [
			'annonce' => $annonce,
			'form' => $form->createView()
		]);
	}

	/**
	 * Supprime une annonce
	 *
	 * @param Annonce $annonce
	 * @return Response
	 */
	public function deleteAnnonce(Annonce $annonce)
	{
		if(count($annonce->getReservations()) > 0) {
			$this->addFlash('warning', "Vous ne pouvez pas supprimer l'annonce {$annonce->getTitle()} car elle possède des réservations !");
		} else {
			$this->manager->remove($annonce);
			$this->manager->flush();

			$this->addFlash('success', "L'annonce <strong>{$annonce->getTitle()}</strong> a bien été supprimée !");
		}

		return $this->redirectToRoute('admin_annonces_index');
	}
}
