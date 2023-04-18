<?php

namespace App\Controllers\Admin;

//use Core\Controller;
use App\Controllers\Authenticated;
use App\Models\Portfolio;
use Core\ViewJSON;

class Portfolios extends Authenticated
{
    protected $mdl;

    protected function before()
    {

        parent::before();

        $json = file_get_contents('php://input');

        $data = json_decode($json);

        $this->mdl = new Portfolio($data ?? []);
    }

    public function postAddAction()
    {

        $result = $this->mdl->addItem();

        if (!$result)
            throw new \Exception('Bad request acpac', 400);

        ViewJSON::responseJson(['item' => $result], 201);
    }

    public function patchUpdateAction()
    {
        $id = $this->route_params['id'];

        $this->mdl->updateItemById($id);

        if (!empty($this->mdl->errors))
            throw new \Exception('Bad request', 400);

        ViewJSON::responseJson([], 204);
    }

    public function deleteDeleteAction()
    {
        $id = $this->route_params['id'];

        $response = $this->mdl->deleteItemById($id);

        if (!empty($this->mdl->errors) || !$response)
            throw new \Exception('Item not found', 404);

        ViewJSON::responseJson([], 204);
    }

}