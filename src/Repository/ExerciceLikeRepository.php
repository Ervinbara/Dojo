<?php

namespace App\Repository;

use App\Entity\ExerciceLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ExerciceLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExerciceLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExerciceLike[]    findAll()
 * @method ExerciceLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciceLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciceLike::class);
    }

    // /**
    //  * @return ExerciceLike[] Returns an array of ExerciceLike objects
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
    public function findOneBySomeField($value): ?ExerciceLike
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
