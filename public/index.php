<?php

require '../vendor/autoload.php';

use App\Middleware\Auth\AuthMiddleware;
use App\Middleware\Session\SessionMiddleware;
use App\Service\Auth\Auth;
use App\Service\ErrorHandler\ErrorHandler;
use App\Service\Factory\Eloquent\EloquentFactory;
use Slim\Views\PhpRenderer;

$app = new \Slim\App(require_once __DIR__ . '/../config/config.php');

$container                           = $app->getContainer();
$container['auth']                   = new Auth($container);
$container['database']               = EloquentFactory::create($container->config['database']);
$container['errorHandler']           = new ErrorHandler();
$container['phpErrorHandler']        = new ErrorHandler();
$container['renderer']               = new PhpRenderer('../template');

$app->add(new AuthMiddleware($container));
$app->add(new SessionMiddleware($container));
$app->add(new RKA\Middleware\IpAddress(false, []));

require_once '../config/routes.php';

$app->run();
