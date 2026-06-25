<?php

declare(strict_types=1);

/**
 * Shared bootstrap for the web API: autoloading and the SQLite connection.
 *
 * This is the web equivalent of the old CLI entry point. It wires up Composer's
 * autoloader (for webonyx/graphql-php and the application classes) and hands back
 * a ready-to-use PDO connection with the schema applied.
 */

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Open the SQLite store and ensure the schema exists.
 *
 * @param string $dbPath Path to the SQLite file, or ':memory:' for an in-memory store.
 */
function importer_pdo(string $dbPath): PDO
{
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec((string) file_get_contents(__DIR__ . '/../schema.sql'));

    return $pdo;
}
