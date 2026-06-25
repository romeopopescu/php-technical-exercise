<?php

declare(strict_types=1);

namespace AcmeLearn\Importer\Api;

use GraphQL\Executor\Executor;
use GraphQL\Type\Schema as GraphQLSchema;
use GraphQL\Utils\BuildSchema;

/**
 * Builds the executable GraphQL schema from the SDL file and wires resolvers.
 *
 * Query and Mutation fields dispatch to a per-operation resolver class
 * (resolvers/query/<Field>.php, resolvers/mutation/<Field>.php); every other field
 * (the User / ImportSummary / SkippedRow scalars) falls back to webonyx's default
 * resolver, which reads the matching array key — the UserMapper already produces
 * camelCase keys that line up with the schema.
 */
final class Schema
{
    public static function build(): GraphQLSchema
    {
        return BuildSchema::build((string) file_get_contents(__DIR__ . '/graphql/schema.graphql'));
    }

    /**
     * The field resolver passed to GraphQL::executeQuery. The PDO connection
     * travels in the context array.
     *
     * Root fields map to a resolver class by name — `users` -> Resolvers\Query\Users,
     * `importCsv` -> Resolvers\Mutation\ImportCsv — which the autoloader resolves, so
     * adding an operation is just adding a file. Non-root fields fall back to the
     * default resolver.
     */
    public static function fieldResolver(): callable
    {
        return static function ($source, array $args, $context, $info) {
            $kind = $info->parentType->name;

            if ($kind !== 'Query' && $kind !== 'Mutation') {
                return Executor::getDefaultFieldResolver()($source, $args, $context, $info);
            }

            $class = __NAMESPACE__ . '\\Resolvers\\' . $kind . '\\' . ucfirst($info->fieldName);

            if (!class_exists($class)) {
                return null;
            }

            return (new $class())->resolve($context['pdo'], $args);
        };
    }
}
