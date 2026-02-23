<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\Api\ApiQueryAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->get('/api', function (Request $request, Response $response) {
        $base = (string) $request->getUri()->getScheme() . '://' . (string) $request->getUri()->getAuthority();

        $queryLinks = '';
        for ($i = 1; $i <= 10; $i++) {
            $url = $base . '/api/' . $i;
            $queryLinks .= '<li><a href="' . $url . '">GET /api/' . $i . '</a></li>';
        }

        $html = '<!doctype html>'
            . '<html lang="it"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">'
            . '<title>API Docs</title>'
            . '<style>body{font-family:Arial,sans-serif;max-width:900px;margin:40px auto;padding:0 16px;line-height:1.5}h1{margin-bottom:8px}code{background:#f4f4f4;padding:2px 6px;border-radius:4px}ul{padding-left:20px}a{text-decoration:none}</style>'
            . '</head><body>'
            . '<h1>Documentazione API</h1>'
            . '<p>Endpoint principale per le query: <code>GET /api/{n}</code></p>'
            . '<h2>Endpoint disponibili</h2>'
            . '<ul>'
            . '<li><a href="' . $base . '/">GET /</a></li>'
            . '<li><a href="' . $base . '/api">GET /api</a> (questa pagina)</li>'
            . $queryLinks
            . '<li><a href="' . $base . '/users">GET /users</a></li>'
            . '<li><a href="' . $base . '/users/1">GET /users/{id}</a></li>'
            . '</ul>'
            . '<p>Per provare le query del database apri direttamente i link <code>/api/1</code> ... <code>/api/10</code>.</p>'
            . '</body></html>';

        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html; charset=UTF-8');
    });

    $app->get('/api/{n}', ApiQueryAction::class);

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
