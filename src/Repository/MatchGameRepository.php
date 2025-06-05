<?php

namespace App\Repository;

use App\Entity\MatchGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MatchGame>
 */
class MatchGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchGame::class);
    }

    public function findUnplayedByWeek(int $week): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.week = :week')
            ->andWhere('m.homeScore IS NULL AND m.awayScore IS NULL')
            ->setParameter('week', $week)
            ->getQuery()
            ->getResult();
    }

    public function findByWeek(int $week): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.week = :week')
            ->setParameter('week', $week)
            ->getQuery()
            ->getResult();
    }

    public function hasUnplayedMatches(): bool
    {
        return null !== $this->createQueryBuilder('m')
                ->select('1')
                ->where('m.homeScore IS NULL AND m.awayScore IS NULL')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
    }

    public function removeAll(): void
    {
        $this->createQueryBuilder('m')->delete()->getQuery()->execute();
    }
}
