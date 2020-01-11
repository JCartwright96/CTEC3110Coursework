<?php
$app_url = dirname($_SERVER['SERVER_NAME']);
$css_path = $app_url . '/css/main.css';
define('CSS_PATH', $css_path);
define('APP_URL', $app_url);

$wsdl = 'https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl';
define('WSDL', $wsdl);
ini_set("xdebug.overload_var_dump", "off");
define ('BCRYPT_ALGO', PASSWORD_DEFAULT);
define ('BCRYPT_COST', 12);

$script_filename = $_SERVER["SCRIPT_FILENAME"];
$arr_script_filename = explode('/' , $script_filename, '-1');
$script_path = implode('/', $arr_script_filename) . '/';

define ('LIB_CHART_OUTPUT_PATH', 'media/charts/');
define ('LIB_CHART_FILE_PATH', $script_path);
define ('LIB_CHART_CLASS_PATH', 'libchart/classes/');

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
        'meta' => [
            'entity_path' => [ __DIR__ . '/src/Entity' ],
            'auto_generate_proxies' => true,
            'proxy_dir' => __DIR__ . '/var/cache/proxies',
            'cache' => null,
        ],
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
