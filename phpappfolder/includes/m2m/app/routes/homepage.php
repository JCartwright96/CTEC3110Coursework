<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

$app->get('/', function(Request $request, Response $response) use ($app)
{
    $messages_link = $this->router->pathFor('messages');
    $login_link = $this->router->pathFor('login');
    $logout_link = $this->router->pathFor('logout');
    $register_link = $this->router->pathFor('registeruserform');

    //$logger = $this->get(logger);
    $this->logger->info('Home page deployed');

    return $this->view->render($response,
        'homepage.html.twig',
        [
            'css_path' => CSS_PATH,
            'app_url' => APP_URL,
            'messages_link' => $messages_link,
            'login_link' => $login_link,
            'register_link' => $register_link,
            'logout_link' => $logout_link,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'page_title' => 'Sessions Demonstration',
        ]);
})->setName('homepage');


