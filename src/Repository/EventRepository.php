<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Event;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findLastEvents(int $limit): array 
    {
        return $this->createQueryBuilder('e')
            ->where('e.date >= :date')
            ->setParameter('date', new \DateTime())
            ->orderBy('e.date', 'DESC')
            ->setMaxResults($limit)

            ->getQuery()

            ->getResult() ;
    }

//pagination for all events
    public function paginationQuery()
    {
         return $this->createQueryBuilder('e')
           ->orderBy('e.id', 'ASC')
            ->getQuery();
        
     }


     //pagination 
    public function paginationQueryByUser($user)
    {
        return $this->createQueryBuilder('e')
            ->where('e.createdBy = :user')
            ->setParameter('user', $user)
            ->orderBy('e.id', 'ASC')
            ->getQuery();
    }



     // fonction pour afficher les events créé par un user

     public function findEventsCreatedByUser(User $user)
     {
         return $this->createQueryBuilder('e')
             ->andWhere('e.createdBy = :user')
             ->setParameter('user', $user)
             ->getQuery()
             ->getResult();
     }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}