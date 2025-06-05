<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Service\PredictionService;
use PHPUnit\Framework\TestCase;

class PredictionServiceTest extends TestCase
{
    public function testCalculateProbabilitiesWithData(): void
    {
        $team1 = $this->createMock(Team::class);
        $team1->method('getName')->willReturn('Chelsea');
        $team1->method('getPoints')->willReturn(10);
        $team1->method('getGoalDifference')->willReturn(5);
        $team1->method('getRecentFormArray')->willReturn(['W', 'W', 'L']);

        $team2 = $this->createMock(Team::class);
        $team2->method('getName')->willReturn('Arsenal');
        $team2->method('getPoints')->willReturn(5);
        $team2->method('getGoalDifference')->willReturn(-2);
        $team2->method('getRecentFormArray')->willReturn(['D', 'L', 'W']);

        $repo = $this->createMock(TeamRepository::class);
        $repo->method('findAll')->willReturn([$team1, $team2]);

        $service = new PredictionService($repo);
        $probabilities = $service->calculateProbabilities();

        $this->assertCount(2, $probabilities);
        $this->assertSame('Chelsea', $probabilities[0]['team']);
        $this->assertArrayHasKey('chance', $probabilities[0]);
        $this->assertGreaterThan($probabilities[1]['chance'], $probabilities[0]['chance']);
    }

    public function testCalculateProbabilitiesWithNoTeams(): void
    {
        $repo = $this->createMock(TeamRepository::class);
        $repo->method('findAll')->willReturn([]);

        $service = new PredictionService($repo);
        $result = $service->calculateProbabilities();

        $this->assertSame([], $result);
    }
}
