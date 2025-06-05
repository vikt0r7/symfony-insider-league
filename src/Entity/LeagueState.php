<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class LeagueState
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $currentWeek = 1;

    public function getCurrentWeek(): int
    {
        return $this->currentWeek;
    }

    public function incrementWeek(): void
    {
        ++$this->currentWeek;
    }

    public function reset(): void
    {
        $this->currentWeek = 1;
    }
}
