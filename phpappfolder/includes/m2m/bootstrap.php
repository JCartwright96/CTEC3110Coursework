<?php


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

require 'vendor/autoload.php';

$settings = require __DIR__ . '/app/settings.php';

if (function_exists('xdebug_start_trace'))
{
    xdebug_start_trace();
}

$container = new \Slim\Container($settings);

require __DIR__ . '/app/dependencies.php';

$app = new \Slim\App($container);

$app->add(new \RKA\SessionMiddleware(['name' => 'MySessionName']));

require __DIR__ . '/app/routes.php';

$app->run();

if (function_exists('xdebug_stop_trace'))
{
    xdebug_stop_trace();
}