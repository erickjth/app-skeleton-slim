<?php declare(strict_types = 1);

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$services = require 'config/services.php';

use App\Middleware\MvcRouterMiddleware;
use Psr7Middlewares\Middleware\TrailingSlash;

$application = new \Slim\App($services);

$application->add(new TrailingSlash(false));

// Mvc pattern
$application->any('/[{controller}[/{action}[/{params:.*}]]]', MvcRouterMiddleware::class);

$application->run();