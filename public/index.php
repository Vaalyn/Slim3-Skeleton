<?php

declare(strict_types = 1);

require '../vendor/autoload.php';

use App\Service\ErrorHandler\ErrorHandler;
use App\Service\Factory\Eloquent\EloquentFactory;
use App\Service\NotFoundHandler\NotFoundHandler;
use Slim\Flash\Messages;
use Slim\Views\PhpRenderer;
use Vaalyn\AuthenticationService\Authentication;
use Vaalyn\AuthenticationService\Middleware\AuthenticationMiddleware;
use Vaalyn\AuthorizationService\Authorization;
use Vaalyn\AuthorizationService\Middleware\AuthorizationMiddleware;
use Vaalyn\MenuBuilderService\MenuBuilder;
use Vaalyn\MenuBuilderService\Middleware\MenuMiddleware;
use Vaalyn\PluginService\PluginLoader;
use Vaalyn\SessionService\Session;

$app          = new \Slim\App(require_once __DIR__ . '/../config/config.php');
$container    = $app->getContainer();
$pluginLoader = new PluginLoader();

if (file_exists(__DIR__ . '/../config/plugins.php')) {
	$plugins = require_once __DIR__ . '/../config/plugins.php';

	foreach ($plugins as $plugin) {
		$pluginLoader->registerPlugin(new $plugin);
	}
}

$pluginLoader->loadPlugins($container);

$container['session']         = (new Session($container->config['session']))->start();
$container['database']        = EloquentFactory::create($container->config['database']);
$container['authorization']   = new Authorization($container);
$container['authentication']  = new Authentication($container, $container->database);
$container['errorHandler']    = new ErrorHandler();
$container['flashMessages']   = new Messages();
$container['menuBuilder']     = new MenuBuilder($container->router, $container->authentication, $container->authorization);
$container['notFoundHandler'] = new NotFoundHandler();
$container['phpErrorHandler'] = new ErrorHandler();
$container['renderer']        = new PhpRenderer($container->config['template']['path']);

$pluginLoader->registerPluginServices($container);

$pluginLoader->registerPluginMiddlewares($app, $container);

$app->add(new MenuMiddleware($container));
$app->add(new AuthorizationMiddleware($container));
$app->add(new AuthenticationMiddleware($container));
$app->add(new RKA\Middleware\IpAddress(false, []));

require_once __DIR__ . '/../config/routes.php';

$pluginLoader->registerPluginRoutes($app, $container);

$app->run();
