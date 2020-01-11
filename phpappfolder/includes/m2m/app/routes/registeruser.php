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
use Doctrine\DBAL\DriverManager;

//$messages_link = $this->router->pathFor('login');
$app->post(
    '/registeruser',
    function(Request $request, Response $response) use ($app)
    {
        $tainted_parameters = $request->getParsedBody();

        $cleaned_parameters = cleanupParameters($app, $tainted_parameters);

        $hashed_password = hash_password($app, $cleaned_parameters['password']);

        $storage_result = storeUserDetails($app, $cleaned_parameters, $hashed_password);
        //FLASH MESSAGE HERE

        $messages_link = $this->router->pathFor('messages');
        $login_link = $this->router->pathFor('login');
        $register_link = $this->router->pathFor('registeruserform');
        $sid = session_id();

        $html_output =  $this->view->render($response,
            'homepage.html.twig',
            [
                'css_path' => CSS_PATH,
                'app_url' => APP_URL,
                'messages_link' => $messages_link,
                'login_link' => $login_link,
                'register_link' => $register_link,
                'landing_page' => $_SERVER["SCRIPT_NAME"],
                'action' => 'storesessiondetails',
                'initial_input_box_value' => null,
                'page_title' => 'Sessions Demonstration',
                'page_heading_1' => 'Sessions Demonstration',
                'page_heading_2' => 'Enter values for storage in a session',
                'page_heading_3' => 'Select the type of session storage to be used',
                'info_text' => 'Your information will be stored in either a session file or in a database',
                'sid_text' => 'Your super secret session SID is ',
                'sid' => $sid,
            ]);

 //       processOutput($app, $html_output);

        return $html_output;
    });

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
 * Uses the Bcrypt library with constants from settings.php to create hashes of the entered password
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


function storeUserDetails($app, array $cleaned_parameters, string $hashed_password): string
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

        $store_result = 'User data was successfully stored into the database';
    } catch (Exception $e) {
        $store_result = 'There appears to have been a problem when saving your details.  Please try again later.';
    }

    return $store_result;
}