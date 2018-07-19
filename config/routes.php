<?php

use App\Routes\Api;
use App\Routes\Frontend;

$app->group('/api', function() {
	$this->get('/ping', Api\PingController::class)->setName('api.ping');
});

$app->get('/dashboard', Frontend\DashboardController::class)->setName('dashboard');

$app->get('/login', Frontend\LoginController::class . ':getLoginAction')->setName('login');
$app->post('/login', Frontend\LoginController::class . ':loginAction')->setName('post.login');

$app->get('/logout', Frontend\LogoutController::class)->setName('logout');
