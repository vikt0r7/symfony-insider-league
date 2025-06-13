<?php

declare(strict_types=1);

namespace App\Application\CommandHandler;

use App\Application\Command\ResetLeagueCommand;
use App\Application\Service\LeagueStateService;
use App\Infrastructure\Repository\MatchGameRepository;
use App\Infrastructure\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ResetLeagueHandler
{
    public function __construct(
        private readonly TeamRepository $teamRepository,
        private readonly MatchGameRepository $matchRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LeagueStateService $leagueStateService,
    ) {
    }

    public function __invoke(ResetLeagueCommand $command): void
    {
        $this->matchRepository->deleteAll();

        foreach ($this->teamRepository->findAll() as $team) {
            $team->resetStats();
            $this->entityManager->persist($team);
        }

        $this->leagueStateService->reset();

        $this->entityManager->flush();
    }
}
