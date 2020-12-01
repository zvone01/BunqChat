<?php

declare(strict_types=1);
use Slim\App;
use App\Application\Middleware\ExampleAfterMiddleware;
use App\Application\Middleware\ExampleBeforeMiddleware;
use App\Application\Middleware\JsonBodyParserMiddleware;

return function(App $app) {

    // Add global Middleware

    $settings = $app->getContainer()->get('settings');
    
    $app->addErrorMiddleware($settings['displayErrorDetails'],$settings['logErrorDetails'],$settings['logErrors']);
    //$app->add(JsonBodyParserMiddleware::class);
    //CORS enable
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    
    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);
        return $response
                ->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200')
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Access-Control-Allow-Headers', ' Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
                
    });

};