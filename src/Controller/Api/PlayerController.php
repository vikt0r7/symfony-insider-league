<?php

namespace App\Controller\Api;

use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    #[Route('/players', name: 'player_index')]
    public function index(PlayerRepository $repo): Response
    {
        $players = $repo->findAll();

        return $this->render('player/index.html.twig', [
            'players' => $players,
        ]);
    }
}
