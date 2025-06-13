<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Model\LeagueState;
use App\Domain\Model\MatchGame;
use App\Infrastructure\Repository\LeagueStateRepository;
use App\Infrastructure\Repository\MatchGameRepository;
use App\Infrastructure\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;

class MatchSchedulerService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MatchGameRepository $matchRepository,
        private readonly LeagueStateRepository $leagueStateRepository,
        private readonly TeamRepository $teamRepository,
    ) {
    }

    public function scheduleAllMatches(): void
    {
        $this->matchRepository->deleteAll();

        $teams = $this->teamRepository->findAll();
        $pairs = [];

        for ($i = 0, $iMax = count($teams); $i < $iMax; ++$i) {
            for ($j = $i + 1; $j < $iMax; ++$j) {
                $pairs[] = [$teams[$i], $teams[$j]];
            }
        }

        $maxWeeks = 6;
        $week = 1;

        foreach ($pairs as [$teamA, $teamB]) {
            $match = new MatchGame($teamA, $teamB);
            $match->setWeek($week);
            $this->entityManager->persist($match);

            $week++;
            if ($week > $maxWeeks) {
                $week = 1;
            }
        }

        $this->entityManager->flush();

        $leagueState = $this->leagueStateRepository->findOneBy([]);
        if (!$leagueState) {
            $leagueState = new LeagueState();
            $this->entityManager->persist($leagueState);
        }
        $leagueState->reset();
        $this->entityManager->flush();
    }
}
