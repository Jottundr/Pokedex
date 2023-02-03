<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokemonController extends AbstractController
{
    #[Route('/pokemon', name: 'app_pokemon')]
    public function allPokemons(HttpClientInterface $client): Response
    {
        $pokemons = [];
        for ($id = 1; $id <= 151; $id++) {
            $response = $client->request('GET', 'https://pokeapi.co/api/v2/pokemon/' . $id);
            $content = $response->toArray();

            $pokedexNumber = $content['id'];
            $name = $content['name'];
            $sprite = $content['sprites']['other']['dream_world']['front_default'];
            $type1 = $content['types'][0]['type']['name'];
            $type2 = (isset($content['types'][1])) ? $content['types'][1]['type']['name'] : null;


            $pokemons[] = ['pokedexNumber' => $pokedexNumber, 'name' => $name, 'sprite' => $sprite, 'type1' => $type1, 'type2' => $type2];
        }
        return $this->render('pokemon/index.html.twig', ['pokemons' => $pokemons]);
    }

    // // #[Route('/savePokemon', name: 'app_save_pokemon')]
    // // public function savePokemon(PokemonRepository $pokemonRepository, HttpClientInterface $client)
    // // {

    // //     $pokemons = [];

    // //     for ($id = 1; $id <= 151; $id++) {
    // //         $response = $client->request('GET', 'https://pokeapi.co/api/v2/pokemon/' . $id);
    // //         $content = $response->toArray();

    // //         $pokedexNumber = $content['id'];
    // //         $name = $content['name'];
    // //         $sprite = $content['sprites']['other']['dream_world']['front_default'];
    // //         $type1 = $content['types'][0]['type']['name'];
    // //         $type2 = (isset($content['types'][1])) ? $content['types'][1]['type']['name'] : null;

    // //         $pokemons[] = ['pokedexNumber' => $pokedexNumber, 'name' => $name, 'sprite' => $sprite, 'type1' => $type1, 'type2' => $type2];

    // //         $pokemon = new Pokemon();
    // //         $pokemon->setPokedexNumber($pokedexNumber);
    // //         $pokemon->setName($name);
    // //         $pokemon->setSprite($sprite);
    // //         $pokemon->setType([$type1, $type2]);

    // //         $pokemonRepository->save($pokemon, true);
    // //     }
    // //     return $this->redirect('/pokemon');
    // }

    #[Route('/savePokemon/{name}', name: 'app_save_pokemon')]
    public function savePokemon(string $name = null, ProfileRepository $profileRepository, PokemonRepository $pokemonRepository, HttpClientInterface $client)
    {
        $trainerId = $profileRepository->findOneBy(['id' => $this->getUser()]);
        // Get the rest of the Pokemon information using the API
        $response = $client->request('GET', 'https://pokeapi.co/api/v2/pokemon/' . $name);
        $content = $response->toArray();

        $pokedexNumber = $content['id'];
        $name = $content['name'];
        $sprite = $content['sprites']['other']['dream_world']['front_default'];
        $type1 = $content['types'][0]['type']['name'];
        $type2 = (isset($content['types'][1])) ? $content['types'][1]['type']['name'] : null;

        // Create a new Pokemon entity and set the trainer_id and other Pokemon information
        $pokemon = new Pokemon();
        $pokemon->setPokedexNumber($pokedexNumber);
        $pokemon->setName($name);
        $pokemon->setSprite($sprite);
        $pokemon->setType([$type1, $type2]);
        $pokemon->addTrainerId($trainerId);

        // Save the Pokemon entity to the database
        $pokemonRepository->save($pokemon, true);

        return $this->redirect('/pokemon');
    }
}