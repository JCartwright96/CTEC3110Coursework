<?php

namespace M2m;

class SoapWrapper
{

    public function __construct(){}
    public function __destruct(){}

    public function createSoapClient()
    {
        $soap_client_handle = false;
        $soap_client_parameters = array();
        $exception = '';
        $wsdl = WSDL;

        $soap_client_parameters = ['trace' => true, 'exceptions' => true];

        try
        {
            $soap_client_handle = new \SoapClient($wsdl, $soap_client_parameters);
            //var_dump($soap_client_handle->__getFunctions());
            //var_dump($soap_client_handle->__getTypes());
            $soap_server_connection_result = true;
        }
        catch (\SoapFault $exception)
        {
            $soap_client_handle = 'Ooops - something went wrong connecting to the data supplier.  Please try again later';
        }
        return $soap_client_handle;
    }

    public function getSoapData($soap_client, $webservicefunction, $webservice_call_parameters, $webservice_value)
    {
        $soap_call_result = null;
        //$raw_xml = '';

        if ($soap_client)
        {
            try
            {

                //var_dump('d');
                switch($webservicefunction){
                    case 'peekMessages':
                        $webservice_call_result = $soap_client->{$webservicefunction}($webservice_call_parameters['username'], $webservice_call_parameters['password'],$webservice_call_parameters['count']);
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

        //var_dump($data);
        //die();
        //var_dump($raw_xml);
        //die();
        //$LastTradeDateTime = $raw_xml->LastTradeDateTime;
        //var_dump($LastTradeDateTime);
        return $data;
    }
}