<?php

declare(strict_types=1);

namespace App\Tests\Controller\Api;

use App\Controller\Api\LeagueController;
use App\Service\LeagueStateService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class LeagueControllerTest extends TestCase
{
    public function testGetCurrentWeek(): void
    {
        $mockService = $this->createMock(LeagueStateService::class);
        $mockService->expects($this->once())
            ->method('getCurrentWeek')
            ->willReturn(3);

        $controller = new LeagueController();
        $response = $controller->getCurrentWeek($mockService);

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertArrayHasKey('week', $data);
        $this->assertSame(3, $data['week']);
    }
}
