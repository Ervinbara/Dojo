<?php

namespace App\Repository;

use App\Entity\ExerciceComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ExerciceComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExerciceComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExerciceComment[]    findAll()
 * @method ExerciceComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciceCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciceComment::class);
    }
    public function report_exo(int $comment,bool $true){
        $qb = $this->createQueryBuilder('c')
        ->update()
        ->set('c.report', '?1')
        ->setParameter(1, $true)
        ->where("c.id = ?2")
        ->setParameter(2,$comment)
        ->getQuery()
        ->getResult();
        return $qb;
    }
    // /**
    //  * @return ExerciceComment[] Returns an array of ExerciceComment objects
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
    public function findOneBySomeField($value): ?ExerciceComment
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
