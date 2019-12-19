<?php
/**
 * class to contain all database access using Doctrine's QueryBulder
 *
 * A QueryBuilder provides an API that is designed for conditionally constructing a DQL query in several steps.
 *
 * It provides a set of classes and methods that is able to programmatically build queries, and also provides
 * a fluent API.
 * This means that you can change between one methodology to the other as you want, or just pick a preferred one.
 *
 * From https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/query-builder.html
 */

namespace M2m;

class DoctrineSqlQueries
{
    public function __construct(){}

    public function __destruct(){}

    public static function queryStoreMessageData($queryBuilder, array $cleaned_parameters)
    {

        $store_result = [];

        $phone = $cleaned_parameters['phone'];
        //$time = $cleaned_parameters['time'];
        $switch_1 = $cleaned_parameters['switch_1'];
        $switch_2 = $cleaned_parameters['switch_2'];
        $switch_3 = $cleaned_parameters['switch_3'];
        $switch_4 = $cleaned_parameters['switch_4'];
        $fan = $cleaned_parameters['fan'];
        $heater = $cleaned_parameters['heater'];
        $keypad = $cleaned_parameters['keypad'];
        
        $queryBuilder = $queryBuilder->insert('message_data')
            ->values([
                'phone_number' => ':phone',
                'switch_01' => ':switch_01',
                'switch_02' => ':switch_02',
                'switch_03' => ':switch_03',
                'switch_04' => ':switch_04',
                'fan' => ':fan',
                'heater' => ':heater',
                'keypad' => ':keypad'
            ])->setParameters([
                ':phone' => $phone,
                ':switch_01' => $switch_1,
                ':switch_02' => $switch_2,
                ':switch_03' => $switch_3,
                ':switch_04' => $switch_4,
                ':fan' => $fan,
                ':heater' => $heater,
                ':keypad' => $keypad
            ]);


        $store_result['outcome'] = $queryBuilder->execute();
        $store_result['sql_query'] = $queryBuilder->getSQL();

        return $store_result;
    }

    public static function queryRetrieveUserData()
    {
        $sql_query_string = '';
        return $sql_query_string;
    }
}
