<?php

namespace App\Repository;

use App\Entity\ExerciseComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ExerciseComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExerciseComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExerciseComment[]    findAll()
 * @method ExerciseComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciseCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciseComment::class);
    }

    // /**
    //  * @return ExerciseComment[] Returns an array of ExerciseComment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExerciseComment
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
