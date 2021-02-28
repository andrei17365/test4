<?php

require '../vendor/autoload.php';
require '../app/config.php';

$container = $containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/test', ['App\controllers\UserController','test']);
    $r->addRoute('GET', '/', ['App\controllers\UserController','register']);
    $r->addRoute('GET', '/register', ['App\controllers\UserController','register']);
    $r->addRoute('POST', '/register', ['App\controllers\UserController','register_edit']);
    $r->addRoute('GET', '/login', ['App\controllers\UserController','login']);
    $r->addRoute('POST', '/login', ['App\controllers\UserController','login_edit']);
    $r->addRoute('GET', '/users', ['App\controllers\UserController','users']);
    $r->addRoute('GET', '/create_user', ['App\controllers\UserController','create_user']);
    $r->addRoute('POST', '/create_user_edit', ['App\controllers\UserController','create_user_edit']);
    // {id} must be a number (\d+)
    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    // The /{title} suffix is optional
    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo 404;
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo 'Метод не разрешен';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // ... call $handler with $vars
        //    $controller = new $handler[0];
        //    call_user_func([$controller, $handler[1]], $vars);
        $container->call($handler,$vars);
        break;
}