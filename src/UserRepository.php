<?php

declare(strict_types=1);

namespace AcmeLearn\Importer;

use PDO;

/**
 * Persists users to the platform's user store.
 */
final class UserRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @param array<string, string|int> $user
     */
    public function insert(array $user): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (hr_id, first_name, last_name, email, department, is_active)
             VALUES (:hr_id, :first_name, :last_name, :email, :department, :is_active)'
        );

        $stmt->execute([
            ':hr_id'      => (string) $user['hr_id'],
            ':first_name' => $user['first_name'],
            ':last_name'  => $user['last_name'],
            ':email'      => $user['email'],
            ':department' => $user['department'] ?? '',
            ':is_active'  => (int) $user['is_active'],
        ]);
    }

    public function existsByHrId(string $hrId): bool
    {
        $stmt = $this->pdo->prepare('SELECT 1 FROM users WHERE hr_id = :hr_id');
        $stmt->execute([':hr_id' => $hrId]);

        return $stmt->fetchColumn() !== false;
    }

    /**
     * @return array<int, array<string, string|int>>
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id, hr_id, first_name, last_name, email, department, is_active FROM users ORDER BY id'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<string, string|int>|null
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, hr_id, first_name, last_name, email, department, is_active FROM users WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $row;
    }

    public function count(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }
}
