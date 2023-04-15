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

    // /api/users/register
    public function postRegisterAction() 
    {

        $result = $this->mdl->createUser();

        if(!$result)
            throw new \Exception('Something went wrong', 400);

        if(array_key_exists('msg',$result ) && $result['msg'] === 'User already exists')
            throw new \Exception($result['msg'], 409);

        if (array_key_exists('msg', $result) && $result['msg'] === 'Invalid token or user')
            throw new \Exception($result['msg'], 400);

        ViewJSON::responseJson($result, 200);

    }
}