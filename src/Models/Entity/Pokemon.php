<?php

namespace App\Models\Entity;

/**
 * Pokemon Entity
 *
 * Representa un Pokémon obtenido de la API PokeAPI.
 * Esta clase encapsula los datos de un Pokémon incluyendo información
 * básica, tipos, estadísticas e imágenes.
 *
 * @package App\Models\Entity
 */
class Pokemon
{
    /**
     * Identificador único del Pokémon en PokeAPI
     *
     * @var int|null
     */
    public ?int $id;

    /**
     * Nombre del Pokémon
     *
     * @var string|null
     */
    public ?string $name;

    /**
     * Altura del Pokémon en decímetros
     *
     * @var int|null
     */
    public ?int $height;

    /**
     * Peso del Pokémon en hectogramos
     *
     * @var int|null
     */
    public ?int $weight;

    /**
     * Array de tipos del Pokémon (p.ej: ['fire', 'flying'])
     *
     * @var array
     */
    public array $types = [];

    /**
     * URL de la imagen oficial del Pokémon
     *
     * @var string|null
     */
    public ?string $imageUrl;

    /**
     * Array de estadísticas del Pokémon (hp, attack, defense, etc.)
     *
     * @var array
     */
    public array $stats = [];

    /**
     * Array de habilidades del Pokémon
     *
     * @var array
     */
    public array $abilities = [];

    /**
     * Constructor de la clase Pokemon
     *
     * @param int|null $id Identificador del Pokémon
     * @param string|null $name Nombre del Pokémon
     * @param int|null $height Altura del Pokémon
     * @param int|null $weight Peso del Pokémon
     */
    public function __construct(?int $id = null, ?string $name = null, ?int $height = null, ?int $weight = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->height = $height;
        $this->weight = $weight;
        $this->imageUrl = null;
    }

    /**
     * Crea una instancia de Pokemon a partir de un array de datos de la API PokeAPI
     *
     * @param array $data Array con los datos del Pokémon obtenidos de la API
     *
     * @return Pokemon Instancia del Pokémon con los datos asignados
     */
    public static function fromArray(array $data): Pokemon
    {
        $pokemon = new self(
            $data['id'] ?? null,
            $data['name'] ?? null,
            $data['height'] ?? null,
            $data['weight'] ?? null
        );

        // Procesar tipos
        if (isset($data['types']) && is_array($data['types'])) {
            foreach ($data['types'] as $type) {
                if (isset($type['type']['name'])) {
                    $pokemon->types[] = $type['type']['name'];
                }
            }
        }

        // Procesar imagen
        if (isset($data['sprites']['other']['official-artwork']['front_default'])) {
            $pokemon->imageUrl = $data['sprites']['other']['official-artwork']['front_default'];
        } elseif (isset($data['sprites']['front_default'])) {
            $pokemon->imageUrl = $data['sprites']['front_default'];
        }

        // Procesar estadísticas
        if (isset($data['stats']) && is_array($data['stats'])) {
            foreach ($data['stats'] as $stat) {
                if (isset($stat['stat']['name']) && isset($stat['base_stat'])) {
                    $pokemon->stats[$stat['stat']['name']] = $stat['base_stat'];
                }
            }
        }

        // Procesar habilidades
        if (isset($data['abilities']) && is_array($data['abilities'])) {
            foreach ($data['abilities'] as $ability) {
                if (isset($ability['ability']['name'])) {
                    $pokemon->abilities[] = $ability['ability']['name'];
                }
            }
        }

        return $pokemon;
    }

    /**
     * Obtiene la altura del Pokémon en metros
     *
     * @return float Altura en metros
     */
    public function getHeightInMeters(): float
    {
        return $this->height ? $this->height / 10 : 0;
    }

    /**
     * Obtiene el peso del Pokémon en kilogramos
     *
     * @return float Peso en kilogramos
     */
    public function getWeightInKilograms(): float
    {
        return $this->weight ? $this->weight / 10 : 0;
    }

    /**
     * Obtiene los tipos del Pokémon como una cadena separada por comas
     *
     * @return string Tipos separados por comas en mayúsculas
     */
    public function getTypesAsString(): string
    {
        return implode(', ', array_map('ucfirst', $this->types));
    }

    /**
     * Obtiene el color de fondo según el tipo principal del Pokémon
     *
     * @return string Código de color hexadecimal o nombre de color CSS
     */
    public function getTypeColor(): string
    {
        $typeColors = [
            'normal' => '#A8A878',
            'fire' => '#F08030',
            'water' => '#6890F0',
            'electric' => '#F8D030',
            'grass' => '#78C850',
            'ice' => '#98D8D8',
            'fighting' => '#C03028',
            'poison' => '#A040A0',
            'ground' => '#E0C068',
            'flying' => '#A890F0',
            'psychic' => '#F85888',
            'bug' => '#A8B820',
            'rock' => '#B8A038',
            'ghost' => '#705898',
            'dragon' => '#7038F8',
            'dark' => '#705848',
            'steel' => '#B8B8D0',
            'fairy' => '#EE99AC'
        ];

        $primaryType = $this->types[0] ?? 'normal';
        return $typeColors[$primaryType] ?? '#A8A878';
    }
}

