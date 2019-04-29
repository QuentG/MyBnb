<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

	/**
	 * Récupère les 2 utilisateurs avec les meilleurs moyennes qui ont minimum 3 annonces
	 *
	 * @param int $limit
	 * @return mixed
	 */
	public function findBestUsers(int $limit = 2)
	{
		return $this->createQueryBuilder('u')
			->join('u.annonces', 'a')
			->join('a.comments', 'c')
			->select('u as user, AVG(c.rating) as rating, COUNT(c) as sumComms')
			->groupBy('u')
			->having('sumComms > 3')
			->orderBy('rating', 'DESC')
			->setMaxResults($limit)
			->getQuery()
			->getResult()
			;
	}
}
