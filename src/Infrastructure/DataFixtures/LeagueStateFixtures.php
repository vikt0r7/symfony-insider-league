<?php

declare(strict_types=1);

namespace App\Infrastructure\DataFixtures;

use App\Domain\Model\LeagueState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class LeagueStateFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['initial'];
    }

    public function load(ObjectManager $manager): void
    {
        $leagueState = $manager->getRepository(LeagueState::class)->findOneBy([]);
        if ($leagueState) {
            return;
        }

        $leagueState = new LeagueState();
        $leagueState->reset();

        $manager->persist($leagueState);
        $manager->flush();
    }
}
