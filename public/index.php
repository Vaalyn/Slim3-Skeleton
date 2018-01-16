<?php
	session_start();

	require '../vendor/autoload.php';

	use Service\ErrorHandler\ErrorHandler;
	use Service\Factory\Eloquent\EloquentFactory;
	use Slim\Views\PhpRenderer;

	$app = new \Slim\App(require_once __DIR__ . '/../config/config.php');

	$container                           = $app->getContainer();
	$container['auth']                   = new \Service\Auth\Auth($container);
	$container['database']               = EloquentFactory::create($container->config['database']);
	$container['errorHandler']           = new ErrorHandler();
	$container['flash']                  = new \Slim\Flash\Messages();
	$container['phpErrorHandler']        = new ErrorHandler();
	$container['renderer']               = new PhpRenderer('../template');

	$app->add(new RKA\Middleware\IpAddress(false, []));
	$app->add(new Middleware\Auth\AuthMiddleware($container));

	require_once '../config/routes.php';

	$app->run();
?>
