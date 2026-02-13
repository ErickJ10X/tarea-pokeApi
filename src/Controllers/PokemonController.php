<?php

namespace App\Controllers;

use App\Models\Service\PokemonService;

/**
 * PokemonController
 *
 * Controlador encargado de gestionar las peticiones relacionadas con Pokémon.
 * Utiliza el servicio PokemonService para consumir datos de la API PokeAPI
 * y renderiza las vistas correspondientes.
 *
 * @package App\Controllers
 */
class PokemonController
{
    /**
     * Instancia del servicio PokemonService
     *
     * @var PokemonService
     */
    private PokemonService $pokemonService;

    /**
     * Constructor del controlador
     *
     * Inicializa la instancia del servicio PokemonService.
     */
    public function __construct()
    {
        $this->pokemonService = new PokemonService();
    }

    /**
     * Muestra una lista paginada de Pokémon
     *
     * Renderiza la vista de lista de Pokémon con paginación.
     * Obtiene los Pokémon del servicio y los pasa a la vista.
     *
     * @return void
     */
    public function index(): void
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $result = $this->pokemonService->getPokemons($limit, $offset);

        render_view('pokemons/index.php', [
            'title' => 'Pokémon',
            'pokemons' => $result['pokemons'],
            'total' => $result['total'],
            'page' => $page,
            'limit' => $limit
        ]);
    }

    /**
     * Muestra los detalles de un Pokémon específico
     *
     * Renderiza la vista de detalle de un Pokémon basado en el ID o nombre
     * proporcionado en los parámetros GET. Si no se encuentra el Pokémon,
     * redirige a la lista de Pokémon.
     *
     * @return void
     */
    public function show(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $name = isset($_GET['name']) ? (string)$_GET['name'] : null;

        $pokemon = null;

        if ($id) {
            $pokemon = $this->pokemonService->getPokemonById($id);
        } elseif ($name) {
            $pokemon = $this->pokemonService->searchPokemonByName($name);
        }

        if (!$pokemon) {
            redirect_to('/pokemons');
            return;
        }

        render_view('pokemons/show.php', [
            'title' => ucfirst($pokemon->name),
            'pokemon' => $pokemon
        ]);
    }

    /**
     * Muestra el formulario y resultados de búsqueda de Pokémon
     *
     * Renderiza la vista de búsqueda. Si se proporciona un parámetro 'q',
     * realiza una búsqueda por nombre de Pokémon y muestra los resultados.
     *
     * @return void
     */
    public function search(): void
    {
        $query = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
        $pokemon = null;

        if (!empty($query)) {
            $pokemon = $this->pokemonService->searchPokemonByName($query);
        }

        render_view('pokemons/search.php', [
            'title' => 'Buscar Pokémon',
            'pokemon' => $pokemon,
            'query' => $query
        ]);
    }

    /**
     * Muestra Pokémon filtrados por tipo
     *
     * Renderiza la vista de Pokémon agrupados por tipo.
     * Si se proporciona un tipo en los parámetros GET, muestra solo los Pokémon
     * de ese tipo específico.
     *
     * @return void
     */
    public function filterByType(): void
    {
        $type = isset($_GET['type']) ? (string)$_GET['type'] : null;
        $allTypes = $this->pokemonService->getAllTypes();
        $pokemons = [];

        if ($type) {
            $pokemons = $this->pokemonService->getPokemonsByType($type);
        }

        render_view('pokemons/filter-by-type.php', [
            'title' => 'Filtrar por Tipo',
            'types' => $allTypes,
            'selectedType' => $type,
            'pokemons' => $pokemons
        ]);
    }
}

