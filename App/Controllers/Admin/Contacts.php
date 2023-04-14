<?php

namespace App\Controllers\Admin;

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

    public function getIndexAction()
    {
        $results = $this->mdl->getAll();

        if (!$results)
            throw new \Exception('No items found', 404);

        ViewJSON::responseJson($results);
    }

    public function getViewAction()
    {
        $id = $this->route_params['id'];

        $result = $this->mdl->getItemByID($id);

        if (!$result)
            throw new \Exception('No item found', 404);

        ViewJSON::responseJson(['item' => $result]);
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