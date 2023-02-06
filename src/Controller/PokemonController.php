<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokemonController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    public function Pokemons(HttpClientInterface $client): array
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


            $pokemons[] = $name;
        }
        return $pokemons;
    }

    #[Route('/pokemon', name: 'app_pokemon')]
    public function allPokemons(HttpClientInterface $client, Request $request): Response
    {
        $pokemons = [];
        $pokemonsNames = $this->Pokemons($client);
        $name = $request->request->get('search');
        $shortest = -1;
        $closest = "";

        if ($name) {
            foreach ($pokemonsNames as $pokemonName) {

                // calcule la distance avec le mot mis en entrée,
                // et le mot courant
                $lev = levenshtein($name, $pokemonName);

                // cherche une correspondance exacte
                if ($lev == 0) {

                    // le mot le plus près est celui-ci (correspondance exacte)
                    $closest = $pokemonName;
                    $shortest = 0;

                    // on sort de la boucle ; nous avons trouvé une correspondance exacte
                    break;
                }
                if ($lev <= $shortest || $shortest < 0) {
                    // définition du mot le plus près ainsi que la distance
                    $closest  = $pokemonName;
                    $shortest = $lev;
                }
            }


            $response = $client->request('GET', 'https://pokeapi.co/api/v2/pokemon/' . $closest);
            $content = $response->toArray();

            $pokedexNumber = $content['id'];
            $name = $content['name'];
            $sprite = $content['sprites']['other']['official-artwork']['front_default'];
            $type1 = $content['types'][0]['type']['name'];
            $type2 = (isset($content['types'][1])) ? $content['types'][1]['type']['name'] : null;

            $pokemons[] = ['pokedexNumber' => $pokedexNumber, 'name' => $name, 'sprite' => $sprite, 'type1' => $type1, 'type2' => $type2];

        } else {
            for ($id = 1; $id <= 151; $id++) {
                $response = $client->request('GET', 'https://pokeapi.co/api/v2/pokemon/' . $id);
                $content = $response->toArray();

                $pokedexNumber = $content['id'];
                $name = $content['name'];
                $sprite = $content['sprites']['other']['official-artwork']['front_default'];
                $type1 = $content['types'][0]['type']['name'];
                $type2 = (isset($content['types'][1])) ? $content['types'][1]['type']['name'] : null;

                $pokemons[] = ['pokedexNumber' => $pokedexNumber, 'name' => $name, 'sprite' => $sprite, 'type1' => $type1, 'type2' => $type2];
            }
        }
        return $this->render('pokemon/index.html.twig', ['pokemons' => $pokemons, 'closest' => $closest]);
    }


    #[Route('/savePokemon/{name}', name: 'app_save_pokemon')]
    public function savePokemon(string $name = null, ProfileRepository $profileRepository, PokemonRepository $pokemonRepository, HttpClientInterface $client)
    {
        $trainerId = $profileRepository->findOneBy(['id' => $this->getUser()]);
        $response = $client->request('GET', 'https://pokeapi.co/api/v2/pokemon/' . $name);
        $content = $response->toArray();

        $pokedexNumber = $content['id'];
        $name = $content['name'];
        $sprite = $content['sprites']['other']['official-artwork']['front_default'];
        $type1 = $content['types'][0]['type']['name'];
        $type2 = (isset($content['types'][1])) ? $content['types'][1]['type']['name'] : null;

        $pokemon = new Pokemon();
        $pokemon->setPokedexNumber($pokedexNumber);
        $pokemon->setName($name);
        $pokemon->setSprite($sprite);
        $pokemon->setType([$type1, $type2]);
        $pokemon->addTrainerId($trainerId);

        $pokemonRepository->save($pokemon, true);

        return $this->redirect('/pokemon');
    }

    #[Route('/deletePokemon/{name}', name: 'app_delete_pokemon')]
    public function deletePokemon(string $name = null, PokemonRepository $pokemonRepository, ProfileRepository $profileRepository)
    {
        $trainer = $profileRepository->findOneBy(['id' => $this->getUser()]);
        $pokemon = $pokemonRepository->findOneBy(['name' => $name]);

        if (!$pokemon) {
            throw new NotFoundHttpException('Pokemon not found');
        }

        $trainer->removePokemon($pokemon);
        $pokemonRepository->remove($pokemon, true);

        return $this->redirect('/team');
    }
}
