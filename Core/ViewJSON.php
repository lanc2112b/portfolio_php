<?php

namespace Core;

/** JSON response 
 * 
 * Perhaps needs moving to an API folder? 
 */

class ViewJSON
{

    /** FIXME: status codes.... */
    public static function responseJson($response,  $status = 200, $cookie = []) //$refresh_token = null,
    {

        $allowed_hosts = explode(',', $_ENV['ALLOWED_HOSTS']);

        $http_origin = '';

        if (in_array($_SERVER['HTTP_ORIGIN'], $allowed_hosts)) {
            $http_origin = $_SERVER['HTTP_ORIGIN'];
        }

        header_remove();
        header("Access-Control-Allow-Origin: $http_origin");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PATCH');
        header('Access-Control-Allow-Credentials: true');
        header('Content-Type: application/json');
        if (!empty($cookie)) {
            setcookie(
                "jwt",
                $cookie['token'],
                [
                    "expires" => $cookie['expiry'],
                    "httpOnly" => true,
                    "secure" => true,
                    "samesite" => 'None',
                ]
            );
        }
        http_response_code($status);
        echo json_encode($response);
    }
}
