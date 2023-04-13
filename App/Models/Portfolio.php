<?php

namespace App\Models;

use PDO;

class Portfolio extends \Core\Model
{

    public function getAll()
    {

        $sql = "SELECT * 
                FROM portfolio_items";

        $db = static::getDB();

        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getItembyID($id)
    {
        $sql = "SELECT * 
                FROM portfolio_items 
                WHERE id = :id";

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
