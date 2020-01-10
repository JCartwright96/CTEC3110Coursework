<?php


$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(
        $container['settings']['view']['template_path'],
        $container['settings']['view']['twig'],
        [
            //'cache' => 'path/to/cache',
            'debug' => true // This line should enable debug mode
    ]);


    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));
    $view->addExtension(new \Twig\Extension\DebugExtension());


    return $view;
};

$container['soapWrapper'] = function ($container) {
    $retrieve_message_data_model = new \M2m\SoapWrapper();
    return $retrieve_message_data_model;
};

$container['doctrineSqlQueries'] = function ($container) {
    $doctrine_sql_queries = new \M2m\DoctrineSqlQueries();
    return $doctrine_sql_queries;
};

$container['xmlParser'] = function ($container) {
    $model = new \M2m\XmlParser();
    return $model;
};

$container['validator'] = function ($container) {
    $validator = new \M2m\Validator();
    return $validator;
};



$container['bcryptWrapper'] = function ($container) {
    $wrapper = new \M2m\BcryptWrapper();
    return $wrapper;
};
