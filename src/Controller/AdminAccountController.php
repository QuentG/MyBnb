<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
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
	 * Connexion admin !
	 *
	 * @param AuthenticationUtils $authenticationUtils
	 * @return Response
	 */
	public function login(AuthenticationUtils $authenticationUtils)
    {
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastname = $authenticationUtils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
        	'error' => $error !== null,
			'lastname' => $lastname
        ]);
    }

	/**
	 * Déconnexion admin !
	 *
	 * @return void
	 */
	public function logout () {}

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
	 * Supprime une annonce !
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
