<?php

namespace App\Repository;

use App\Entity\AdminPasswordEdition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AdminPasswordEdition|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminPasswordEdition|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminPasswordEdition[]    findAll()
 * @method AdminPasswordEdition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminPasswordEditionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdminPasswordEdition::class);
    }

    // /**
    //  * @return AdminPasswordEdition[] Returns an array of AdminPasswordEdition objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdminPasswordEdition
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
