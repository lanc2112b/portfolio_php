<?php

namespace Core;

/** JSON response 
 * 
 * Perhaps needs moving to an API folder? 
*/

class ViewJSON
{

    /* protected $allowed_hosts = ['https://www.muninn.co.uk', 'https://pap.muninn.co.uk'];
    protected $http_origin; */

    

    /** FIXME: status codes.... */
    public static function responseJson($response, $status = 200)
    {

        $allowed_hosts = ['https://www.muninn.co.uk', 'https://pap.muninn.co.uk'];
        //$allowed_hosts = ['https://www.muninn.co.uk', 'https://pap.muninn.co.uk', 'http://localhost:3000'];

        $http_origin = '';

        if (in_array($_SERVER['HTTP_ORIGIN'], $allowed_hosts)) {
            $http_origin = $_SERVER['HTTP_ORIGIN'];
        }


        header_remove();
        //header("Access-Control-Allow-Origin: *");  // works
        header("Access-Control-Allow-Origin: $http_origin"); // works
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PATCH');
        header('Access-Control-Allow-Credentials: true');
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($response);
    }


/*     private function getOrigin() {

        if(in_array($_SERVER['HTTP_ORIGIN'], $this->allowed_hosts)) {
            $this->http_origin = $_SERVER['HTTP_ORIGIN'];
        }

    } */



}