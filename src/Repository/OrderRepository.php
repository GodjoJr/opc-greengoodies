<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function countOrdersToday(): int
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where('o.createdAt >= :today')
            ->setParameter('today', new \DateTimeImmutable('today'))
            ->getQuery()
            ->getSingleScalarResult();
    }
}
