<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/registeruserform', function(Request $request, Response $response)
{
    $sid = session_id();

    $login_link = $this->router->pathFor('login');
    $messages_link = $this->router->pathFor('messages');
    $register_link = $this->router->pathFor('registeruserform');


    return $this->view->render($response,
        'registeruserform.html.twig',
        [
            'css_path' => CSS_PATH,
            'app_url' => APP_URL,
            'messages_link' => $messages_link,
            'login_link' => $login_link,
            'register_link' => $register_link,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'action' => 'registeruser',
            'initial_input_box_value' => null,
            'page_title' => 'Homepage',
            'page_heading_1' => 'Registration Form',
            'page_heading_2' => 'Complete the Registration form below',
            'sid' => $sid,
        ]);
})->setName('registeruserform');
