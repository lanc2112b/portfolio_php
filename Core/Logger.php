<?php

namespace Core;

use \App\Models\Logging;

class Logger
{

    public function addLog($contr, $action, $params, $username, $validated)
    {   

        $mdl = New Logging();

        $mdl->saveLog($contr, $action, $params, $username, $validated);
    }
}