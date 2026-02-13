<?php

namespace App\Controllers;

use App\Models\Service\PokemonService;

/**
 * ApiTestsController
 *
 * Controlador para pruebas y demostración de la API PokeAPI.
 * Proporciona páginas de prueba interactivas para validar la integración
 * con PokeAPI y sus distintos endpoints.
 *
 * @package App\Controllers
 */
class ApiTestsController
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
     * Muestra la página principal de pruebas
     *
     * Renderiza una vista con formularios interactivos para probar
     * los diferentes endpoints de PokeAPI integrados en la aplicación.
     *
     * @return void
     */
    public function index(): void
    {
        render_view('api-tests/index.php', [
            'title' => 'Pruebas API PokeAPI'
        ]);
    }

    /**
     * Prueba el endpoint de obtener Pokémon por ID
     *
     * Realiza una petición para obtener información de un Pokémon específico
     * basado en un ID proporcionado y devuelve los resultados en JSON.
     *
     * @return void
     */
    public function testGetPokemonById(): void
    {
        header('Content-Type: application/json');

        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Parámetro "id" requerido']);
            return;
        }

        $id = (int)$_GET['id'];
        $pokemon = $this->pokemonService->getPokemonById($id);

        if (!$pokemon) {
            http_response_code(404);
            echo json_encode(['error' => "Pokémon con ID {$id} no encontrado"]);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'id' => $pokemon->id,
                'name' => $pokemon->name,
                'height' => $pokemon->getHeightInMeters(),
                'weight' => $pokemon->getWeightInKilograms(),
                'types' => $pokemon->types,
                'abilities' => $pokemon->abilities,
                'stats' => $pokemon->stats,
                'image' => $pokemon->imageUrl
            ]
        ]);
    }

    /**
     * Prueba el endpoint de búsqueda de Pokémon por nombre
     *
     * Realiza una búsqueda de un Pokémon por su nombre y devuelve
     * la información encontrada en formato JSON.
     *
     * @return void
     */
    public function testSearchPokemon(): void
    {
        header('Content-Type: application/json');

        if (!isset($_GET['name'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Parámetro "name" requerido']);
            return;
        }

        $name = (string)$_GET['name'];
        $pokemon = $this->pokemonService->searchPokemonByName($name);

        if (!$pokemon) {
            http_response_code(404);
            echo json_encode(['error' => "Pokémon con nombre '{$name}' no encontrado"]);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'id' => $pokemon->id,
                'name' => $pokemon->name,
                'height' => $pokemon->getHeightInMeters(),
                'weight' => $pokemon->getWeightInKilograms(),
                'types' => $pokemon->types,
                'abilities' => $pokemon->abilities,
                'stats' => $pokemon->stats,
                'image' => $pokemon->imageUrl
            ]
        ]);
    }

    /**
     * Prueba el endpoint de listar Pokémon con paginación
     *
     * Obtiene una lista paginada de Pokémon y devuelve los resultados
     * junto con información de paginación en formato JSON.
     *
     * @return void
     */
    public function testGetPokemons(): void
    {
        header('Content-Type: application/json');

        $limit = isset($_GET['limit']) ? min((int)$_GET['limit'], 20) : 10;
        $offset = isset($_GET['offset']) ? max((int)$_GET['offset'], 0) : 0;

        $result = $this->pokemonService->getPokemons($limit, $offset);

        $pokemons = [];
        foreach ($result['pokemons'] as $pokemon) {
            $pokemons[] = [
                'id' => $pokemon->id,
                'name' => $pokemon->name,
                'types' => $pokemon->types,
                'image' => $pokemon->imageUrl
            ];
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'pokemons' => $pokemons,
                'pagination' => [
                    'limit' => $limit,
                    'offset' => $offset,
                    'total' => $result['total']
                ]
            ]
        ]);
    }

    /**
     * Prueba el endpoint de filtrar Pokémon por tipo
     *
     * Obtiene todos los Pokémon de un tipo específico y devuelve
     * la lista en formato JSON.
     *
     * @return void
     */
    public function testFilterByType(): void
    {
        header('Content-Type: application/json');

        if (!isset($_GET['type'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Parámetro "type" requerido']);
            return;
        }

        $type = (string)$_GET['type'];
        $pokemons = $this->pokemonService->getPokemonsByType($type);

        if (empty($pokemons)) {
            http_response_code(404);
            echo json_encode(['error' => "No se encontraron Pokémon del tipo '{$type}'"]);
            return;
        }

        $pokemonList = [];
        foreach ($pokemons as $pokemon) {
            $pokemonList[] = [
                'id' => $pokemon->id,
                'name' => $pokemon->name,
                'types' => $pokemon->types,
                'image' => $pokemon->imageUrl
            ];
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'type' => $type,
                'count' => count($pokemonList),
                'pokemons' => $pokemonList
            ]
        ]);
    }

    /**
     * Obtiene la lista de todos los tipos de Pokémon disponibles
     *
     * Devuelve un array de todos los tipos de Pokémon que están disponibles
     * en PokeAPI en formato JSON.
     *
     * @return void
     */
    public function testGetAllTypes(): void
    {
        header('Content-Type: application/json');

        $types = $this->pokemonService->getAllTypes();

        echo json_encode([
            'success' => true,
            'data' => [
                'types' => $types,
                'total' => count($types)
            ]
        ]);
    }
}

