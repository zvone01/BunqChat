<?php

declare(strict_types=1);

use DI\Container;
use Monolog\Logger;

return function(Container $container) {
        // Global Settings Object
        $container->set('settings', function(){
            return  [
                'name' => 'Bunq chat application',
                'displayErrorDetails' => true,
                'logErrorDetails' => true,
                'logErrors' => true,
                'logger' => [
                    'name' => 'slim-app',
                    'path' =>  __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'database' => [
                    'driver' => 'sqlite',
                    'database' => './db/database.db',
                ],
            ];
        });
};