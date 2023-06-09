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
    
    public function getIndexAction()
    {
        $results = $this->mdl->getAll(50, 1);

        if (!$results)
            throw new \Exception('No items found', 404);

        ViewJSON::responseJson($results);
    }

    public function getViewAction()
    {

        $id = $this->route_params['id'];

        $result = $this->mdl->getLandingContentById($id);

        if (!$result)
            throw new \Exception('No items found', 404);

        ViewJSON::responseJson(['item' => $result]);
    }
}