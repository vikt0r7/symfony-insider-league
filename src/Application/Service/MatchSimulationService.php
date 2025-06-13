<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Model\LeagueState;
use App\Domain\Model\MatchGame;
use App\Infrastructure\Repository\LeagueStateRepository;
use App\Infrastructure\Repository\MatchGameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use LogicException;
use Psr\Log\LoggerInterface;

class MatchSimulationService
{
    public function __construct(
        private readonly MatchGameRepository $matchRepository,
        private readonly LeagueStateRepository $leagueStateRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly int $maxWeeks = 6,
    ) {
    }

    public function simulateNextWeek(): void
    {
        $this->logger->info('Attempting to simulate next week...');

        $leagueState = $this->leagueStateRepository->findOneBy([]);
        if (!$leagueState) {
            $this->logger->error('League state not found.');
            throw new LogicException('League state not found. Please schedule matches first.');
        }

        $week = $leagueState->getCurrentWeek();
        $this->logger->info("Current week: $week");

        $matches = $this->matchRepository->findUnplayedByWeek($week);
        $this->logger->info("findUnplayedByWeek($week) returned " . count($matches) . " matches");

        while (count($matches) === 0) {
            $nextWeek = $this->matchRepository->findNextWeekWithUnplayedMatches($week);
            if ($nextWeek === null) {
                $this->logger->info("No more weeks with unplayed matches. Season finished.");
                return;
            }
            $week = $nextWeek;
            $leagueState->setCurrentWeek($week);

            $matches = $this->matchRepository->findUnplayedByWeek($week);
        }

        foreach ($matches as $match) {
            [$homeGoals, $awayGoals] = $this->generateScore();
            $match->setHomeScore($homeGoals);
            $match->setAwayScore($awayGoals);
            $this->applyResult($match);
            $this->logger->info(
                sprintf(
                    "Match id %d: homeScore=%s, awayScore=%s",
                    $match->getId(),
                    $match->getHomeScore(),
                    $match->getAwayScore()
                )
            );
        }

        $nextWeek = $this->matchRepository->findNextWeekWithUnplayedMatches($week);
        if ($nextWeek !== null) {
            $leagueState->setCurrentWeek($nextWeek);
            $this->logger->info("Week set to next unplayed week: $nextWeek");
        } else {
            $this->logger->info("No more unplayed weeks after current. Season finished.");
        }

        $this->entityManager->flush();
    }


    public function simulateAllWeeks(): void
    {
        while (true) {
            try {
                $this->simulateNextWeek();
            } catch (Exception $e) {
                break;
            }
        }
    }

    private function generateScore(): array
    {
        return [random_int(0, 5), random_int(0, 5)];
    }

    private function applyResult(MatchGame $match): void
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

        $this->entityManager->persist($home);
        $this->entityManager->persist($away);
    }
}
