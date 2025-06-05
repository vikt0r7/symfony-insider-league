<?php

declare(strict_types=1);

namespace App\Tests\Controller\Api;

use App\Controller\Api\MatchController;
use App\Entity\MatchGame;
use App\Entity\Team;
use App\Repository\MatchGameRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MatchControllerTest extends WebTestCase
{
    public function testIndexReturnsJson(): void
    {
        $teamA = (new Team())->setName('Chelsea');
        $reflection = new \ReflectionProperty(Team::class, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($teamA, 1);

        $teamB = (new Team())->setName('Arsenal');
        $reflection->setValue($teamB, 2);

        $match = new MatchGame($teamA, $teamB);
        $match->setHomeScore(2);
        $match->setAwayScore(1);
        $match->setWeek(1);

        $matchReflection = new \ReflectionProperty(MatchGame::class, 'id');
        $matchReflection->setAccessible(true);
        $matchReflection->setValue($match, 10);

        $matchRepo = $this->createMock(MatchGameRepository::class);
        $matchRepo->method('findAll')->willReturn([$match]);

        $controller = new MatchController();

        $response = $controller->index($matchRepo);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertCount(1, $data);
        $this->assertSame(2, $data[0]['homeScore']);
        $this->assertSame(1, $data[0]['awayScore']);
        $this->assertSame('Chelsea', $data[0]['homeTeam']['name']);
        $this->assertSame('Arsenal', $data[0]['awayTeam']['name']);
    }
}
