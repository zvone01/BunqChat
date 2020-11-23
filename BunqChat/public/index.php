<?php

//for newer PHP 
declare(strict_types=1);

use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$settings =  require __DIR__ .'/../app/settings.php';
$settings($container);


$logger =  require __DIR__ .'/../app/logger.php';
$logger($container);

//Set container on app
AppFactory::setContainer($container);

$app = AppFactory::create();


$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

$route =  require __DIR__ .'/../app/route.php';
$route($app);


//Run app
$app->run();