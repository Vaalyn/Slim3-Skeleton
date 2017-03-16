<?php
	require '../vendor/autoload.php';
	
	// Include all db classes
	foreach (glob('../db/*.php') as $DBFile) {
		include_once $DBFile;
	}
	
	// Include all entities
	foreach (glob('../entity/*.php') as $EntityFile) {
		include_once $EntityFile;
	}
	
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;
	use Slim\Views\PhpRenderer;
	
	$app 						= new \Slim\App(require __DIR__ . '/../config/config.php');
	$container 					= $app->getContainer();
	$container['renderer'] 		= new PhpRenderer('../template');
	$container['database']		= new SQL_Manager($container->get('config')['database']);
	
	$container['errorHandler'] = function ($container) {
		return function ($request, $response, $exception) use ($container) {
			$message = 'Beim Verarbeiten der Anfrage ist ein Fehler aufgetreten.';
			if ($container->get('settings')['displayErrorDetails']) {
				$message .= '<pre>' . $exception . '</pre>';
			}
			return $container['response']->withStatus(500)
				->withHeader('Content-Type', 'text/html')
				->write($message);
		};
	};
	
	$container['phpErrorHandler'] = function ($container) {
		return function ($request, $response, $error) use ($container) {
			$message = 'Beim Verarbeiten der Anfrage ist ein Fehler aufgetreten.';
			if ($container->get('settings')['displayErrorDetails']) {
				$message .= '<pre>' . $exception . '</pre>';
			}
			return $container['response']
				->withStatus(500)
				->withHeader('Content-Type', 'text/html')
				->write($message);
		};
	};
	
	// Include all Middlewares
	foreach (glob('../middleware/*Middleware.php') as $MiddlewareFile) {
		include_once $MiddlewareFile;
	}
	
	// Include all API Route Controllers
	foreach (glob('../routes/api/*Controller.php') as $ApiControllerFile) {
		include_once $ApiControllerFile;
	}
	
	// Include all Frontend Route Controllers
	foreach (glob('../routes/frontend/*Controller.php') as $FrontendControllerFile) {
		include_once $FrontendControllerFile;
	}
	
	$app->run();
?>