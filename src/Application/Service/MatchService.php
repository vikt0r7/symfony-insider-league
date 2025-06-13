<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Model\MatchGame;
use App\Infrastructure\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class MatchService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TeamRepository $teamRepository,
    ) {
    }

    public function resetMatchResult(MatchGame $match, int $homeScore, int $awayScore): void
    {
        $teams = $this->teamRepository->findAll();

        foreach ($teams as $team) {
            $team->resetStats();
        }

        $match->setHomeScore($homeScore);
        $match->setAwayScore($awayScore);

        $this->entityManager->flush();
    }
}
