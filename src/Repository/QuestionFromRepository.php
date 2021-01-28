<?php

namespace App\Repository;

use App\Entity\QuestionFrom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method QuestionFrom|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionFrom|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionFrom[]    findAll()
 * @method QuestionFrom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionFromRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionFrom::class);
    }

    // /**
    //  * @return QuestionFrom[] Returns an array of QuestionFrom objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuestionFrom
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
