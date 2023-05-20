<?php

namespace App\Controllers\Admin;

use App\Controllers\Authenticated;
use App\Models\Logging;
use Core\ViewJSON;

class Logs extends Authenticated
{

    protected $mdl;
    protected $page;
    protected $limit;

    protected function before()
    {

        parent::before();

        $this->mdl = new Logging();

    }

    public function getIndexAction()
    {

        if (isset($_GET['page']) && is_numeric($_GET['page']))
            $this->page = $_GET['page'];

        if (isset($_GET['limit']) && is_numeric($_GET['limit']))
            $this->limit = $_GET['limit'];
        
        $count = $this->mdl->getLogCount();

        $results = $this->mdl->getLogs($this->limit ?? 10, $this->page ?? 1);

        if (!$results)
            throw new \Exception('No items found', 404);

        $data = [ $count, $results ];

        ViewJSON::responseJson($data);
    }

}