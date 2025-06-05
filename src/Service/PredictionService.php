<?php

namespace App\Service;

use App\Repository\TeamRepository;

class PredictionService
{
    public function __construct(private TeamRepository $teamRepository)
    {
    }

    public function calculateProbabilities(): array
    {
        $teams = $this->teamRepository->findAll();

        $scores = [];
        $totalScore = 0;

        foreach ($teams as $team) {
            $formBonus = count(array_filter($team->getRecentFormArray(), static fn($f) => $f === 'W')) * 10;
            $score = $team->getPoints() * 100 + $team->getGoalDifference() + $formBonus;
            $scores[$team->getName()] = max(1, $score);
            $totalScore += $scores[$team->getName()];
        }

        if ($totalScore === 0) {
            $teamCount = count($teams);
            if ($teamCount === 0) {
                return [];
            }

            $equalChance = round(100 / $teamCount, 2);
            return array_map(fn($team) => [
                'team' => $team->getName(),
                'chance' => $equalChance
            ], $teams);
        }

        $probabilities = [];
        foreach ($scores as $teamName => $score) {
            $probabilities[] = [
                'team' => $teamName,
                'chance' => round($score / $totalScore * 100, 2)
            ];
        }

        usort($probabilities, static fn($a, $b) => $b['chance'] <=> $a['chance']);

        return $probabilities;
    }
}
