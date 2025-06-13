<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class PredictionsNotAvailableException extends \RuntimeException
{
    public static function forWeek(int $week, int $availableAfterWeek): self
    {
        return new self(
            sprintf(
                'Predictions are available only after week %d (current week: %d)',
                $availableAfterWeek,
                $week
            )
        );
    }
}
