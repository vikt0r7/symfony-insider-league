<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application;

use App\Application\Service\MatchSimulationService;
use App\Application\Service\LeagueStateService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use App\Infrastructure\DataFixtures\TeamFixtures;
use App\Infrastructure\DataFixtures\PremierLeagueFixtures;
use App\Infrastructure\DataFixtures\LeagueStateFixtures;
use App\Infrastructure\DataFixtures\MatchFixtures;

class SimulateSeasonTest extends KernelTestCase
{
    private MatchSimulationService $simulationService;
    private LeagueStateService $leagueStateService;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $databaseTool = $container
            ->get(DatabaseToolCollection::class)
            ->get();

        $databaseTool->loadFixtures([
            TeamFixtures::class,
            PremierLeagueFixtures::class,
            LeagueStateFixtures::class,
            MatchFixtures::class,
        ]);

        $this->simulationService = $container->get(MatchSimulationService::class);
        $this->leagueStateService = $container->get(LeagueStateService::class);
    }

    public function testFullSeasonSimulation(): void
    {
        for ($i = 0; $i < 4; ++$i) {
            $this->simulationService->simulateNextWeek();
        }

        $currentWeek = $this->leagueStateService->getCurrentWeek();
        $this->assertSame(5, $currentWeek);
    }
}
