<?php

namespace M2m;
/**
 *
 * A soap wrapper class to access the EE M2m account.
 *
 * Class SoapWrapper
 * @package M2m
 */

class SoapWrapper
{

    public function __construct(){}
    public function __destruct(){}

    /**
     *
     * Function to create and return a soap client for M2m.
     *
     * @return \SoapClient|string
     */
    public function createSoapClient()
    {
        $soap_client_parameters = array();
        $wsdl = WSDL;
        $soap_client_parameters = ['trace' => true, 'exceptions' => true];
        try
        {
            $soap_client_handle = new \SoapClient($wsdl, $soap_client_parameters);
        }
        catch (\SoapFault $exception)
        {
            $soap_client_handle = 'Ooops - something went wrong connecting to the data supplier.  Please try again later';
        }
        return $soap_client_handle;
    }


    /**
     *
     * Function to perform soap calls.
     *
     * @param $soap_client
     * @param $webservicefunction
     * @param $webservice_call_parameters
     * @param $webservice_value
     * @param null $data
     * @return null
     */
    public function getSoapData($soap_client, $webservicefunction, $webservice_call_parameters, $webservice_value, $data = null)
    {
        $soap_call_result = null;
        //$raw_xml = '';

        if ($soap_client)
        {
            try
            {
                switch($webservicefunction){
                    case 'peekMessages':
                        $webservice_call_result = $soap_client->{$webservicefunction}($webservice_call_parameters['username'], $webservice_call_parameters['password'],$webservice_call_parameters['count']);
                        break;
                    case 'sendMessage':
                        $webservice_call_result = $soap_client->{$webservicefunction}($webservice_call_parameters['username'], $webservice_call_parameters['password'],$data['phone_number'],$data['message'], 0, 'SMS');
                        break;

                }
                if ($webservice_value != '') {
                    $raw_xml = $webservice_call_result->{$webservice_value};

                } else {
                    $data = $webservice_call_result;
                }
            }
            catch (\SoapFault $exception)
            {
                var_dump($exception);
                $soap_server_get_quote_result = $exception;
            }
        }
        return $data;
    }
}