<?php

namespace App\Controller\Api;

use App\Entity\MatchGame;
use App\Entity\Team;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/standings')]
class StandingsController extends AbstractController
{
    #[Route('', name: 'api_standings', methods: ['GET'])]
    public function standings(EntityManagerInterface $em): JsonResponse
    {
        $teams = $em->getRepository(Team::class)->findAll();
        $matches = $em->getRepository(MatchGame::class)->findAll();

        $table = [];

        // 1. Гарантируем уникальность: инициализируем только по ID
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

        // 2. Обрабатываем сыгранные матчи
        foreach ($matches as $match) {
            if ($match->getHomeScore() === null || $match->getAwayScore() === null) {
                continue;
            }

            $home = $match->getHomeTeam();
            $away = $match->getAwayTeam();

            $homeId = $home->getId();
            $awayId = $away->getId();

            $homeGoals = $match->getHomeScore();
            $awayGoals = $match->getAwayScore();

            $table[$homeId]['played']++;
            $table[$awayId]['played']++;

            $table[$homeId]['goalsFor'] += $homeGoals;
            $table[$homeId]['goalsAgainst'] += $awayGoals;
            $table[$awayId]['goalsFor'] += $awayGoals;
            $table[$awayId]['goalsAgainst'] += $homeGoals;

            if ($homeGoals > $awayGoals) {
                $table[$homeId]['won']++;
                $table[$homeId]['points'] += 3;
                $table[$homeId]['form'][] = 'W';

                $table[$awayId]['lost']++;
                $table[$awayId]['form'][] = 'L';
            } elseif ($homeGoals < $awayGoals) {
                $table[$awayId]['won']++;
                $table[$awayId]['points'] += 3;
                $table[$awayId]['form'][] = 'W';

                $table[$homeId]['lost']++;
                $table[$homeId]['form'][] = 'L';
            } else {
                $table[$homeId]['drawn']++;
                $table[$awayId]['drawn']++;
                $table[$homeId]['points']++;
                $table[$awayId]['points']++;
                $table[$homeId]['form'][] = 'D';
                $table[$awayId]['form'][] = 'D';
            }
        }

        // 3. Итоги
        foreach ($table as &$row) {
            $row['goalDifference'] = $row['goalsFor'] - $row['goalsAgainst'];
            $row['form'] = array_slice(array_reverse($row['form']), 0, 5);
        }
        unset($row); // good practice

        // 4. Сортировка
        uasort($table, fn($a, $b) => $b['points'] <=> $a['points']
            ?: $b['goalDifference'] <=> $a['goalDifference']
                ?: strcmp($a['club'], $b['club'])
        );

        // 5. Финальное добавление позиции
        $response = [];
        $position = 1;
        foreach ($table as $row) {
            $row['position'] = $position++;
            $response[] = $row;
        }

        return $this->json($response);
    }
}

