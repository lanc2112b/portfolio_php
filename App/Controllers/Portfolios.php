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

    /**
     * getIndexAction
     * 
     * Returns all items in portfolio_items
     * Is it worth prepending request method at this point?
     * Everything needs /someAction ? hmmm. leave in for future use.
     * 
     * @return void
     */
    public function getIndexAction()
    {
        $results = $this->mdl->getAll();
        ViewJSON::responseJson($results);
    }

}