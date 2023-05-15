<?php

namespace App\Controllers;


use Core\Controller;
use App\Models\Contact;
use Core\ViewJSON;


class Contacts extends Controller
{
    
    protected $mdl;

    protected function before()
    {

        parent::before();

        $json = file_get_contents('php://input');

        $data = json_decode($json);

        $this->mdl = new Contact($data ?? []);
    }

    public function postAddAction()
    {

        $result = $this->mdl->addItem();

        if (!$result)
            throw new \Exception('Bad request', 400);

        ViewJSON::responseJson(['item' => $result], 201);
    }
}