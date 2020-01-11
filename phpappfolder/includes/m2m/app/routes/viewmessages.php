<?php


use Slim\Http\Request;
use Slim\Http\Response;
use Doctrine\DBAL\DriverManager;

$app->get('/messages', function(Request $request, Response $response) use ($app)
{
    $flash = $this->flash->getMessages();

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
                        $message_array['18-3110-AF']['phone'] = (string)$message->sourcemsisdn;
                        $message_array['18-3110-AF']['time'] = (string)$message->receivedtime;

                        $cleaned_parameters = cleanupMessageData($app, $message_array['18-3110-AF']);

                        storeMessageDetails($app, $cleaned_parameters);
                    }
                }
            }
    }

    //After any new messages have been stored, get messages from the db.
    $messages = getMessageDetails($app);

    $currentData = getLatestMessageDetails($app);


    $chart_location = createChart($app, $messages);

    $messages_link = $this->router->pathFor('messages');
    $login_link = $this->router->pathFor('login');
    $register_link = $this->router->pathFor('registeruserform');
    $logout_link = $this->router->pathFor('logout');

    $this->logger->info('View messages page deployed');

    return $this->view->render($response,
        'messages.html.twig',
        [
            'css_path' => CSS_PATH,
            'app_url' => APP_URL,
            'messages_link' => $messages_link,
            'login_link' => $login_link,
            'register_link' => $register_link,
            'logout_link' => $logout_link,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'action' => 'storesessiondetails',
            'initial_input_box_value' => null,
            'page_title' => 'Sessions Demonstration',
            'page_heading_1' => 'Sessions Demonstration',
            'page_heading_2' => 'Enter values for storage in a session',
            'page_heading_3' => 'Select the type of session storage to be used',
            'info_text' => 'Your information will be stored in either a session file or in a database',
            'messages' => $messages,
            'currentData' => $currentData,
            'chart' => $chart_location,
            'flash' => $flash,
            'title' => 'M2m Messages'
        ]);
})->setName('messages')->add(new \M2m\Middleware\AuthMiddleware($container));


$app->get('/update-messages', function(Request $request, Response $response) use ($app)
{

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
                    $message_array['18-3110-AF']['phone'] = (string)$message->sourcemsisdn;
                    $message_array['18-3110-AF']['time'] = (string)$message->receivedtime;


                    $cleaned_parameters = cleanupMessageData($app, $message_array['18-3110-AF']);

                    storeMessageDetails($app, $cleaned_parameters);
                }
            }
        }

    }

    $messages = getMessageDetails($app);
    $currentData = getLatestMessageDetails($app);
    $chart_location = createChart($app, $messages);

    $messages_link = $this->router->pathFor('messages');
    $login_link = $this->router->pathFor('login');
    $register_link = $this->router->pathFor('registeruserform');
    $logout_link = $this->router->pathFor('logout');
    return $this->view->render($response,
        'update-messages.html.twig',
        [
            'css_path' => CSS_PATH,
            'app_url' => APP_URL,
            'messages_link' => $messages_link,
            'login_link' => $login_link,
            'register_link' => $register_link,
            'logout_link' => $logout_link,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'action' => 'storesessiondetails',
            'initial_input_box_value' => null,
            'page_title' => 'Sessions Demonstration',
            'page_heading_1' => 'Sessions Demonstration',
            'page_heading_2' => 'Enter values for storage in a session',
            'page_heading_3' => 'Select the type of session storage to be used',
            'info_text' => 'Your information will be stored in either a session file or in a database',
            'messages' => $messages,
            'currentData' => $currentData,
            'chart' => $chart_location
        ]);
})->setName('messages')->add(new \M2m\Middleware\AuthMiddleware($container));


/**
 * @param $app
 * @return array
 *
 * This is a function to view all the messages on the m2m server through an SOAP call. Messages will not be deleted from the server.
 */
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
                'count' => (int)9999,
                'deviceMsisdn' => '',
                'countryCode' => ''
            ];
        $webservice_value = '';

        $message_data = $soap_wrapper->getSoapData($soap_client, $soap_call_function, $soap_call_parameters, $webservice_value);
    }

    return $message_data;
}

