<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class Stats {

	/**
	 * @var ObjectManager
	 */
	private $manager;

	/**
	 * Stats constructor.
	 *
	 * @param ObjectManager $manager
	 */
	public function __construct(ObjectManager $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * Recupère sous forme d'un tableau toutes les statistiques principales du site
	 *
	 * @return array
	 */
	public function getStats(): array
	{
		$users = $this->getUsersCount();
		$annonces = $this->getAnnoncesCount();
		$reservations = $this->getReservationsCount();
		$comments = $this->getCommentsCount();

		return compact('users', 'annonces', 'reservations', 'comments');
	}

	/**
	 * Récupère le nombre d'utilisateurs inscrits sur le site
	 *
	 * @return int
	 */
	public function getUsersCount(): int
	{
		return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')
			->getSingleScalarResult();
	}

	/**
	 * Récupère le nombre d'annonces postées
	 *
	 * @return int
	 */
	public function getAnnoncesCount(): int
	{
		return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Annonce a')
			->getSingleScalarResult();
	}

	/**
	 * Récupère le nombre de réservations
	 *
	 * @return int
	 */
	public function getReservationsCount(): int
	{
		return $this->manager->createQuery('SELECT COUNT(r) FROM App\Entity\Reservation r')
			->getSingleScalarResult();
	}

	/**
	 * Récupère le nombre d'avis donnés
	 *
	 * @return int
	 */
	public function getCommentsCount(): int
	{
		return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')
			->getSingleScalarResult();
	}

	/**
	 * Récupère la moyenne des 5 meilleurs / pires notes
	 *
	 * @param string $order = DESC : meilleurs notes / ASC : pires notes
	 * @return mixed
	 */
	public function getAnnoncesStats(string $order){
		return $this->manager->createQuery(
			'SELECT AVG(c.rating) as note, a.title, a.id, u.firstname, u.lastname, u.picture
			FROM App\Entity\Comment c
			JOIN c.annonce a
			JOIN a.author u
			GROUP BY a
			ORDER BY note ' . $order
		)
			->setMaxResults(5)
			->getResult();
	}

}