<?php

declare(strict_types = 1);

use App\Routes\Api;
use App\Routes\Frontend;

$app->group('/api', function() {
	$this->get('/ping', Api\PingController::class)->setName('api.ping');
	$this->post('/login', Api\LoginController::class . ':loginAction')->setName('api.login');
});

$app->get('/dashboard', Frontend\DashboardController::class)->setName('dashboard');

$app->get('/login', Frontend\LoginController::class . ':getLoginAction')->setName('login');
$app->post('/login', Frontend\LoginController::class . ':loginAction')->setName('login.action');

$app->get('/logout', Frontend\LogoutController::class)->setName('logout');
