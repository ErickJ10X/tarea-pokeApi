<?php

namespace App\Models\Entity;

/**
 * User Entity
 *
 * Representa un usuario del sistema con su información de autenticación.
 * Esta clase encapsula los datos básicos de un usuario registrado.
 *
 * @package App\Models\Entity
 */
class User
{
    /**
     * Lista de campos permitidos para asignación masiva
     *
     * @const array FILLABLE
     */
    public const FILLABLE = ['name', 'email', 'password'];

    /**
     * Identificador único del usuario
     *
     * @var int|null
     */
    public ?int $id;

    /**
     * Nombre completo del usuario
     *
     * @var string|null
     */
    public ?string $name;

    /**
     * Email del usuario (usado para autenticación)
     *
     * @var string|null
     */
    public ?string $email;

    /**
     * Contraseña del usuario (hasheada con password_hash)
     *
     * @var string|null
     */
    public ?string $password;

    /**
     * Constructor de la clase User
     *
     * @param array $attributes Array asociativo con los datos del usuario
     */
    public function __construct(array $attributes = [])
    {
        $this->id = isset($attributes['id']) ? (int)$attributes['id'] : null;
        $this->name = $attributes['name'] ?? null;
        $this->email = $attributes['email'] ?? null;
        $this->password = $attributes['password'] ?? null;
    }

    /**
     * Crea una instancia de User a partir de un array
     *
     * @param array $data Array con los datos del usuario
     * @return self Instancia nueva de la clase User
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

}

