<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\AdminReservationType;
use App\Repository\ReservationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminReservationController extends AbstractController
{

	/**
	 * @var ReservationRepository
	 */
	private $repository;

	/**
	 * @var ObjectManager
	 */
	private $manager;

	/**
	 * AdminReservationController constructor.
	 *
	 * @param ReservationRepository $repository
	 * @param ObjectManager $manager
	 */
	public function __construct(ReservationRepository $repository, ObjectManager $manager)
	{
		$this->repository = $repository;
		$this->manager = $manager;
	}

	/**
	 * Affiche toutes les réservations
	 *
	 * @return Response
	 */
	public function index()
    {
        return $this->render('admin/reservation/index.html.twig', [
			'reservations' => $this->repository->findAll()
        ]);
    }

	/**
	 * Editer une réservation
	 *
	 * @param Reservation $reservation
	 * @param Request $request
	 * @return Response
	 */
	public function editReservation(Reservation $reservation, Request $request)
	{
		$form = $this->createForm(AdminReservationType::class, $reservation);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {

			// On le gère avec le @ORM\PreUpdate sur la func prePersist()
			// 0 est compté comme une valuer VIDE = empty($this->$amount)
			$reservation->setAmount(0);

			$this->manager->persist($reservation);
			$this->manager->flush();

			$this->addFlash('success', "La réservation n°{$reservation->getId()} a bien été modifiée !");

			return $this->redirectToRoute('admin_reservations_index');
		}

		return $this->render('admin/reservation/edit.html.twig', [
			'reservation' => $reservation,
			'form' => $form->createView()
		]);
	}

	/**
	 * Supprimer une réservation
	 *
	 * @param Reservation $reservation
	 * @return Response
	 */
	public function deleteReservation(Reservation $reservation)
	{
		$this->manager->remove($reservation);
		$this->manager->flush();

		$this->addFlash('success', "La réservation a bien été supprimée !");

		return $this->redirectToRoute('admin_reservations_index');
	}
}
