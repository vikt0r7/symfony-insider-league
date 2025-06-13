<?php

declare(strict_types=1);

namespace App\Application\QueryHandler;

use App\Application\Query\GetCurrentWeekQuery;
use App\Application\Service\LeagueStateService;

class GetCurrentWeekHandler
{
    public function __construct(private LeagueStateService $leagueStateService)
    {
    }

    public function __invoke(GetCurrentWeekQuery $query): int
    {
        return $this->leagueStateService->getCurrentWeek();
    }
}
