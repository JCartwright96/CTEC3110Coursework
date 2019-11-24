<?php
$app_url = dirname($_SERVER['SCRIPT_NAME']);
$css_path = $app_url . '/css/m2m.css';
define('CSS_PATH', $css_path);
define('APP_URL', $app_url);

return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,
        // View settings
        'view' => [
            'template_path' => __DIR__ . '/templates/',
            'twig' => [
                'cache' => false,
                'debug' => true,
                'auto_reload' => true,
            ],
        ],
        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../log/app.log',
        ],
    ],
];