<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/registeruserform', function(Request $request, Response $response)
{
    $login_link = $this->router->pathFor('login');
    $messages_link = $this->router->pathFor('messages');
    $register_link = $this->router->pathFor('registeruserform');
    $logout_link = $this->router->pathFor('logout');


    return $this->view->render($response,
        'registeruserform.html.twig',
        [
            'css_path' => CSS_PATH,
            'app_url' => APP_URL,
            'messages_link' => $messages_link,
            'login_link' => $login_link,
            'register_link' => $register_link,
            'logout_link' => $logout_link,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'action' => 'registeruser',
            'initial_input_box_value' => null,
            'page_title' => 'Registration'
        ]);
})->setName('registeruserform');
