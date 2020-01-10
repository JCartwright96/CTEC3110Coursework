<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Doctrine\DBAL\DriverManager;

$app->get('/', function(Request $request, Response $response) use ($app)
{
    $current_settings = getCurrentSettingsDetails($app);
    $current_settings = $current_settings[0];


    $message_id = $current_settings['message_id'];
    $phone_number = $current_settings['phone_number'];
    $switch_01 = $current_settings['switch_01'];
    $switch_02 = $current_settings['switch_02'];
    $switch_03 = $current_settings['switch_03'];
    $switch_04 = $current_settings['switch_04'];
    $fan = $current_settings['fan'];
    $heater = $current_settings['heater'];
    $keypad = $current_settings['keypad'];
//    $receivedtime = $current_settings['receivedtime'];
    $timestamp = $current_settings['timestamp'];
    $messages_link = $this->router->pathFor('messages');
    $login_link = $this->router->pathFor('login');
    $logout_link = $this->router->pathFor('logout');
    $register_link = $this->router->pathFor('registeruserform');


    return $this->view->render($response,
        'homepage.html.twig',
        [
            'css_path' => CSS_PATH,
            'app_url' => APP_URL,
            'messages_link' => $messages_link,
            'login_link' => $login_link,
            'register_link' => $register_link,
            'logout_link' => $logout_link,
            'currentsettings' => $current_settings,
            'message.message_id' => $message_id,
            'message.phone_number' => $phone_number,
            'message.switch_01' => $switch_01,
            'message.switch_02' => $switch_02,
            'message.switch_03' => $switch_03,
            'message.switch_04' => $switch_04,
            'message.fan' => $fan,
            'message.heater' => $heater,
            'message.keypad' => $keypad,
//            'message.receivedtime' => $receivedtime,
            'message.timestamp' => $timestamp,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'action' => 'storesessiondetails',
            'initial_input_box_value' => null,
            'page_title' => 'Sessions Demonstration',
            'page_heading_1' => 'Sessions Demonstration',
            'page_heading_2' => 'Enter values for storage in a session',
            'page_heading_3' => 'Select the type of session storage to be used',
            'info_text' => 'Your information will be stored in either a session file or in a database',
        ]);
})->setName('homepage');


function getCurrentSettingsDetails($app) {
    $messages = [];

    $database_connection_settings = $app->getContainer()->get('doctrine_settings');
    $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
    $database_connection = DriverManager::getConnection($database_connection_settings);

    $messages = $doctrine_queries::queryGetLatestMessageData($database_connection);

    return $messages;
}
