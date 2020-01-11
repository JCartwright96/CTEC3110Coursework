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
        $logout_link = $this->router->pathFor('logout');


        if($validated_login)
        {
            $session = new \RKA\Session();
            $session->set('logged', true);
            $session->set('auto_id', $validated_login->getId());
            $session->set('user_name', $validated_login->getUserName());

            return $response->withRedirect($messages_link);
        }
        else
            {
            $html_output = $this->view->render($response,
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
                    'page_title' => 'Login'
                ]);
        }

        return $html_output;
    });

function cleanupLoginParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validator = $app->getContainer()->get('validator');

    $tainted_email = $tainted_parameters['email'];
    $tainted_password = $tainted_parameters['password'];

    if (isset ($tainted_password)) {
        $cleaned_parameters['password'] = $tainted_password;
    }
    if (isset($tainted_email)) {
        $cleaned_parameters['sanitised_email'] = $validator->sanitiseString($tainted_email);
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
    try {
        $doctrine = $app->getContainer()->get('db');
        $user = $doctrine->getRepository(\M2m\Entity\User::Class)->findOneByEmail($cleaned_parameters['sanitised_email']);

        if (!empty($user)) {
            $verify_password = $user->getPassword();
            $input_password = $cleaned_parameters['password'];

            if(password_verify($input_password, $verify_password)) {

                return $user;
            }
        }
        return false;
    } catch (Exception $e) {
        $store_result = 'There appears to have been a problem when saving your details.  Please try again later.';
    }
}
