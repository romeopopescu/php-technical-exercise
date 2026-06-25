<?php

declare(strict_types=1);

namespace AcmeLearn\Importer\Api;

/**
 * Maps user rows between the store's snake_case columns and the GraphQL camelCase fields.
 */
final class UserMapper
{
    /**
     * Convert a database row into the shape the GraphQL User type expects.
     *
     * @param array<string, string|int> $row
     *
     * @return array<string, string|int|bool>
     */
    public static function toGraphql(array $row): array
    {
        return [
            'id'         => (int) $row['id'],
            'hrId'       => (string) $row['hr_id'],
            'firstName'  => (string) $row['first_name'],
            'lastName'   => (string) $row['last_name'],
            'email'      => (string) $row['email'],
            'department' => (string) $row['department'],
            'isActive'   => (bool) $row['is_active'],
        ];
    }

    /**
     * Convert a GraphQL UpdateUserInput into store columns, keeping only provided keys.
     *
     * @param array<string, string|bool> $input
     *
     * @return array<string, string|int>
     */
    public static function inputToColumns(array $input): array
    {
        $map = [
            'firstName'  => 'first_name',
            'lastName'   => 'last_name',
            'email'      => 'email',
            'department' => 'department',
            'isActive'   => 'is_active',
        ];

        $columns = [];
        foreach ($map as $field => $column) {
            if (!array_key_exists($field, $input)) {
                continue;
            }
            $columns[$column] = $field === 'isActive' ? (int) $input[$field] : (string) $input[$field];
        }

        return $columns;
    }
}
