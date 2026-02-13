<?php

namespace App\Models\Repository;

use Core\Database;
use App\Models\Entity\User as UserEntity;
use InvalidArgumentException;
use PDO;

/**
 * UserRepository
 *
 * Repositorio para gestionar las operaciones de persistencia de la entidad User
 * en la base de datos. Proporciona métodos para CRUD, búsqueda, paginación
 * y otras operaciones relacionadas con usuarios.
 *
 * @package App\Models\Repository
 */
class UserRepository
{
    /**
     * Nombre de la tabla en la base de datos
     *
     * @const string TABLE
     */
    private const string TABLE = 'users';

    /**
     * Instancia de conexión PDO
     *
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Constructor del repositorio
     *
     * @param PDO|null $pdo Instancia de PDO. Si es null, obtiene la conexión global
     */
    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance()->getConnection();
    }

    /**
     * Obtiene todos los usuarios de la base de datos
     *
     * @return array Array de instancias de UserEntity
     */
    public function all(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . self::TABLE);
        $stmt->execute();

        return array_map(
            fn(array $row) => UserEntity::fromArray($row),
            $stmt->fetchAll()
        );
    }

    /**
     * Busca un usuario por su ID
     *
     * @param int $id Identificador del usuario
     * @return UserEntity|null Instancia de UserEntity si existe, null en caso contrario
     */
    public function find(int $id): ?UserEntity
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();
        return $row ? UserEntity::fromArray($row) : null;
    }

    /**
     * Busca un usuario por un campo específico
     *
     * @param string $column Nombre de la columna a buscar
     * @param mixed $value Valor a buscar
     * @return UserEntity|null Instancia de UserEntity si existe, null en caso contrario
     * @throws InvalidArgumentException Si la columna no existe en la entidad User
     */
    public function findBy(string $column, mixed $value): ?UserEntity
    {
        if (!property_exists(UserEntity::class, $column)) {
            throw new InvalidArgumentException("Columna no válida: $column");
        }

        $stmt = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE $column = :value LIMIT 1");
        $stmt->execute(['value' => $value]);

        $row = $stmt->fetch();
        return $row ? UserEntity::fromArray($row) : null;
    }

    /**
     * Crea un nuevo usuario en la base de datos
     *
     * @param array $data Array con los datos del usuario (name, email, password)
     * @return int|null ID del usuario creado, null si falla
     * @throws InvalidArgumentException Si no hay datos válidos para insertar
     */
    public function create(array $data): ?int
    {
        $data = array_intersect_key($data, array_flip(UserEntity::FILLABLE));

        if (empty($data)) {
            throw new InvalidArgumentException('No hay datos válidos para insertar.');
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO " . self::TABLE . " ($columns) VALUES ($placeholders)";

        $this->pdo->prepare($sql)->execute($data);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza los datos de un usuario existente
     *
     * @param int $id Identificador del usuario a actualizar
     * @param array $data Array con los datos a actualizar
     * @return bool True si se actualizó exitosamente, false en caso contrario
     */
    public function update(int $id, array $data): bool
    {
        $data = array_intersect_key($data, array_flip(UserEntity::FILLABLE));

        if (empty($data)) {
            return false;
        }

        $set = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));

        $sql = "UPDATE " . self::TABLE . " SET $set WHERE id = :id";

        $data['id'] = $id;

        return $this->pdo->prepare($sql)->execute($data);
    }

    /**
     * Elimina un usuario de la base de datos
     *
     * @param int $id Identificador del usuario a eliminar
     * @return bool True si se eliminó exitosamente, false en caso contrario
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Obtiene usuarios con paginación
     *
     * @param int $perPage Número de usuarios por página (default: 15)
     * @param int $page Número de página (default: 1)
     * @return array Array con 'data' (usuarios) y 'meta' (información de paginación)
     */
    public function paginate(int $perPage = 15, int $page = 1): array
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " LIMIT :limit OFFSET :offset");
        $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        $countStmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM " . self::TABLE);
        $countStmt->execute();
        $total = (int) ($countStmt->fetch()['total'] ?? 0);

        return [
            'data' => array_map(fn($r) => UserEntity::fromArray($r), $rows),
            'meta' => [
                'total'        => $total,
                'per_page'     => $perPage,
                'current_page' => $page,
                'last_page'    => (int) ceil($total / $perPage),
            ]
        ];
    }

    /**
     * Obtiene el número total de usuarios en la base de datos
     *
     * @return int Total de usuarios
     */
    public function count(): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM " . self::TABLE);
        $stmt->execute();

        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    /**
     * Busca usuarios por nombre
     *
     * Busca usuarios cuyo nombre contenga la cadena especificada (búsqueda parcial)
     *
     * @param string $name Cadena a buscar en los nombres
     * @return array Array de instancias de UserEntity que coinciden
     */
    public function search(string $name): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE name LIKE :name");
        $stmt->execute(['name' => '%' . $name . '%']);

        return array_map(
            fn(array $row) => UserEntity::fromArray($row),
            $stmt->fetchAll()
        );
    }
}

