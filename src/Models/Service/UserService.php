<?php

namespace App\Models\Service;

use App\Models\Entity\User as UserEntity;
use App\Models\Repository\UserRepository;

/**
 * UserService
 *
 * Servicio de negocio para gestionar la lógica relacionada con Usuarios.
 * Actúa como intermediario entre los Controllers y el Repository,
 * encapsulando la lógica de negocio, validaciones y operaciones de autenticación.
 *
 * @package App\Models\Service
 */
class UserService
{
    /**
     * Instancia del repositorio de usuarios
     *
     * @var UserRepository
     */
    protected UserRepository $repo;

    /**
     * Campos permitidos para asignación masiva
     *
     * @var array
     */
    protected array $fillable = ['name', 'email', 'password'];

    /**
     * Constructor del servicio
     *
     * @param UserRepository|null $repo Instancia del repositorio. Si es null, crea una nueva
     */
    public function __construct(?UserRepository $repo = null)
    {
        $this->repo = $repo ?? new UserRepository();
    }

    /**
     * Obtiene todos los usuarios de la base de datos
     *
     * @return array Array de instancias de UserEntity
     */
    public function all(): array
    {
        return $this->repo->all();
    }

    /**
     * Busca un usuario por su ID
     *
     * @param int $id Identificador del usuario
     * @return UserEntity|null Instancia de UserEntity o null si no existe
     */
    public function find(int $id): ?UserEntity
    {
        return $this->repo->find($id);
    }

    /**
     * Busca un usuario por un campo específico
     *
     * @param string $column Nombre del campo a buscar (name, email, etc.)
     * @param mixed $value Valor a buscar
     * @return UserEntity|null Instancia de UserEntity o null si no existe
     */
    public function findBy(string $column, $value): ?UserEntity
    {
        return $this->repo->findBy($column, $value);
    }

    /**
     * Crea un nuevo usuario en la base de datos
     *
     * Hashea automáticamente la contraseña antes de guardarla.
     * Solo se insertan los campos permitidos en $fillable.
     *
     * @param array $data Array con los datos del usuario (name, email, password)
     * @return int|null ID del usuario creado o null si falla
     * @throws \InvalidArgumentException Si no hay datos válidos para insertar
     */
    public function create(array $data): ?int
    {
        $data = $this->onlyFillable($data);

        if (empty($data)) {
            throw new \InvalidArgumentException('No valid data provided to create user.');
        }

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $this->repo->create($data);
    }

    /**
     * Actualiza los datos de un usuario existente
     *
     * Hashea automáticamente la contraseña si se proporciona.
     * Solo actualiza los campos permitidos en $fillable.
     *
     * @param int $id Identificador del usuario a actualizar
     * @param array $data Array con los datos a actualizar
     * @return bool True si se actualizó exitosamente, false en caso contrario
     */
    public function update(int $id, array $data): bool
    {
        $data = $this->onlyFillable($data);

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $this->repo->update($id, $data);
    }

    /**
     * Elimina un usuario de la base de datos
     *
     * @param int $id Identificador del usuario a eliminar
     * @return bool True si se eliminó exitosamente, false en caso contrario
     */
    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }

    /**
     * Obtiene usuarios con paginación
     *
     * @param int $perPage Número de usuarios por página
     * @param int $page Número de página
     * @return array Array con 'data' (usuarios) y 'meta' (información de paginación)
     */
    public function paginate(int $perPage = 15, int $page = 1): array
    {
        return $this->repo->paginate($perPage, $page);
    }

    /**
     * Obtiene el número total de usuarios en la base de datos
     *
     * @return int Total de usuarios
     */
    public function count(): int
    {
        return $this->repo->count();
    }

    /**
     * Busca usuarios por nombre (búsqueda parcial)
     *
     * @param string $name Cadena a buscar en los nombres de usuarios
     * @return array Array de instancias de UserEntity que coinciden
     */
    public function search(string $name): array
    {
        return $this->repo->search($name);
    }

    /**
     * Autentica un usuario por email y contraseña
     *
     * Verifica la contraseña usando password_verify.
     * Incluye fallback para contraseñas en texto plano (legacy).
     *
     * @param string $email Email del usuario
     * @param string $password Contraseña en texto plano
     * @return UserEntity|false Instancia de UserEntity si la autenticación es exitosa, false en caso contrario
     */
    public function authenticate(string $email, string $password)
    {
        $user = $this->repo->findBy('email', $email);

        if (! $user) return false;

        if (isset($user->password) && password_verify($password, $user->password)) {
            return $user;
        }

        // fallback – compare plain text (legacy)
        if (isset($user->password) && $user->password === $password) {
            return $user;
        }

        return false;
    }

    /**
     * Filtra un array para mantener solo los campos permitidos en $fillable
     *
     * @param array $data Array de datos a filtrar
     * @return array Array filtrado con solo los campos permitidos
     */
    protected function onlyFillable(array $data): array
    {
        return array_filter(
            $data,
            fn($k) => in_array($k, $this->fillable, true),
            ARRAY_FILTER_USE_KEY
        );
    }
}

