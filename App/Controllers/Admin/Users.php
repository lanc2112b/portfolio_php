<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\User;
use Core\ViewJSON;

class Users extends Controller
{

    protected $mdl;

    protected function before()
    {

        parent::before();

        $json = file_get_contents('php://input');

        $data = json_decode($json);

        $this->mdl = new User($data ?? []);
    }

    public function postRegisterAction() 
    {



    }
}