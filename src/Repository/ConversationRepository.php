<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Messages;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method Messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messages[]    findAll()
 * @method Messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{   

    /**
     * @var User
     */
    private $user;

    public function __construct(ManagerRegistry $registry)
    {
         parent::__construct($registry, Messages::class);
    }
     

    public function getAllMessages(int $from, int $to) //,string $message 
     {
        $qb = $this->createQueryBuilder('f');
        $parameters = array(
            'f' => $from,
            't' => $to,
            // Attribuer f.from_id.user.username à un nouvel élèment du select
             
        );
        $qb
            ->select('f as msg , fr.id as from, t.id as to, fr.username as username, f.created_at as date')
            ->innerJoin('f.from_id', 'fr') 
            ->innerJoin('f.to_id', 't') 
            ->where('f.from_id = :f AND f.to_id = :t')
            ->orWhere('f.from_id = :t AND f.to_id = :f')
            ->orderBy('f.created_at') 
            ->setParameters($parameters);

       // var_dump($qb->getQuery()->getArrayResult());
        return $qb->getQuery()->getArrayResult();
     }

    // /**
    //  * @return Messages[] Returns an array of Messages objects
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
    public function findOneBySomeField($value): ?Messages
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
