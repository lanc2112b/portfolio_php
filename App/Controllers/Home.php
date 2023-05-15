<?php

namespace App\Controllers;

/**
 * 
 * 
 */
use \Core\ViewJSON;

/** home controller */

class Home extends \Core\Controller
{

    protected function before()
    {

    }

    protected function after()
    {

    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getIndexAction()
    {
        /** return api running, try:  */
        //echo "Hello from the home controller";
        $arr = ['msg' => 'Message from home'];
        ViewJSON::responseJson($arr);
    }
}