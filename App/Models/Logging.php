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

    private function getRef()
    {

        return $_SERVER['HTTP_REFERER'];
    }

    public function saveLog($contr, $action, $params, $username = 'none', $validated = 0)
    {
        
        $this->address = $_SERVER['REMOTE_ADDR'];

        if ($this->address === $_ENV['FILTER_IP'])
            return;
       
        $sql = 'INSERT INTO logs 
                (addr, host, refer, contr, action, params, username, validated)
                VALUES
                (:addr, :host, :refer, :contr, :action, :params, :username, :validated)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':addr', $this->address, PDO::PARAM_STR);
        $stmt->bindValue(':host', $this->getHost(), PDO::PARAM_STR);
        $stmt->bindValue(':refer', $this->getRef() ?? 'none', PDO::PARAM_STR);
        $stmt->bindValue(':contr', $contr, PDO::PARAM_STR);
        $stmt->bindValue(':action', $action, PDO::PARAM_STR);
        $stmt->bindValue(':params', implode(',',$params), PDO::PARAM_STR);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':validated', $validated, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getLogs($limit, $page)
    {
        /** add limit, page, filter_by, sort_by, order */
        $offset = ($page * $limit) - $limit; 

        $sql = 'SELECT *
                FROM logs
                ORDER BY created_at DESC
                LIMIT :limit
                OFFSET :offset';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLogCount()
    {
        $sql = 'SELECT COUNT(*) AS total_rows FROM logs';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
}