<?php

namespace App;

/** login / logout not required. */
use \App\Models\User;

class Authenticate
{

    public static function getUser()
    {

        if(!array_key_exists('HTTP_AUTHORIZATION', $_SERVER)){
            throw new \Exception('No authorization provided', 403);
        }

        if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            throw new \Exception('No token received', 403);
        }

        $mdl = new User(['credential' => $matches[1]]);

        // FIXME:
        return $mdl->validateAuthorisedUser();

    }
}