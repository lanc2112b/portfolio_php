<?php

namespace App\Controllers\Admin;

use App\Controllers\Authenticated;
use App\Models\Logging;
use Core\ViewJSON;

class Logs extends Authenticated
{

    protected $mdl;

    protected function before()
    {

        parent::before();

        $this->mdl = new Logging();

    }

    public function getIndexAction()
    {
        $results = $this->mdl->getLogs();

        if (!$results)
            throw new \Exception('No items found', 404);

        ViewJSON::responseJson($results);
    }

}