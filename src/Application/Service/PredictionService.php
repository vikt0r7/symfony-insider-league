<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Exception\PredictionsNotAvailableException;
use App\Infrastructure\Repository\TeamRepository;

class PredictionService
{
    public function __construct(private readonly TeamRepository $teamRepository)
    {
    }

    public function calculateProbabilities(): array
    {
        $teams = $this->teamRepository->findAll();

        $scores = [];
        $totalScore = 0;

        foreach ($teams as $team) {
            $formBonus = count(array_filter($team->getRecentFormArray(), static fn ($f) => 'W' === $f)) * 10;
            $score = $team->getPoints() * 100 + $team->getGoalDifference() + $formBonus;
            $scores[$team->getName()] = max(1, $score);
            $totalScore += $scores[$team->getName()];
        }

        if (0 === $totalScore) {
            $teamCount = count($teams);
            if (0 === $teamCount) {
                return [];
            }

            $equalChance = round(100 / $teamCount, 2);

            return array_map(static fn ($team) => [
                'team' => $team->getName(),
                'chance' => $equalChance,
            ], $teams);
        }

        $probabilities = [];
        foreach ($scores as $teamName => $score) {
            $probabilities[] = [
                'team' => $teamName,
                'chance' => round($score / $totalScore * 100, 2),
            ];
        }

        usort($probabilities, static fn ($a, $b) => $b['chance'] <=> $a['chance']);

        return $probabilities;
    }

    public function getPredictionsIfAvailable(LeagueStateService $leagueStateService): array
    {
        $currentWeek = $leagueStateService->getCurrentWeek();

        if (!$leagueStateService->arePredictionsAvailable()) {
            throw PredictionsNotAvailableException::forWeek($currentWeek, LeagueStateService::WEEK_PREDICTIONS_AVAILABLE);
        }

        return $this->calculateProbabilities();
    }
}
