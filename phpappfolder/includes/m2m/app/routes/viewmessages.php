<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/messages', function(Request $request, Response $response) use ($app)
{
    $sid = session_id();

    $messages = peekMessages($app);

    foreach ($messages as $message) {
        $message = simplexml_load_string($message);
        var_dump($message);
    }



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
})->setName('messages');


function peekMessages($app)
{
    $message_data = [];

    $soap_wrapper = $app->getContainer()->get('soapWrapper');

    $soap_client = $soap_wrapper->createSoapClient();

    if (is_object($soap_client))
    {
        $soap_call_function = 'peekMessages';
        $soap_call_parameters =
            [
                'username' => '19_17234645',
                'password' => 'Password1234',
                'count' => (int)20,
                'deviceMsisdn' => '',
                'countryCode' => ''
            ];
        $webservice_value = '';

        $message_data = $soap_wrapper->getSoapData($soap_client, $soap_call_function, $soap_call_parameters, $webservice_value);
    }

    return $message_data;
}