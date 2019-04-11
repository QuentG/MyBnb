<?php

namespace App\Repository;

use App\Entity\UpdatePassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UpdatePassword|null find($id, $lockMode = null, $lockVersion = null)
 * @method UpdatePassword|null findOneBy(array $criteria, array $orderBy = null)
 * @method UpdatePassword[]    findAll()
 * @method UpdatePassword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UpdatePasswordRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UpdatePassword::class);
    }

    // /**
    //  * @return UpdatePassword[] Returns an array of UpdatePassword objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UpdatePassword
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
