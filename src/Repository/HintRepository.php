<?php

namespace App\Repository;

use App\Entity\Hint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Hint|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hint|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hint[]    findAll()
 * @method Hint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HintRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hint::class);
    }

    // /**
    //  * @return Hint[] Returns an array of Hint objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hint
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
