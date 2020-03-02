<?php

namespace App\Repository;

use App\Entity\MasteryLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MasteryLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method MasteryLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method MasteryLevel[]    findAll()
 * @method MasteryLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MasteryLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MasteryLevel::class);
    }

    // /**
    //  * @return MasteryLevel[] Returns an array of MasteryLevel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MasteryLevel
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
