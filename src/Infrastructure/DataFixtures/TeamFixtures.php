<?php

declare(strict_types=1);

namespace App\Infrastructure\DataFixtures;

use App\Domain\Model\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['initial'];
    }

    public function load(ObjectManager $manager): void
    {
        $teams = ['Chelsea', 'Arsenal', 'Liverpool', 'Manchester United'];

        foreach ($teams as $teamName) {
            $team = $manager->getRepository(Team::class)->findOneBy(['name' => $teamName]);
            if ($team) {
                continue;
            }

            $team = new Team();
            $team->setName($teamName);
            $manager->persist($team);
        }

        $manager->flush();
    }
}
