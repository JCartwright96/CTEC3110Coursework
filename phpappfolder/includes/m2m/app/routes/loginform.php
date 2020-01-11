<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/login', function(Request $request, Response $response)
{
    $flash = $this->flash->getMessages();

    $home_link = $this->router->pathFor("homepage");
    $login_link = $this->router->pathFor('login');
    $messages_link = $this->router->pathFor('messages');
    $logout_link = $this->router->pathFor('logout');
    $register_link = $this->router->pathFor('registeruserform');

    $this->logger->info('Login page deployed');

    return $this->view->render($response,
        'login.html.twig',
        [
            'css_path' => CSS_PATH,
            'app_url' => APP_URL,
            'home_link' => $home_link,
            'messages_link' => $messages_link,
            'login_link' => $login_link,
            'logout_link' => $logout_link,
            'register_link' => $register_link,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'action' => 'loginvalidation',
            'initial_input_box_value' => null,
            'page_title' => 'Homepage',
            'page_heading_1' => 'Login Form',
            'page_heading_2' => 'Complete the Login form below',
            'flash' => $flash
        ]);

})->setName('login');