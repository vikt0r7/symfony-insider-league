<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Shared\Attribute\RequestDto;

#[RequestDto]
class SimulateWeekCommand
{
    public function __construct(
        public int $week = 0,
    ) {
    }
}
