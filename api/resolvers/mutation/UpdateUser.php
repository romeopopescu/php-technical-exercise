<?php

declare(strict_types=1);

namespace AcmeLearn\Importer\Api\Resolvers\Mutation;

use AcmeLearn\Importer\Api\Resolvers\mutation_resolver;
use PDO;
use RuntimeException;

/**
 * Resolves the `updateUser` mutation.
 */
final class UpdateUser extends mutation_resolver
{
    /**
     * @param array{id: int, input: array<string, string|bool>} $args
     *
     * @return array<string, string|int|bool>
     */
    public function resolve(PDO $pdo, array $args): array
    {
        // Task 2: wire this resolver up to update an existing user and return it.
        throw new RuntimeException('updateUser is not implemented yet.');
    }
}
