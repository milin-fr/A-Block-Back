<?php

namespace App\Repository;

use App\Entity\Prerequisite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Prerequisite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prerequisite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prerequisite[]    findAll()
 * @method Prerequisite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrerequisiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prerequisite::class);
    }

    // /**
    //  * @return Prerequisite[] Returns an array of Prerequisite objects
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
    public function findOneBySomeField($value): ?Prerequisite
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
