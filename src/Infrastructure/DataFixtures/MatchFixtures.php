<?php

declare(strict_types=1);

namespace App\Infrastructure\DataFixtures;

use App\Domain\Model\MatchGame;
use App\Domain\Model\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use RuntimeException;

class MatchFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['initial'];
    }

    public function getDependencies(): array
    {
        return [
            TeamFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $teams = $manager->getRepository(Team::class)->findAll();

        if (count($teams) < 2) {
            throw new RuntimeException('Not enough teams for fixtures');
        }

        $existingMatches = $manager->getRepository(MatchGame::class)->findAll();
        if (count($existingMatches) > 0) {
            return;
        }

        $pairs = [];
        for ($i = 0, $iMax = count($teams); $i < $iMax; ++$i) {
            for ($j = $i + 1; $j < $iMax; ++$j) {
                $pairs[] = [$teams[$i], $teams[$j]];
            }
        }

        $maxWeeks = 6;
        $week = 1;

        foreach ($pairs as [$teamA, $teamB]) {
            $match = new MatchGame($teamA, $teamB);
            $match->setWeek($week);
            $match->setHomeScore(null);
            $match->setAwayScore(null);
            $manager->persist($match);

            $week++;
            if ($week > $maxWeeks) {
                $week = 1;
            }
        }

        $manager->flush();
    }
}
