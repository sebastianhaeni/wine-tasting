<?php
use Silex\Provider\SessionServiceProvider;
use WineTasting\Service\Router;

require_once __DIR__ . '/bootstrap.php';

$app->register(new SessionServiceProvider());

$router = new Router();
$router->constructRoutes($app);

$app->run();
