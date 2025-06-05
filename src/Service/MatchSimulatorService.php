<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\LeagueStateRepository;
use App\Repository\MatchGameRepository;
use Doctrine\ORM\EntityManagerInterface;

class MatchSimulatorService
{
    public function __construct(
        private readonly MatchGameRepository $matchRepository,
        private readonly LeagueStateRepository $leagueStateRepository,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function simulateNextWeek(): void
    {
        $leagueState = $this->leagueStateRepository->find(1);
        if (!$leagueState) {
            throw new \RuntimeException('League state not found');
        }

        $currentWeek = $leagueState->getCurrentWeek();

        $matches = $this->matchRepository->findUnplayedByWeek($currentWeek);

        foreach ($matches as $match) {
            $homeScore = random_int(0, 5);
            $awayScore = random_int(0, 5);

            $match->setHomeScore($homeScore);
            $match->setAwayScore($awayScore);
        }

        $this->em->flush();

        $leagueState->incrementWeek();
        $this->em->flush();
    }
}
