<?php

declare(strict_types=1);

namespace App\Application\QueryHandler;

use App\Application\Query\GetPredictionsQuery;
use App\Application\Service\LeagueStateService;
use App\Application\Service\PredictionService;
use App\Domain\Exception\PredictionsNotAvailableException;

class GetPredictionsHandler
{
    public function __construct(
        private LeagueStateService $leagueStateService,
        private PredictionService $predictionService,
    ) {
    }

    public function __invoke(GetPredictionsQuery $query): array
    {
        if (!$this->leagueStateService->arePredictionsAvailable()) {
            throw PredictionsNotAvailableException::forWeek($this->leagueStateService->getCurrentWeek(), $this->leagueStateService::WEEK_PREDICTIONS_AVAILABLE);
        }

        return $this->predictionService->calculateProbabilities();
    }
}
