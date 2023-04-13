<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Portfolio;
use Core\ViewJSON;

class Portfolios extends Controller
{

    protected $mdl;

    public function __construct()
    {
        $this->mdl = new Portfolio();
    }

    public function getIndexAction()
    {
        $results = $this->mdl->getAll();
        ViewJSON::responseJson($results);
    }

}