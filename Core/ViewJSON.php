<?php

namespace Core;

/** JSON response 
 * 
 * Perhaps needs moving to an API folder? 
*/

class ViewJSON
{

    /** FIXME: status codes.... */
    public static function responseJson($response, $status = 200)
    {
        header_remove();
        //header("Access-Control-Allow-Origin: *");  // works
        header("Access-Control-Allow-Origin: http://localhost:3000"); // works
        header("Access-Control-Allow-Headers: Content-Type");
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($response);
    }



}