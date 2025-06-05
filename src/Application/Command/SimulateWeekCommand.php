<?php

declare(strict_types=1);

namespace App\Application\Command;

class SimulateWeekCommand
{
    public function __construct(public int $week)
    {
    }
}
