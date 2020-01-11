<?php
/**
 * registeruser.php
 *
 * calculate the result
 *
 * produces a result according to the user entered values and calculation type
 *
 * Author: CF Ingrams
 * Email: <clinton@cfing.co.uk>
 * Date: 22/10/2015
 *
 * @author CF Ingrams <clinton@cfing.co.uk>
 */

use Slim\Http\Request;
use Slim\Http\Response;

//$messages_link = $this->router->pathFor('login');
$app->post(
    '/registeruser',
    function(Request $request, Response $response) use ($app)
    {
        $login_link = $this->router->pathFor('login');
        $tainted_parameters = $request->getParsedBody();
        $cleaned_parameters = cleanupParameters($app, $tainted_parameters);


        $hashed_password = hash_password($app, $cleaned_parameters['password']);
        $messages_link = $this->router->pathFor('messages');
        $register_link = $this->router->pathFor('registeruserform');

        //Check if user already exists
        $check = checkUser($app, $cleaned_parameters);

        if(strlen($cleaned_parameters['password']) < 6 || strlen($cleaned_parameters['password']) > 32) {
            $session = new \RKA\Session();
            $session->set('register_data', $cleaned_parameters);

            $this->flash->addMessage('info', 'Password must be between 6 and 32 characters');
            return $response->withRedirect($register_link);
        }

        if(strlen($cleaned_parameters['sanitised_username']) < 1) {
            $session = new \RKA\Session();
            $session->set('register_data', $cleaned_parameters);

            $this->flash->addMessage('info', 'Name cannot be empty');
            return $response->withRedirect($register_link);
        }

        if(strlen($cleaned_parameters['sanitised_email']) < 1) {
            $session = new \RKA\Session();
            $session->set('register_data', $cleaned_parameters);

            $this->flash->addMessage('info', 'Email cannot be empty');
            return $response->withRedirect($register_link);
        }

        if ($check == false) {

            unset($cleaned_parameters['password']);
            $session = new \RKA\Session();
            $session->set('register_data', $cleaned_parameters);

            $this->logger->warning('Register form - users already exists');
            $this->flash->addMessage('info', 'A user with that email address already exists!');
            return $response->withRedirect($login_link);
        }

        $storage_result = storeUserDetails($app, $cleaned_parameters, $hashed_password);


        if ($storage_result != false) {
            $this->logger->info('User was successfully stored in the database');

            $session = new \RKA\Session();
            $session->set('logged', true);
            $session->set('auto_id', $storage_result->getId());
            $session->set('user_name', $storage_result->getUserName());

            $this->flash->addMessage('info', 'User Successfully Registered & Logged In!');

            return $response->withRedirect($messages_link);
        } else {
            $this->logger->error('Problem when saving user details to the database');
        }

        $messages_link = $this->router->pathFor('messages');
        $login_link = $this->router->pathFor('login');
        $register_link = $this->router->pathFor('registeruserform');


        $html_output =  $this->view->render($response,
            'homepage.html.twig',
            [
                'css_path' => CSS_PATH,
                'app_url' => APP_URL,
                'messages_link' => $messages_link,
                'login_link' => $login_link,
                'register_link' => $register_link,
                'landing_page' => $_SERVER["SCRIPT_NAME"]
            ]);

        return $html_output;
    });


/**
 *
 * Uses the validator class to sanitize the user input.
 *
 * @param $app
 * @param $tainted_parameters
 * @return array
 */
function cleanupParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validator = $app->getContainer()->get('validator');

    $tainted_username = $tainted_parameters['username'];
    $tainted_email = $tainted_parameters['email'];
    $tainted_phone_number = $tainted_parameters['phone_number'];

    $cleaned_parameters['password'] = $tainted_parameters['password'];
    $cleaned_parameters['sanitised_username'] = $validator->sanitiseString($tainted_username);
    $cleaned_parameters['sanitised_phone_number'] = $validator->sanitiseString($tainted_phone_number);
    $cleaned_parameters['sanitised_email'] = $validator->sanitiseEmail($tainted_email);
    return $cleaned_parameters;
}


/**
 * Uses the Bcrypt library to create password hash from the users input.
 *
 * @param $app
 * @param $password_to_hash
 * @return string
 */
function hash_password($app, $password_to_hash): string
{
    $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');
    $hashed_password = $bcrypt_wrapper->createHashedPassword($password_to_hash);
    return $hashed_password;
}

/**
 *
 * Checks that an existing user with the same email address doesn't exist.
 *
 * @param $app
 * @param array $cleaned_parameters
 * @return bool
 */
function checkUser($app, array $cleaned_parameters)
{
    try {
        $doctrine = $app->getContainer()->get('db');
        $user = $doctrine->getRepository(\M2m\Entity\User::Class)->findOneByEmail($cleaned_parameters['sanitised_email']);

        if ($user == null) {
            return true;
        }
        return false;
    } catch (Exception $e) {
        $this->logger->error('Could not check user');
        return false;
    }
}

function storeUserDetails($app, array $cleaned_parameters, string $hashed_password)
{
    try {
        $user = new \M2m\Entity\User();
        $user->setUserName($cleaned_parameters['sanitised_username']);
        $user->setEmail($cleaned_parameters['sanitised_email']);
        $user->setPassword($hashed_password);
        $user->setPhone($cleaned_parameters['sanitised_phone_number']);

        $doctrine = $app->getContainer()->get('db');
        $doctrine->persist($user);
        $doctrine->flush();

        return $user;
    } catch (Exception $e) {
        $logger = $app->getContainer()->get('logger');
        $logger->error('Could not store user details');
        $store_result = false;
    }

    return $store_result;
}