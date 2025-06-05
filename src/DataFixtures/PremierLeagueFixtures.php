<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PremierLeagueFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $teamNames = ['Chelsea', 'Arsenal', 'Manchester United', 'Liverpool'];

        foreach ($teamNames as $name) {
            $existing = $manager->getRepository(Team::class)->findOneBy(['name' => $name]);
            if ($existing) {
                continue;
            }

            $team = new Team();
            $team->setName($name);

            for ($i = 1; $i <= 4; ++$i) {
                $player = new Player();
                $player->setName("Player {$i} {$name}");
                $player->setTeam($team);
                $player->setScore(random_int(0, 10));
                $player->setCreatedAt(new \DateTimeImmutable());
                $manager->persist($player);
            }

            $manager->persist($team);
        }

        $manager->flush();
    }
}
