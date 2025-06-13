<?php

declare(strict_types=1);

namespace App\Infrastructure\DataFixtures;

use App\Domain\Model\Player;
use App\Domain\Model\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use RuntimeException;

class PremierLeagueFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $teamNames = ['Chelsea', 'Arsenal', 'Manchester United', 'Liverpool'];

        foreach ($teamNames as $name) {
            $team = $manager->getRepository(Team::class)->findOneBy(['name' => $name]);
            if (!$team) {
                throw new RuntimeException("Team '{$name}' not found. Load TeamFixtures first.");
            }

            $existingPlayers = $manager->getRepository(Player::class)->findBy(['team' => $team]);
            if (count($existingPlayers) > 0) {
                continue;
            }

            for ($i = 1; $i <= 4; ++$i) {
                $player = new Player();
                $player->setName("Player {$i} {$name}");
                $player->setTeam($team);
                $player->setScore(random_int(0, 10));
                $player->setCreatedAt(new \DateTimeImmutable());
                $manager->persist($player);
            }
        }

        $manager->flush();
    }
}
