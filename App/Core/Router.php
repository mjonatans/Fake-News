<?php

namespace App\Core;

use FastRoute;
class Router
{
    public static function route()
    {
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/', [\App\Controllers\PostController::class, 'allPosts']);
            $r->addRoute('GET', '/articles', [\App\Controllers\PostController::class, 'allPosts']);
            $r->addRoute('GET', '/users', [\App\Controllers\PostController::class, 'allUsers']);
            $r->addRoute('GET', '/articles/{id:\d+}', [\App\Controllers\PostController::class, 'singlePost']);
            $r->addRoute('GET', '/users/{id:\d+}', [\App\Controllers\PostController::class, 'singleUser']);
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {

            case FastRoute\Dispatcher::NOT_FOUND:
                return new View('notFound', []);

            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return $routeInfo[1];

            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                [$controller, $method] = $handler;

                return (new $controller)->{$method}($vars);
        }
        return null;
    }
}