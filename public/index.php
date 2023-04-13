<?php

/** autoload */
require '../vendor/autoload.php';

/** error handling */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/** Load .env file(s) */
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$router = new Core\Router();

$router->add('',['controller' => 'Home', 'action' => 'index']);

// Vanilla SSR routes, not used here
// $router->add('{controller}/{action}');
// $router->add('{controller}/{id:\d+}/{action}');
// $router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

/** User routes */
$router->add('api/{controller}/{action}');
$router->add('api/{controller}/{id:\d+}/{action}');

/** Admin routes */
$router->add('api/admin/{controller}/{action}', ['namespace' => 'Admin']);
$router->add('api/admin/{controller}/{id:\d+}/{action}', ['namespace' => 'Admin']);

$router->dispatch($_SERVER['QUERY_STRING']);

