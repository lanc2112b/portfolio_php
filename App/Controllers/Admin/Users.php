<?php

namespace App\Controllers\Admin;

/** User admin */

class Users extends \Core\Controller
{

    protected function before()
    {
        //check logged in and isAdmin
    }

    public function indexAction()
    {
        echo "Users admin index";
    }
}