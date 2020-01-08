<?php
use Slim\Http\Request;
use Slim\Http\Response;
use Doctrine\DBAL\DriverManager;


$app->post(
    '/loginvalidation',
    function(Request $request, Response $response) use ($app)
    {
        $tainted_parameters = $request->getParsedBody();

        $cleaned_parameters = cleanupLoginParameters($app, $tainted_parameters);

        $validated_login = validateLogin($app, $cleaned_parameters);

        $register_link = $this->router->pathFor("registeruserform");
        $home_link = $this->router->pathFor("homepage");
        $messages_link = $this->router->pathFor('messages');
        $login_link = $this->router->pathFor('login');
        $sid = session_id();

        if($validated_login) {
            $html_output = $this->view->render($response,
                'homepage.html.twig',
                [
                    'css_path' => CSS_PATH,
                    'app_url' => APP_URL,
                    'home_link' => $home_link,
                    'messages_link' => $messages_link,
                    'login_link' => $login_link,
                    'register_link' => $register_link,
                    'landing_page' => $_SERVER["SCRIPT_NAME"],
                ]);
        }

        else {
            $html_output = $this->view->render($response,
                'login.html.twig',
                [
                    'css_path' => CSS_PATH,
                    'app_url' => APP_URL,
                    'home_link' => $home_link,
                    'messages_link' => $messages_link,
                    'login_link' => $login_link,
                    'register_link' => $register_link,
                    'landing_page' => $_SERVER["SCRIPT_NAME"],
                    'page_title' => 'Homepage',
                    'page_heading_1' => 'Login Form',
                    'page_heading_2' => 'Complete the Login form below',
                    'error_message' => "Incorrect Login Credentials",
                ]);
        }

        //       processOutput($app, $html_output);

        return $html_output;
    });

function cleanupLoginParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validator = $app->getContainer()->get('validator');

    $tainted_username = $tainted_parameters['username'];
    $tainted_password = $tainted_parameters['password'];

    if (isset ($tainted_password)) {
        $cleaned_parameters['password'] = $tainted_parameters['password'];
    }
    if (isset($tainted_username)) {
        $cleaned_parameters['sanitised_username'] = $validator->sanitiseString($tainted_username);
    }
    return $cleaned_parameters;
}

function hash_login_password($app, $password_to_hash): string
    {
        $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');
        $hashed_password = $bcrypt_wrapper->createHashedPassword($password_to_hash);
        return $hashed_password;
    }

function validateLogin($app, $cleaned_parameters) {

    $database_connection_settings = $app->getContainer()->get('doctrine_settings');
    $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
    $database_connection = DriverManager::getConnection($database_connection_settings);

    $result = $doctrine_queries::queryCheckUserExists($database_connection, $cleaned_parameters);

    $input_password = $cleaned_parameters['password'];
    $verify_password = $result['password'];

    if(password_verify($input_password, $verify_password)) {
        return true;
    }
    else return false;
}
