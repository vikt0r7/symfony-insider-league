<?php

namespace App\Application\Handler;

use App\Application\Command\ResetLeagueCommand;
use App\Repository\MatchGameRepository;
use App\Repository\TeamRepository;
use App\Service\LeagueStateService;
use Doctrine\ORM\EntityManagerInterface;

class ResetLeagueHandler
{
    public function __construct(
        private readonly TeamRepository $teamRepository,
        private readonly MatchGameRepository $matchRepository,
        private readonly EntityManagerInterface $em,
        private readonly LeagueStateService $leagueStateService,
    ) {
    }

    public function handle(ResetLeagueCommand $command): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\MatchGame')->execute();

        foreach ($this->teamRepository->findAll() as $team) {
            $team->resetStats();
            $this->em->persist($team);
        }

        $this->leagueStateService->reset();

        $this->em->flush();
    }
}
