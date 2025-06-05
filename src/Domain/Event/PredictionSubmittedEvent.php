<?php

namespace App\Domain\Event;

use App\Domain\Common\DomainEvent;

class PredictionSubmittedEvent implements DomainEvent
{
    public function __construct(
        private int $userId,
        private int $matchId
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getMatchId(): int
    {
        return $this->matchId;
    }
}
