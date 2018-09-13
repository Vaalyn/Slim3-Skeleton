<?php

declare(strict_types = 1);

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
use App\Service\Plugin\PluginLoader;
use App\Service\Session\Session;
use Slim\Views\PhpRenderer;

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
$container['authorization']   = new Authorization($container);
$container['authentication']  = new Authentication($container);
$container['database']        = EloquentFactory::create($container->config['database']);
$container['errorHandler']    = new ErrorHandler();
$container['flashMessages']   = new \Slim\Flash\Messages();
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
