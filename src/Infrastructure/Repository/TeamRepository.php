<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Model\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function resetAllStats(): void
    {
        $teams = $this->findAll();
        foreach ($teams as $team) {
            $team->resetStats();
        }
    }

    public function getStandings(): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.homeMatches', 'hm')
            ->leftJoin('t.awayMatches', 'am')
            ->addSelect('hm', 'am')
            ->orderBy('t.points', 'DESC')
            ->addOrderBy('t.goalsFor', 'DESC')
            ->addOrderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
