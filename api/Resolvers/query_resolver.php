<?php

declare(strict_types=1);

namespace AcmeLearn\Importer\Api\Resolvers;

use PDO;

/**
 * Base class for every GraphQL Query resolver. Each Query field has its own
 * concrete subclass under resolvers/query/, mirroring Totara's per-operation
 * resolver layout. Schema dispatches by instantiating the matching class and
 * calling resolve().
 */
abstract class query_resolver
{
    /**
     * @param array<string, mixed> $args
     *
     * @return mixed The value for the resolved field.
     */
    abstract public function resolve(PDO $pdo, array $args): mixed;
}
