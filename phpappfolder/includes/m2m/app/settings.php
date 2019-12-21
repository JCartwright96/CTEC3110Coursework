<?php
$app_url = dirname($_SERVER['SERVER_NAME']);
$css_path = $app_url . '/css/main.css';
define('CSS_PATH', $css_path);
define('APP_URL', $app_url);

$wsdl = 'https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl';
define('WSDL', $wsdl);
ini_set("xdebug.overload_var_dump", "off");


$settings = [
    "settings" => [
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'mode' => 'development',
        'debug' => true,
        'view' => [
            'template_path' => __DIR__ . '/templates/',
            'twig' => [
                'cache' => false,
                'debug' => false,
                'auto_reload' => true,
            ],
            ],
    ],
    'doctrine_settings' => [
        'driver' => 'pdo_mysql',
        'host' => '127.0.0.1',
        'dbname' => 'm2m',
        'port' => '3306',
        'user' => 'm2m',
        'password' => 'm2m',
        'charset' => 'utf8mb4'
    ],
];

return $settings;
