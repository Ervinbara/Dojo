<?php

namespace App\Repository;

use App\Entity\CategoryExercice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CategoryExercice|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryExercice|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryExercice[]    findAll()
 * @method CategoryExercice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryExerciceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryExercice::class);
    }

    // /**
    //  * @return CategoryExercice[] Returns an array of CategoryExercice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryExercice
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
