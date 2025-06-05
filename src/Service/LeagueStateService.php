<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\LeagueState;
use App\Repository\LeagueStateRepository;
use Doctrine\ORM\EntityManagerInterface;

class LeagueStateService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LeagueStateRepository $leagueStateRepository,
    ) {
    }

    public function getState(): LeagueState
    {
        $state = $this->leagueStateRepository->find(1);
        if (!$state) {
            $state = new LeagueState();
            $this->em->persist($state);
            $this->em->flush();
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
        $this->em->flush();
    }

    public function reset(): void
    {
        $state = $this->getState();
        $state->reset();
        $this->em->flush();
    }
}
