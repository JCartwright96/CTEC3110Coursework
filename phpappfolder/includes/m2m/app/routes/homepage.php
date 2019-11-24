<?php
/**
 * homepage.php
 *
 * display the check primes application homepage
 *
 * allows the user to enter a value for testing if prime
 *
 * Author: CF Ingrams
 * Email: <clinton@cfing.co.uk>
 * Date: 18/10/2015
 *
 * @author CF Ingrams <clinton@cfing.co.uk>
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function(Request $request, Response $response)
{
    $sid = session_id();

    return $this->view->render($response,
        'homepage.html.twig',
        [
            'css_path' => CSS_PATH,
            'app_url' => APP_URL,
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
})->setName('homepage');
