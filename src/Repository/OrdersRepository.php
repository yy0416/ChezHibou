<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    /**
     * @return Orders[] Returns an array of Orders objects
     */
    public function findByUserOrderedByDateDesc($user)
    {
        return $this->createBaseQueryBuilder()
            ->andWhere('o.users = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Orders[] Returns an array of Orders objects filtered by date range
     */
    public function findByDateRange(?\DateTime $startDate, ?\DateTime $endDate)
    {
        $qb = $this->createBaseQueryBuilder();

        if ($startDate) {
            $qb->andWhere('o.created_at >= :startDate')
                ->setParameter('startDate', $startDate->setTime(0, 0, 0));
        }

        if ($endDate) {
            $qb->andWhere('o.created_at <= :endDate')->setParameter('endDate', $endDate->setTime(23, 59, 59));
        }

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * Base QueryBuilder for Orders queries, with default ordering by created_at DESC
     */
    private function createBaseQueryBuilder()
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.created_at', 'DESC');
    }
}
