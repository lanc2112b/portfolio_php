<?php

namespace App\Models;

class Portfolio extends \Core\Model
{

    public function getAll()
    {

        $db = static::getDB();

        $stmt = $db->query("SELECT * FROM portfolio_items");

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }
}