function sendMessage($app, $data)
{
    $message_data = [];

    $soap_wrapper = $app->getContainer()->get('soapWrapper');

    $soap_client = $soap_wrapper->createSoapClient();

    if (is_object($soap_client))
    {
        $soap_call_function = 'sendMessage';
        $soap_call_parameters =
            [
                'username' => '19_17234645',
                'password' => 'Password1234',
                'deviceMsisdn' => '',
                'countryCode' => ''
            ];
        $webservice_value = '';

        $message_data = $soap_wrapper->getSoapData($soap_client, $soap_call_function, $soap_call_parameters, $webservice_value, $data);
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
function storeMessageDetails($app, array $cleaned_parameters)
{
    try {
        $message = new \M2m\Entity\Messages();
        $message->setPhone($cleaned_parameters['phone']);
        $message->setSwitch01($cleaned_parameters['switch_1']);
        $message->setSwitch02($cleaned_parameters['switch_2']);
        $message->setSwitch03($cleaned_parameters['switch_3']);
        $message->setSwitch04($cleaned_parameters['switch_4']);
        $message->setFan($cleaned_parameters['fan']);
        $message->setHeater($cleaned_parameters['heater']);
        $message->setKeypad($cleaned_parameters['keypad']);
        $message->setReceivedTime($cleaned_parameters['received_time']);

        $doctrine = $app->getContainer()->get('db');
        $doctrine->persist($message);
        $doctrine->flush();

        $notify_message_data = [
            'phone_number' => $cleaned_parameters['phone'],
            'message'   => 'Your message has been received and downloaded onto the M2M web application.'
        ];

        sendMessage($app, $notify_message_data);

        return $message;
    } catch (Exception $e) {
        $this->logger->error('Could not store message details');
        return false;
    }
}

function getMessageDetails($app) {
    try {
        $doctrine = $app->getContainer()->get('db');
        //currenly gets the latest 20 messages.
        $messages = $doctrine->getRepository(\M2m\Entity\Messages::class)->findBy(array(), array('id' => 'DESC'),20);
        return $messages;
    } catch (Exception $e) {
        $this->logger->error('Could not get message details');
        return false;
    }
}

function getLatestMessageDetails($app) {
    try {
        $doctrine = $app->getContainer()->get('db');
        //currenly gets the latest 20 messages.
        $messages = $doctrine->getRepository(\M2m\Entity\Messages::class)->findBy(array(),array('id'=>'DESC'),1);

        return $messages;
    } catch (Exception $e) {
        $this->logger->error('Could not get latest message details');
        return false;
    }
}

/**
 *  Takes the tainted input from the $message_array
 *  Uses the corresponding validator and returns each parameter in a cleaned array. 
 * 
 * @param $app
 * @param $tainted_parameters $message_array
 * @return array $cleaned parameters
 */

function cleanupMessageData($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validator = $app->getContainer()->get('validator');

    $tainted_phone_number = $tainted_parameters['phone'];
    $tainted_s1 = $tainted_parameters['switch_1'];
    $tainted_s2 = $tainted_parameters['switch_2'];
    $tainted_s3 = $tainted_parameters['switch_3'];
    $tainted_s4 = $tainted_parameters['switch_4'];
    $tainted_fan = $tainted_parameters['fan'];
    $tainted_heater = $tainted_parameters['heater'];
    $tainted_keypad = $tainted_parameters['keypad'];

    // Cleaning each of the tainted parameters, and adding them to the cleaned array
    $cleaned_parameters['phone'] = $validator->validateInt($tainted_phone_number);
    $cleaned_parameters['switch_1'] = (int)$validator->validateBool($tainted_s1);
    $cleaned_parameters['switch_2'] = (int)$validator->validateBool($tainted_s2);
    $cleaned_parameters['switch_3'] = (int)$validator->validateBool($tainted_s3);
    $cleaned_parameters['switch_4'] = (int)$validator->validateBool($tainted_s4);
    $cleaned_parameters['fan'] = $validator->sanitiseString($tainted_fan);
    $cleaned_parameters['heater'] = $validator->validateInt($tainted_heater);
    $cleaned_parameters['keypad'] = $validator->validateInt($tainted_keypad);
    $cleaned_parameters['received_time'] = $validator->validateDateTime($tainted_parameters['time']);

    return $cleaned_parameters;
}

function createChart($app, array $temperature_data)
{
    require_once 'libchart/classes/libchart.php';

    $temperatureDetailsChartModel = $app->getContainer()->get('temperatureDetailsChartModel');

    $temperatureDetailsChartModel->setStoredTemperatureData($temperature_data);
    $temperatureDetailsChartModel->createLineChart();
    $chart_details = $temperatureDetailsChartModel->getLineChartDetails();

    return $chart_details;
}

function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}
