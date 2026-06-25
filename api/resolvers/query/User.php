<?php

declare(strict_types=1);

namespace AcmeLearn\Importer\Api\Resolvers\Query;

use AcmeLearn\Importer\Api\Resolvers\query_resolver;
use AcmeLearn\Importer\Api\UserMapper;
use AcmeLearn\Importer\UserRepository;
use PDO;

/**
 * Resolves the `user(id:)` query: look up a single user by store id.
 */
final class User extends query_resolver
{
    /**
     * @param array{id: int} $args
     *
     * @return array<string, string|int|bool>|null
     */
    public function resolve(PDO $pdo, array $args): ?array
    {
        $row = (new UserRepository($pdo))->find($args['id']);

        return $row === null ? null : UserMapper::toGraphql($row);
    }
}
