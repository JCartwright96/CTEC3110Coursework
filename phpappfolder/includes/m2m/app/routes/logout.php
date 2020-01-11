<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/logout', function(Request $request, Response $response)
{
    $session = new \RKA\Session();

    $session->set('logged', false);
    $session->set('auto_id', null);
    $session->set('user_name', null);



    $home_link = $this->router->pathFor("homepage");
    $login_link = $this->router->pathFor('login');
    $logout_link = $this->router->pathFor('logout');
    $messages_link = $this->router->pathFor('messages');
    $register_link = $this->router->pathFor('registeruserform');

    $this->logger->info('Logout page deployed');

    return $this->view->render($response,
        'login.html.twig',
        [
            'css_path' => CSS_PATH,
            'app_url' => APP_URL,
            'home_link' => $home_link,
            'messages_link' => $messages_link,
            'login_link' => $login_link,
            'register_link' => $register_link,
            'logout_link' => $logout_link,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'action' => 'loginvalidation',
            'page_title' => 'Login',
        ]);

})->setName('logout');