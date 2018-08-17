<?php

require '../vendor/autoload.php';

use App\Middleware\Authentication\AuthenticationMiddleware;
use App\Middleware\Authorization\AuthorizationMiddleware;
use App\Middleware\Menu\MenuMiddleware;
use App\Service\Authentication\Authentication;
use App\Service\Authorization\Authorization;
use App\Service\ErrorHandler\ErrorHandler;
use App\Service\Factory\Eloquent\EloquentFactory;
use App\Service\MenuBuilder\MenuBuilder;
use App\Service\NotFoundHandler\NotFoundHandler;
use App\Service\Session\Session;
use Slim\Views\PhpRenderer;

$app = new \Slim\App(require_once __DIR__ . '/../config/config.php');

$container                           = $app->getContainer();
$container['session']                = (new Session($container->config['session']))->start();
$container['authorization']          = new Authorization($container);
$container['authentication']         = new Authentication($container);
$container['database']               = EloquentFactory::create($container->config['database']);
$container['errorHandler']           = new ErrorHandler();
$container['flash']                  = new \Slim\Flash\Messages();
$container['menuBuilder']            = new MenuBuilder($container->router, $container->authentication, $container->authorization);
$container['notFoundHandler']        = new NotFoundHandler();
$container['phpErrorHandler']        = new ErrorHandler();
$container['renderer']               = new PhpRenderer('../template');

if (file_exists(__DIR__ . '/../config/custom/middleware.php')) {
	require_once __DIR__ . '/../config/custom/middleware.php';
}

$app->add(new MenuMiddleware($container));
$app->add(new AuthorizationMiddleware($container));
$app->add(new AuthenticationMiddleware($container));
$app->add(new RKA\Middleware\IpAddress(false, []));

require_once __DIR__ . '/../config/routes.php';

$app->run();
