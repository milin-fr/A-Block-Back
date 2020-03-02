<?php

namespace App\Repository;

use App\Entity\ProgramComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProgramComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProgramComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProgramComment[]    findAll()
 * @method ProgramComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProgramComment::class);
    }

    // /**
    //  * @return ProgramComment[] Returns an array of ProgramComment objects
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
    public function findOneBySomeField($value): ?ProgramComment
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
