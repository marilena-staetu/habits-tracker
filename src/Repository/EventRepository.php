<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    public function getEventByHabitAndDate($habitId, $date)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.date = :date')
            ->andWhere('c.habit = :habit')
            ->setParameter(':date', $date)
            ->setParameter(':habit', $habitId)
            ->getQuery()
            ->disableResultCache()
            ->getOneOrNullResult()
            ;
    }

    public function getEventById($id)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :val')
            ->setParameter(':val', $id)
            ->getQuery()
            ->disableResultCache()
            ->getOneOrNullResult()
            ;
    }

    public function countEventsOfAHabit($habitId)
    {
        $queryResult = $this->createQueryBuilder('c')
            ->andWhere('c.habit = :habit')
            ->setParameter(':habit', $habitId)
            ->select('COUNT(c.habit) as event_count')
            ->getQuery()
            ->disableResultCache()
            ->getOneOrNullResult();

        return $queryResult['event_count'];
    }
}
