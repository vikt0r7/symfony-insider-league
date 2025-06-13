<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Api;

use App\Infrastructure\DataFixtures\PremierLeagueFixtures;
use App\Infrastructure\DataFixtures\TeamFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class LeagueControllerTest extends WebTestCase
{
    public function testGetCurrentWeek(): void
    {
        $client = static::createClient();

        $container = static::getContainer();

        /** @var DatabaseToolCollection $databaseToolCollection */
        $databaseToolCollection = $container->get(DatabaseToolCollection::class);
        $databaseTool = $databaseToolCollection->get();

        $databaseTool->loadFixtures([
            TeamFixtures::class,
            PremierLeagueFixtures::class,
        ]);

        $client->request('GET', '/api/league/week');

        $this->assertResponseIsSuccessful();
    }
}
