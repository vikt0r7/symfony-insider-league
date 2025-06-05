<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MatchGame;
use App\Entity\Team;
use App\Repository\LeagueStateRepository;
use App\Repository\MatchGameRepository;
use Doctrine\ORM\EntityManagerInterface;

class MatchSchedulerService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MatchGameRepository $matchRepository,
        private readonly LeagueStateRepository $leagueStateRepository,
    ) {
    }

    public function scheduleAllMatches(): void
    {
        $this->matchRepository->removeAll();

        /** @var Team[] $teams */
        $teams = $this->em->getRepository(Team::class)->findAll();

        $pairs = [];

        for ($i = 0, $iMax = count($teams); $i < $iMax; ++$i) {
            for ($j = $i + 1; $j < $iMax; ++$j) {
                $teamA = $teams[$i];
                $teamB = $teams[$j];

                $pairKey = $teamA->getId() < $teamB->getId()
                    ? $teamA->getId() . '-' . $teamB->getId()
                    : $teamB->getId() . '-' . $teamA->getId();

                if (!isset($pairs[$pairKey])) {
                    $pairs[$pairKey] = [$teamA, $teamB];
                }
            }
        }

        $week = 1;
        foreach ($pairs as [$teamA, $teamB]) {
            $match = new MatchGame($teamA, $teamB);
            $match->setWeek($week++);
            $this->em->persist($match);
        }

        $this->em->flush();

        $leagueState = $this->leagueStateRepository->find(1);
        if (!$leagueState) {
            $leagueState = new \App\Entity\LeagueState();
            $this->em->persist($leagueState);
        }
        $leagueState->reset();
        $this->em->flush();
    }
}
