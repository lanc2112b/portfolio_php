<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Landing;
use Core\ViewJSON;

class Landings extends Controller
{
    protected $mdl;

    protected function before()
    {

        parent::before();

        $json = file_get_contents('php://input');

        $data = json_decode($json);

        $this->mdl = new Landing($data ?? []);
    }
    
}