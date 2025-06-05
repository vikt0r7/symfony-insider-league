<?php

namespace App\Service;

use App\Repository\MatchGameRepository;
use App\Repository\LeagueStateRepository;
use Doctrine\ORM\EntityManagerInterface;

class MatchSimulatorService
{
    public function __construct(
        private MatchGameRepository $matchRepository,
        private LeagueStateRepository $leagueStateRepository,
        private EntityManagerInterface $em
    ) {
    }

    public function simulateNextWeek(): void
    {
        $leagueState = $this->leagueStateRepository->find(1);
        if (!$leagueState) {
            throw new \RuntimeException('League state not found');
        }

        $currentWeek = $leagueState->getCurrentWeek();

        // 1. Получаем матчи текущей недели без результата
        $matches = $this->matchRepository->findUnplayedByWeek($currentWeek);

        foreach ($matches as $match) {
            // 2. Случайно генерируем счёт
            $homeScore = random_int(0, 5);
            $awayScore = random_int(0, 5);

            $match->setHomeScore($homeScore);
            $match->setAwayScore($awayScore);

        }

        $this->em->flush();

        // 3. Увеличиваем текущую неделю
        $leagueState->incrementWeek();
        $this->em->flush();
    }
}
