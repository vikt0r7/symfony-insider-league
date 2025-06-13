<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Model\LeagueState;
use App\Domain\Model\MatchGame;
use App\Infrastructure\Repository\LeagueStateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class LeagueStateService
{
    public const WEEK_PREDICTIONS_AVAILABLE = 4;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LeagueStateRepository $leagueStateRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function getState(): LeagueState
    {
        /** @var MatchGame $state */
        $state = $this->leagueStateRepository->findOneBy([]);
        if (!$state) {
            $this->logger->info('LeagueState not found, creating new one.');
            $state = new LeagueState();
            $this->entityManager->persist($state);
            $this->entityManager->flush();
            $this->logger->info('New LeagueState created with ID ' . $state->getId());
        }

        return $state;
    }

    public function getCurrentWeek(): int
    {
        return $this->getState()->getCurrentWeek();
    }

    public function increaseCurrentWeek(): void
    {
        $state = $this->getState();
        $state->incrementWeek();
        $this->entityManager->flush();
    }

    public function reset(): void
    {
        $state = $this->getState();
        $state->reset();
        $this->entityManager->flush();
    }

    public function arePredictionsAvailable(): bool
    {
        return $this->getCurrentWeek() > self::WEEK_PREDICTIONS_AVAILABLE;
    }
}
