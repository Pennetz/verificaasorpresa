<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'db' => [
                    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
                    'port' => (int) ($_ENV['DB_PORT'] ?? 3306),
                    'dbname' => $_ENV['DB_NAME'] ?? 'TestASorpresa',
                    'user' => $_ENV['DB_USER'] ?? 'utente_phpmyadmin',
                    'pass' => $_ENV['DB_PASS'] ?? '86FbuSRrfWRkgWh',
                    'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
                ],
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
            ]);
        }
    ]);
};
