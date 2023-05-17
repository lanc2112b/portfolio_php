<?php

namespace App\Models;

use Core\Model;
use PDO;

/**
 * $_SERVER['REMOTE_ADDR']
 * ipv4 000.000.000.000
 * ipv6 2001:0db8:0000:0000:0000:8a2e:0370:7334
 * ipv4 via ipv6 64:ff9b::192.0.2.128
 * 
 */


class Logging extends Model
{

    protected $address; 

    private function getHost() 
    {

        return gethostbyaddr($this->address);
    }

    public function saveLog($contr, $action, $params, $username = 'none', $validated = 0)
    {
        
        $this->address = $_SERVER['REMOTE_ADDR'];
       
        $sql = 'INSERT INTO logs 
                (addr, host, contr, action, params, username, validated)
                VALUES
                (:addr, :host, :contr, :action, :params, :username, :validated)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':addr', $this->address, PDO::PARAM_STR);
        $stmt->bindValue(':host', $this->getHost(), PDO::PARAM_STR);
        $stmt->bindValue(':contr', $contr, PDO::PARAM_STR);
        $stmt->bindValue(':action', $action, PDO::PARAM_STR);
        $stmt->bindValue(':params', implode(',',$params), PDO::PARAM_STR);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':validated', $validated, PDO::PARAM_INT);

        return $stmt->execute();
    }
}