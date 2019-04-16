<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends AbstractController
{
	/**
	 * @var ObjectManager
	 */
	private $manager;
	/**
	 * @var ReservationRepository
	 */
	private $repository;

	public function __construct(ReservationRepository $repository, ObjectManager $manager)
	{
		$this->repository = $repository;
		$this->manager = $manager;
	}

	/**
	 * Formulaire des réservations
	 *
	 * @param Annonce $annonce
	 * @param Request $request
	 * @return Response
	 * @IsGranted("ROLE_USER")
	 */
	public function reserver(Annonce $annonce, Request $request)
    {
		$reservation = new Reservation();
    	$form = $this->createForm(ReservationType::class, $reservation);
    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid())
		{
			$user = $this->getUser();

			$reservation->setReserveur($user)
				->setAnnonce($annonce);

			if(!$reservation->isPossibleDates())
			{
				$this->addFlash('warning', "Les dates choisis ne sont pas disponibles");
			} else {

				$this->manager->persist($reservation);
				$this->manager->flush();

				return $this->redirectToRoute('show-reservation', [
					'id' => $reservation->getId(),
					'withAlert' => true
				]);
			}
		}

        return $this->render('reservation/reserver.html.twig', [
        	'annonce' => $annonce,
			'form' => $form->createView()
        ]);
    }

	/**
	 * Voir la page d'une réservation
	 *
	 * @param Reservation $reservation
	 * @return Response
	 */
    public function show(Reservation $reservation)
	{
		return $this->render('reservation/show.html.twig', [
			'reservation' => $reservation
		]);
	}
}
