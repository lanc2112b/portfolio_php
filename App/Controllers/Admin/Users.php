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
            throw new \Exception('', 400);

        if (array_key_exists('msg', $result)) {
            
            if ($result['msg'] === 'Invalid token')
                throw new \Exception($result['msg'], 401);

            if ($result['msg'] === 'User already exists')
                throw new \Exception($result['msg'], 409);
            /*  ViewJSON::responseJson($result, 409);
                return; */
                
            if ($result['msg'] === 'Failed creating user')
                throw new \Exception($result['msg'], 400);
        }

        $user = $result['user'];
        $cookie_info = $result['refresh_info'];
        
        ViewJSON::responseJson(['user' => $user], 200, $cookie_info);

    }

    // /api/users/login
    public function postLoginAction()
    {

        $result = $this->mdl->validateUserLogin(); // against access token (google)

        if (!$result)
            throw new \Exception('Something went wrong', 400);

        $user = $result['user'];
        $cookie_info = $result['refresh_info'];

        if (array_key_exists('msg', $user) && $user['msg'] === 'User not found')
            throw new \Exception($user['msg'], 404);

        if (array_key_exists('msg', $user) && $user['msg'] === 'Invalid token or user')
            throw new \Exception($user['msg'], 400);

        ViewJSON::responseJson(['user' => $user], 200, $cookie_info);
    }


    //api/users/refresh
    public function getRefreshAction()
    {
        $result = $this->mdl->getNewAccessToken();

        if (!$result)
            throw new \Exception('Something went wrong', 400);

        if (array_key_exists('msg', $result) && $result['msg'] === 'Invalid token')
            throw new \Exception($result['msg'], 401);

        if (array_key_exists('msg', $result) && $result['msg'] === 'Unauthorised user')
            throw new \Exception($result['msg'], 401);

        if (array_key_exists('msg', $result) && $result['msg'] === 'Expired token')
            throw new \Exception($result['msg'], 403);

        ViewJSON::responseJson($result['auth'], 200);
    }

    public function getLogoutAction()
    {
        $cookie_info = ['token' => '', 'expiry' => 0];

        $this->mdl->destroyRefreshToken(); 

        ViewJSON::responseJson([], 204, $cookie_info);
    }
}