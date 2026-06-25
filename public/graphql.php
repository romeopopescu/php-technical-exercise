<?php

declare(strict_types=1);

use AcmeLearn\Importer\Api\Schema;
use GraphQL\Error\DebugFlag;
use GraphQL\GraphQL;

require_once __DIR__ . '/../api/bootstrap.php';

// Keep PHP warnings/notices out of the JSON response body; GraphQL execution
// errors are returned in the payload's "errors" array instead.
ini_set('display_errors', '0');

header('Content-Type: application/json');

$input = json_decode((string) file_get_contents('php://input'), true);
$query = $input['query'] ?? null;
$variables = $input['variables'] ?? null;

if (!is_string($query)) {
    http_response_code(400);
    echo json_encode(['errors' => [['message' => 'Request body must include a "query" string.']]]);

    return;
}

$pdo = importer_pdo(__DIR__ . '/../data/users.sqlite');

$result = GraphQL::executeQuery(
    schema: Schema::build(),
    source: $query,
    contextValue: ['pdo' => $pdo],
    variableValues: $variables,
    fieldResolver: Schema::fieldResolver(),
);

echo json_encode($result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE));
