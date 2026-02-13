<?php

namespace App\Models\Service;

use App\Models\Entity\Pokemon;
use Core\HttpClient;

/**
 * PokemonService
 *
 * Servicio encargado de consumir y gestionar datos de la API PokeAPI.
 * Proporciona métodos para buscar, obtener y listar Pokémon.
 * Utiliza la clase HttpClient para realizar las peticiones HTTP.
 *
 * @package App\Models\Service
 * @see https://pokeapi.co/
 */
class PokemonService
{
    /**
     * URL base de la API PokeAPI
     *
     * @const string API_BASE_URL
     */
    private const API_BASE_URL = 'https://pokeapi.co/api/v2';

    /**
     * Número máximo de Pokémon en la API (aproximado)
     *
     * @const int MAX_POKEMON
     */
    private const MAX_POKEMON = 1025;

    /**
     * Cache en memoria para evitar peticiones repetidas
     *
     * @var array
     */
    private static array $cache = [];

    /**
     * Obtiene un Pokémon por su ID
     *
     * Realiza una petición a la API PokeAPI para obtener los detalles
     * completos de un Pokémon específico. Implementa caché local.
     *
     * @param int $id ID del Pokémon a buscar
     *
     * @return Pokemon|null Retorna la instancia de Pokemon si se encuentra, null en caso contrario
     */
    public function getPokemonById(int $id): ?Pokemon
    {
        // Validar ID
        if ($id < 1 || $id > self::MAX_POKEMON) {
            return null;
        }

        // Verificar caché
        $cacheKey = "pokemon_{$id}";
        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        $url = self::API_BASE_URL . "/pokemon/{$id}";
        $response = HttpClient::get($url);

        if ($response['status'] !== 200 || $response['error']) {
            return null;
        }

        $pokemon = Pokemon::fromArray($response['data']);
        self::$cache[$cacheKey] = $pokemon;

        return $pokemon;
    }

    /**
     * Busca Pokémon por nombre
     *
     * Realiza una petición a la API PokeAPI para buscar un Pokémon
     * por su nombre (búsqueda exacta).
     *
     * @param string $name Nombre del Pokémon a buscar
     *
     * @return Pokemon|null Retorna la instancia de Pokemon si se encuentra, null en caso contrario
     */
    public function searchPokemonByName(string $name): ?Pokemon
    {
        if (empty($name)) {
            return null;
        }

        $name = strtolower(trim($name));

        // Verificar caché
        $cacheKey = "pokemon_name_{$name}";
        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        $url = self::API_BASE_URL . "/pokemon/{$name}";
        $response = HttpClient::get($url);

        if ($response['status'] !== 200 || $response['error']) {
            return null;
        }

        $pokemon = Pokemon::fromArray($response['data']);
        self::$cache[$cacheKey] = $pokemon;

        return $pokemon;
    }

    /**
     * Obtiene una lista de Pokémon con paginación
     *
     * Realiza una petición a la API PokeAPI para obtener una lista
     * de Pokémon con soporte para límite y offset (paginación).
     *
     * @param int $limit Número de Pokémon a obtener (máximo 20)
     * @param int $offset Número de Pokémon a saltar desde el inicio
     *
     * @return array Array con 'pokemons' (array de Pokemon) y 'total' (número total de Pokémon)
     */
    public function getPokemons(int $limit = 20, int $offset = 0): array
    {
        // Limitar valores
        $limit = min($limit, 20);
        $offset = max($offset, 0);

        $url = self::API_BASE_URL . "/pokemon?limit={$limit}&offset={$offset}";
        $response = HttpClient::get($url);

        if ($response['status'] !== 200 || $response['error']) {
            return ['pokemons' => [], 'total' => 0];
        }

        $pokemons = [];
        if (isset($response['data']['results']) && is_array($response['data']['results'])) {
            foreach ($response['data']['results'] as $result) {
                // Extraer ID de la URL
                preg_match('/\/pokemon\/(\d+)/', $result['url'], $matches);
                if (isset($matches[1])) {
                    $pokemon = $this->getPokemonById((int)$matches[1]);
                    if ($pokemon) {
                        $pokemons[] = $pokemon;
                    }
                }
            }
        }

        return [
            'pokemons' => $pokemons,
            'total' => $response['data']['count'] ?? 0
        ];
    }

    /**
     * Obtiene Pokémon de un tipo específico
     *
     * Realiza una petición a la API PokeAPI para obtener todos los Pokémon
     * que pertenecen a un tipo específico (p.ej: fire, water, grass, etc.).
     *
     * @param string $type Tipo de Pokémon a buscar
     *
     * @return array Array de instancias de Pokemon del tipo especificado
     */
    public function getPokemonsByType(string $type): array
    {
        if (empty($type)) {
            return [];
        }

        $type = strtolower(trim($type));
        $url = self::API_BASE_URL . "/type/{$type}";
        $response = HttpClient::get($url);

        if ($response['status'] !== 200 || $response['error']) {
            return [];
        }

        $pokemons = [];
        if (isset($response['data']['pokemon']) && is_array($response['data']['pokemon'])) {
            foreach ($response['data']['pokemon'] as $pokemonData) {
                if (isset($pokemonData['pokemon']['url'])) {
                    preg_match('/\/pokemon\/(\d+)/', $pokemonData['pokemon']['url'], $matches);
                    if (isset($matches[1])) {
                        $pokemon = $this->getPokemonById((int)$matches[1]);
                        if ($pokemon) {
                            $pokemons[] = $pokemon;
                        }
                    }
                }
            }
        }

        return $pokemons;
    }

    /**
     * Obtiene información de un tipo de Pokémon
     *
     * Realiza una petición a la API PokeAPI para obtener información
     * detallada sobre un tipo de Pokémon específico.
     *
     * @param string $type Nombre del tipo a buscar
     *
     * @return array|null Array con información del tipo o null si no existe
     */
    public function getTypeInfo(string $type): ?array
    {
        if (empty($type)) {
            return null;
        }

        $type = strtolower(trim($type));

        // Verificar caché
        $cacheKey = "type_{$type}";
        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        $url = self::API_BASE_URL . "/type/{$type}";
        $response = HttpClient::get($url);

        if ($response['status'] !== 200 || $response['error']) {
            return null;
        }

        self::$cache[$cacheKey] = $response['data'];
        return $response['data'];
    }

    /**
     * Obtiene una lista de todos los tipos de Pokémon
     *
     * Realiza una petición a la API PokeAPI para obtener la lista
     * completa de todos los tipos disponibles.
     *
     * @return array Array con la información de todos los tipos
     */
    public function getAllTypes(): array
    {
        // Verificar caché
        if (isset(self::$cache['all_types'])) {
            return self::$cache['all_types'];
        }

        $url = self::API_BASE_URL . "/type?limit=100";
        $response = HttpClient::get($url);

        if ($response['status'] !== 200 || $response['error']) {
            return [];
        }

        $types = [];
        if (isset($response['data']['results']) && is_array($response['data']['results'])) {
            foreach ($response['data']['results'] as $type) {
                $types[] = $type['name'];
            }
        }

        self::$cache['all_types'] = $types;
        return $types;
    }

    /**
     * Limpia el caché de la clase
     *
     * @return void
     */
    public static function clearCache(): void
    {
        self::$cache = [];
    }
}

