<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Api;

use App\Domain\Model\LeagueState;
use App\Infrastructure\DataFixtures\LeagueStateFixtures;
use App\Infrastructure\DataFixtures\MatchFixtures;
use App\Infrastructure\DataFixtures\PremierLeagueFixtures;
use App\Infrastructure\DataFixtures\TeamFixtures;
use App\Infrastructure\Repository\LeagueStateRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class MatchControllerTest extends WebTestCase
{
    public function testIndexReturnsJson(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $databaseToolCollection = $container->get(DatabaseToolCollection::class);
        $databaseTool = $databaseToolCollection->get();
        $databaseTool->loadFixtures([
            TeamFixtures::class,
            PremierLeagueFixtures::class,
            LeagueStateFixtures::class,
            MatchFixtures::class,
        ]);

        $leagueStateRepository = $container->get(LeagueStateRepository::class);

        /** @var LeagueState $leagueState */
        $leagueState = $leagueStateRepository->find(1);
        $leagueState->reset();
        $container->get('doctrine')->getManager()->flush();

        $client->request('GET', '/api/matches');

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        $match = $data[0];
        $this->assertArrayHasKey('scoreA', $match);
        $this->assertArrayHasKey('scoreB', $match);
        $this->assertArrayHasKey('teamA', $match);
        $this->assertArrayHasKey('teamB', $match);
    }
}
