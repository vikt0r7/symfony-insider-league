<?php

declare(strict_types=1);

namespace App\Application\Event;

use App\Domain\Common\DomainEvent;

class PredictionSubmittedEvent implements DomainEvent
{
    public function __construct(
        private readonly int $userId,
        private readonly int $matchId,
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
