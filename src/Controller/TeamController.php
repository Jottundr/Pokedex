<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    #[Route('/team', name: 'app_team')]
    public function index(ProfileRepository $profileRepository): Response
    {
        $profile = $profileRepository->findOneBy(['id' => $this->getUser()]);
        $pokemons = $profile->getPokemon();

        return $this->render('team/index.html.twig', ['pokemons' => $pokemons]);
    }
}
