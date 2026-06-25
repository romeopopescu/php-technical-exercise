<?php

declare(strict_types=1);

/**
 * Router for the PHP built-in server: `php -S localhost:8080 public/router.php`.
 *
 * Routes /graphql to the API and serves the built Vue SPA (client/dist) for
 * everything else, falling back to index.html so client-side navigation works.
 */

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

if ($path === '/graphql') {
    require __DIR__ . '/graphql.php';

    return;
}

$distDir = __DIR__ . '/../client/dist';
$indexHtml = $distDir . '/index.html';

if (!is_file($indexHtml)) {
    http_response_code(503);
    header('Content-Type: text/plain');
    echo "The Vue app has not been built yet.\n\n"
        . "Run:  cd client && npm install && npm run build\n"
        . "Then reload this page. (For live development use `npm run dev` instead.)\n";

    return;
}

$requested = realpath($distDir . $path);
$distRoot = realpath($distDir);

if ($path !== '/' && $requested !== false && str_starts_with($requested, $distRoot) && is_file($requested)) {
    $mimeTypes = [
        'js'   => 'text/javascript',
        'css'  => 'text/css',
        'html' => 'text/html',
        'json' => 'application/json',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'png'  => 'image/png',
        'woff2' => 'font/woff2',
    ];
    $ext = strtolower(pathinfo($requested, PATHINFO_EXTENSION));
    header('Content-Type: ' . ($mimeTypes[$ext] ?? 'application/octet-stream'));
    readfile($requested);

    return;
}

header('Content-Type: text/html');
readfile($indexHtml);
