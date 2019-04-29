<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

	/**
	 * Récupère les 5 annonces avec les meilleurs moyennes
	 *
	 * @param int $limit
	 * @return mixed
	 */
	public function findBestAnnonces(int $limit)
	{
    	return $this->createQueryBuilder('a')
			->select('a as annonce, AVG(c.rating) as rating')
			->join('a.comments', 'c')
			->groupBy('a')
			->orderBy('rating', 'DESC')
			->setMaxResults($limit)
			->getQuery()
			->getResult()
			;
	}

}
