<?php
/**
 * Created by PhpStorm.
 * User: slim
 * Date: 24/10/17
 * Time: 10:01
 */

namespace M2m;

class MessageDetailsModel
{

    public function getCompanySymbols()
    {
        $company_symbols = [];
        $query_string = $this->sql_queries->getAllCompanySymbols();
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);

        $this->database_wrapper->makeDatabaseConnection();

        $this->database_wrapper->safeQuery($query_string);

        $number_of_company_symbols = $this->database_wrapper->countRows();
        if ($number_of_company_symbols > 0)
        {
            while ($row = $this->database_wrapper->safeFetchArray())
            {
                $company_symbol = $row['stock_company_symbol'];
                $company_name = $row['stock_company_name'];
                $company_symbols[$company_symbol] = $company_name . ' (' . $company_symbol . ')';
            }
        }
        else
        {
            $company_symbols[0] = 'No companies found';
        }
        return $company_symbols;
    }


    public function getCompanyStockData($validated_company_symbol)
    {
        $company_details = [];
        $stock_company_name = '';
        $company_stock_values_list = [];

        $query_string = $this->sql_queries->getCompanyStockData();
        $query_parameters = [':stock_company_symbol' => $validated_company_symbol];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);

        $this->database_wrapper->makeDatabaseConnection();

        $this->database_wrapper->safeQuery($query_string, $query_parameters);

        $number_of_data_sets = $this->database_wrapper->countRows();

        if ($number_of_data_sets > 0)
        {
            $lcv = 0;
            while ($row = $this->database_wrapper->safeFetchArray())
            {
                $stock_company_name = $row['stock_company_name'];
                $company_stock_values_list[$lcv]['date'] = $row['stock_date'];
                $company_stock_values_list[$lcv]['time'] = $row['stock_time'];
                $company_stock_values_list[$lcv++]['value'] = $row['stock_last_value'];
            }
        }
        else
        {
            $company_details[0] = 'No company details found';
        }

        $company_details['$company_symbol'] = $validated_company_symbol;
        $company_details['company-name'] = $stock_company_name;
        $company_details['company-data-sets'] = $number_of_data_sets;
        $company_details['company-retrieved-data'] = $company_stock_values_list;

        return $company_details;
    }

}