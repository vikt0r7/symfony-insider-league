<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MatchGame;
use App\Repository\MatchGameRepository;
use App\Repository\TeamRepository;

class SimulationService
{
    public function __construct(
        private readonly MatchGameRepository $matchRepository,
        private readonly TeamRepository $teamRepository,
    ) {
    }

    public function simulateWeek(int $week): array
    {
        $matches = $this->matchRepository->findUnplayedByWeek($week);
        foreach ($matches as $match) {
            [$homeGoals, $awayGoals] = $this->simulate();

            $match->setHomeScore($homeGoals);
            $match->setAwayScore($awayGoals);

            $this->applyResult($match);
        }

        return $matches;
    }

    public function simulate(): array
    {
        return [random_int(0, 5), random_int(0, 5)];
    }

    public function applyResult(MatchGame $match): void
    {
        $home = $match->getHomeTeam();
        $away = $match->getAwayTeam();

        $homeGoals = $match->getHomeScore();
        $awayGoals = $match->getAwayScore();

        $home->addPlayed();
        $away->addPlayed();

        $home->addGoalsFor($homeGoals);
        $home->addGoalsAgainst($awayGoals);
        $away->addGoalsFor($awayGoals);
        $away->addGoalsAgainst($homeGoals);

        if ($homeGoals > $awayGoals) {
            $home->addWon();
            $away->addLost();
        } elseif ($homeGoals < $awayGoals) {
            $away->addWon();
            $home->addLost();
        } else {
            $home->addDrawn();
            $away->addDrawn();
        }
    }
}
