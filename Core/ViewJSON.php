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
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($response);
    }



}