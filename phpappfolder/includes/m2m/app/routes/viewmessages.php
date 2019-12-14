<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Doctrine\DBAL\DriverManager;

$app->get('/messages', function(Request $request, Response $response) use ($app)
{
    $sid = session_id();

    $messages = peekMessages($app);

    foreach ($messages as $message) {
        libxml_use_internal_errors(true);


            $message = simplexml_load_string($message);
            if (isset($message->message)) {
                if (isJson($message->message)) {
                    $message_array = (string)$message->message;
                    $message_array = json_decode($message_array, true);
                    if( isset( $message_array['18-3110-AF'] ) ){
                        //THIS IS OUR MESSAGE
                        $message_array['18-3110-AF']['source'] = (string)$message->sourcemsisdn;
                        $message_array['18-3110-AF']['time'] = (string)$message->receivedtime;

                        var_dump($message_array);


                        //storeMessageDetails($app, $cleaned_parameters);
                    }
                }
            }

    }


    //$storage_result = storeMessageDetails($app, $cleaned_parameters, $hashed_password);


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
                'count' => (int)100,
                'deviceMsisdn' => '',
                'countryCode' => ''
            ];
        $webservice_value = '';

        $message_data = $soap_wrapper->getSoapData($soap_client, $soap_call_function, $soap_call_parameters, $webservice_value);
    }

    return $message_data;
}

/**
 *
 * Uses the Doctrine QueryBuilder API to store the sanitised user data.
 *
 * @param $app
 * @param array $cleaned_parameters
 * @param string $hashed_password
 * @return array
 * @throws \Doctrine\DBAL\DBALException
 */
function storeMessageDetails($app, array $cleaned_parameters): string
{
    $storage_result = [];
    $store_result = '';

    //var_dump('asd');
    //die();

    $database_connection_settings = $app->getContainer()->get('doctrine_settings');
    //var_dump($database_connection_settings);
    //die();
    $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
    $database_connection = DriverManager::getConnection($database_connection_settings);

    //$queryBuilder = $database_connection->createQueryBuilder();

    //$storage_result = $doctrine_queries::queryStoreMessageData($queryBuilder, $cleaned_parameters);
    $storage_result = 1;
    if ($storage_result['outcome'] == 1)
    {
        $store_result = 'User data was successfully stored using the SQL query: ' . $storage_result['sql_query'];
    }
    else
    {
        $store_result = 'There appears to have been a problem when saving your details.  Please try again later.';

    }
    return $store_result;
}

function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}