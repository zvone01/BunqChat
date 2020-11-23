<?php

declare(strict_types=1);
use Slim\App;
use App\Application\Middleware\ExampleAfterMiddleware;
use App\Application\Middleware\ExampleBeforeMiddleware;

return function(App $app) {

    // Add global Middleware

    $settings = $app->getContainer()->get('settings');
    
    $app->addErrorMiddleware($settings['displayErrorDetails'],$settings['logErrorDetails'],$settings['logErrors']);

};