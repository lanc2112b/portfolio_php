<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Portfolio;
use Core\ViewJSON;

class Portfolios extends Controller
{

    protected $mdl;

    protected function before()
    {

        parent::before();
        
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

        $status = ($results) ? 200 : 404;

        ViewJSON::responseJson($results ?: 'No items found', $status);
    }

    public function getViewAction()
    {
        $id = $this->route_params['id'];
        
        $result = $this->mdl->getItembyID($id);

        $status = ($result) ? 200 : 404;

        ViewJSON::responseJson(['item' => $result ?: 'No item found'], $status);
    }

}