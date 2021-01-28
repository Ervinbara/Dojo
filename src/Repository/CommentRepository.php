<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    
    public function report(int $comment,bool $true){
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

}
