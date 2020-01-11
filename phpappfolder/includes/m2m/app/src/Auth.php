<?php

namespace M2m;

/**
 * Class Auth
 * @package M2m
 *
 * Class to get stored session data, used to
 * validate if a user is signed in.
 */
class Auth
{

    /**
     * Function to check if a session is logged in.
     *
     * @return bool
     */
    public function getStatus()
    {
        $session = new \RKA\Session();
        $session_data = [
            'logged' => $session->get('logged'),
            'auto_id' => $session->get('logged'),
            'user_name' => $session->get('user_name')
        ];


        if ($session->get('logged')) {
            return true;
        }
        return false;
    }

    /**
     *
     * Function to check if a session is logged in, returning the session data.
     *
     * @return array|bool
     */
    public function user()
    {
        $session = new \RKA\Session();
        $session_data = [
            'logged' => $session->get('logged'),
            'auto_id' => $session->get('logged'),
            'user_name' => $session->get('user_name')
        ];

        if ($session->get('logged') !== null) {
            return $session_data;
        }
        return false;
    }
}