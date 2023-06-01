<?php

namespace App\Controllers\Admin;

//use Core\Controller;

use App\Controllers\Authenticated;
use App\Models\Contact;
use Core\ViewJSON;


class Contacts extends Authenticated
{
    
    protected $mdl;
    protected $page;
    protected $limit;

    protected function before()
    {

        parent::before();

        $json = file_get_contents('php://input');

        $data = json_decode($json);

        $this->mdl = new Contact($data ?? []);
    }

    public function getIndexAction()
    {

        if (isset($_GET['page']) && is_numeric($_GET['page']))
            $this->page = $_GET['page'];

        if (isset($_GET['limit']) && is_numeric($_GET['limit']))
            $this->limit = $_GET['limit'];

        $count = $this->mdl->getMessageCount();

        $results = $this->mdl->getAll($this->limit ?? 10, $this->page ?? 1);
        //var_dump($results);
        if (!$results)
            throw new \Exception('No items found', 404);

        $data = [$count, $results];
        
        ViewJSON::responseJson($data);
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