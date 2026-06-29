<?php

declare(strict_types=1);

namespace AcmeLearn\Importer\Api\Resolvers\Mutation;

use AcmeLearn\Importer\Api\Resolvers\mutation_resolver;
use AcmeLearn\Importer\Api\UserMapper;
use AcmeLearn\Importer\UserRepository;
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
        $repository = new UserRepository($pdo);
        $columns    = UserMapper::inputToColumns($args['input']);

        if ($columns !== []) {
            $repository->update($args['id'], $columns);
        }

        $row = $repository->find($args['id']);
        if ($row === null) {
            throw new RuntimeException("User {$args['id']} not found.");
        }

        return UserMapper::toGraphql($row);
    }
}
