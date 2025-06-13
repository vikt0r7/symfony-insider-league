<?php

declare(strict_types=1);

namespace App\Application\QueryHandler;

use App\Application\Query\GetStandingsQuery;
use App\Infrastructure\Repository\MatchGameRepository;
use App\Infrastructure\Repository\TeamRepository;

class GetStandingsHandler
{
    public function __construct(
        private TeamRepository $teamRepository,
        private MatchGameRepository $matchGameRepository,
    ) {
    }

    public function __invoke(GetStandingsQuery $query): array
    {
        $teams = $this->teamRepository->findAll();
        $matches = $this->matchGameRepository->findAll();

        $table = [];

        foreach ($teams as $team) {
            $id = $team->getId();
            if (!isset($table[$id])) {
                $table[$id] = [
                    'id' => $id,
                    'club' => $team->getName(),
                    'played' => 0,
                    'won' => 0,
                    'drawn' => 0,
                    'lost' => 0,
                    'goalsFor' => 0,
                    'goalsAgainst' => 0,
                    'goalDifference' => 0,
                    'points' => 0,
                    'form' => [],
                ];
            }
        }

        foreach ($matches as $match) {
            if (null === $match->getHomeScore() || null === $match->getAwayScore()) {
                continue;
            }

            $home = $match->getHomeTeam();
            $away = $match->getAwayTeam();

            $homeId = $home->getId();
            $awayId = $away->getId();

            $homeGoals = $match->getHomeScore();
            $awayGoals = $match->getAwayScore();

            ++$table[$homeId]['played'];
            ++$table[$awayId]['played'];

            $table[$homeId]['goalsFor'] += $homeGoals;
            $table[$homeId]['goalsAgainst'] += $awayGoals;
            $table[$awayId]['goalsFor'] += $awayGoals;
            $table[$awayId]['goalsAgainst'] += $homeGoals;

            if ($homeGoals > $awayGoals) {
                ++$table[$homeId]['won'];
                $table[$homeId]['points'] += 3;
                $table[$homeId]['form'][] = 'W';

                ++$table[$awayId]['lost'];
                $table[$awayId]['form'][] = 'L';
            } elseif ($homeGoals < $awayGoals) {
                ++$table[$awayId]['won'];
                $table[$awayId]['points'] += 3;
                $table[$awayId]['form'][] = 'W';

                ++$table[$homeId]['lost'];
                $table[$homeId]['form'][] = 'L';
            } else {
                ++$table[$homeId]['drawn'];
                ++$table[$awayId]['drawn'];
                ++$table[$homeId]['points'];
                ++$table[$awayId]['points'];
                $table[$homeId]['form'][] = 'D';
                $table[$awayId]['form'][] = 'D';
            }
        }

        foreach ($table as &$row) {
            $row['goalDifference'] = $row['goalsFor'] - $row['goalsAgainst'];
            $row['form'] = array_slice(array_reverse($row['form']), 0, 5);
        }
        unset($row);

        uasort(
            $table,
            static fn ($a, $b) => $b['points'] <=> $a['points']
                ?: $b['goalDifference'] <=> $a['goalDifference']
                    ?: strcmp($a['club'], $b['club'])
        );

        $response = [];
        $position = 1;
        foreach ($table as $row) {
            $row['position'] = $position++;
            $response[] = $row;
        }

        return $response;
    }
}
