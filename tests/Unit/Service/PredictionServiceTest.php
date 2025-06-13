<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Application\Service\PredictionService;
use App\Domain\Model\Team;
use App\Infrastructure\Repository\TeamRepository;
use PHPUnit\Framework\TestCase;

class PredictionServiceTest extends TestCase
{
    private PredictionService $service;
    private TeamRepository $teamRepository;

    protected function setUp(): void
    {
        $this->teamRepository = $this->createMock(TeamRepository::class);
        $this->service = new PredictionService($this->teamRepository);
    }

    public function testCalculateProbabilitiesWithTeams(): void
    {
        $team1 = $this->createMock(Team::class);
        $team1->method('getName')->willReturn('Team A');
        $team1->method('getPoints')->willReturn(20);
        $team1->method('getGoalDifference')->willReturn(10);
        $team1->method('getRecentFormArray')->willReturn(['W', 'L', 'W']);

        $team2 = $this->createMock(Team::class);
        $team2->method('getName')->willReturn('Team B');
        $team2->method('getPoints')->willReturn(15);
        $team2->method('getGoalDifference')->willReturn(5);
        $team2->method('getRecentFormArray')->willReturn(['L', 'L', 'D']);

        $this->teamRepository
            ->method('findAll')
            ->willReturn([$team1, $team2]);

        $results = $this->service->calculateProbabilities();

        $this->assertCount(2, $results);
        $this->assertEquals('Team A', $results[0]['team']);
        $this->assertGreaterThan($results[1]['chance'], $results[0]['chance']);
    }

    public function testCalculateProbabilitiesWithNoTeams(): void
    {
        $this->teamRepository->method('findAll')->willReturn([]);

        $results = $this->service->calculateProbabilities();

        $this->assertEmpty($results);
    }

    public function testCalculateProbabilitiesWithZeroScores(): void
    {
        $team = $this->createMock(Team::class);
        $team->method('getName')->willReturn('Team A');
        $team->method('getPoints')->willReturn(0);
        $team->method('getGoalDifference')->willReturn(0);
        $team->method('getRecentFormArray')->willReturn(['L', 'L', 'L']);

        $this->teamRepository
            ->method('findAll')
            ->willReturn([$team]);

        $results = $this->service->calculateProbabilities();

        $this->assertEquals([
            ['team' => 'Team A', 'chance' => 100.0],
        ], $results);
    }
}
