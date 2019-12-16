<?php

namespace App\Repository;

use App\Entity\PasswordEdition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PasswordEdition|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordEdition|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordEdition[]    findAll()
 * @method PasswordEdition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordEditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordEdition::class);
    }

    // /**
    //  * @return PasswordEdition[] Returns an array of PasswordEdition objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PasswordEdition
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
