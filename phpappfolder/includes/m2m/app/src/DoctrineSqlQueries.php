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

use Doctrine\DBAL\DriverManager;

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
        $receivedtime = $cleaned_parameters['received_time'];

        
        $queryBuilder = $queryBuilder->insert('message_data')
            ->values([
                'phone_number' => ':phone',
                'switch_01' => ':switch_01',
                'switch_02' => ':switch_02',
                'switch_03' => ':switch_03',
                'switch_04' => ':switch_04',
                'fan' => ':fan',
                'heater' => ':heater',
                'keypad' => ':keypad',
                'receivedtime' => ':receivedtime'
            ])->setParameters([
                ':phone' => $phone,
                ':switch_01' => $switch_1,
                ':switch_02' => $switch_2,
                ':switch_03' => $switch_3,
                ':switch_04' => $switch_4,
                ':fan' => $fan,
                ':heater' => $heater,
                ':keypad' => $keypad,
                ':receivedtime' => $receivedtime
            ]);


        $store_result['outcome'] = $queryBuilder->execute();
        $store_result['sql_query'] = $queryBuilder->getSQL();

        return $store_result;
    }

    public static function querySelectMessageData($conn)
    {
        $sql = "SELECT * FROM message_data";
        $stmt = $conn->query($sql); // Simple, but has several drawbacks
        while ($row = $stmt->fetch()) {

            var_dump($row);
        }

        $sql_query_string = '';
        return $sql_query_string;
    }

    public static function queryCheckMessageData($conn, $queryBuilder, array $cleaned_parameters)
    {
        $phone = $cleaned_parameters['phone'];
        //$time = $cleaned_parameters['time'];
        $switch_1 = $cleaned_parameters['switch_1'];
        $switch_2 = $cleaned_parameters['switch_2'];
        $switch_3 = $cleaned_parameters['switch_3'];
        $switch_4 = $cleaned_parameters['switch_4'];
        $fan = $cleaned_parameters['fan'];
        $heater = $cleaned_parameters['heater'];
        $keypad = $cleaned_parameters['keypad'];
        $receivedtime = $cleaned_parameters['received_time'];

        $queryBuilder = $queryBuilder->select('*')
            ->from('message_data')
            ->where('phone_number = \'' . $phone . '\'')
            ->andWhere('switch_01 = \'' . $switch_1. '\'')
            ->andWhere('switch_02 = \'' . $switch_2. '\'')
            ->andWhere('switch_03 = \'' . $switch_3. '\'')
            ->andWhere('switch_04 = \'' . $switch_4. '\'')
            ->andWhere('heater = \'' . $heater. '\'')
            ->andWhere('fan = \'' . $fan. '\'')
            ->andWhere('keypad = \'' . $keypad. '\'')
            ->andWhere('receivedtime = \'' . $receivedtime. '\'');


        $stmt = $conn->query($queryBuilder->getSQL());

        return $stmt->fetch();
    }

    public static function queryGetMessageData($conn) {
        $sql = "SELECT * FROM message_data ORDER BY `message_id` DESC";
        $stmt = $conn->query($sql); // Simple, but has several drawbacks
        $messages = [];


        while ($row = $stmt->fetch()) {
            $messages[] = $row;
        }

        return $messages;

    }
    public static function queryRetrieveUserData()
    {
        $sql_query_string = '';
        return $sql_query_string;
    }

    public static function queryStoreUserData($queryBuilder, array $cleaned_parameters, string $hashed_password)
    {

        $store_result = [];

        $username = $cleaned_parameters['sanitised_username'];
        $emailAddress = $cleaned_parameters['sanitised_email'];
        $phone_number = $cleaned_parameters['sanitised_phone_number'];


        $queryBuilder = $queryBuilder->insert('user_data')
            ->values([
                'user_name' => ':username',
                'password' => ':password',
                'email' => ':email',
                'phone_number' => ':phone_number'
            ])->setParameters([
                ':username' => $username,
                ':password' => $hashed_password,
                ':email' => $emailAddress,
                ':phone_number' => $phone_number
            ]);


        $store_result['outcome'] = $queryBuilder->execute();
        $store_result['sql_query'] = $queryBuilder->getSQL();

        return $store_result;
    }
    
    public static function queryGetLatestMessageData($conn) {
        $sql = "SELECT * FROM message_data WHERE timestamp = (SELECT MAX(timestamp) FROM message_data) ";
        $stmt = $conn->query($sql); // Simple, but has several drawbacks
        $messages = [];


        while ($row = $stmt->fetch()) {
            $messages[] = $row;
        }

        return $messages;

    }
    
    /**
     * Checks if a specific user exists in the database
     * @param $conn
     * @param $cleaned_parameters
     * @return mixed
     */
    public static function queryCheckUserExists($conn, $cleaned_parameters) {


        $email = $cleaned_parameters['sanitised_email'];

        $sql ='SELECT * FROM user_data WHERE email=:email';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();


        return $user;
    }
}
