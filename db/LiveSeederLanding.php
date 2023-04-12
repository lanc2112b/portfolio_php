<?php

namespace db;

use Core\Model;
use PDO;

class LiveSeederLanding extends Model
{

    public function dropTable()
    {
        $db = static::getDB();
        $stmt = $db->query("DROP TABLE IF EXISTS landing_page");
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function createLandingTable()
    {
        $db = static::getDB();

        $sql = "CREATE TABLE IF NOT EXISTS landing_page(
            id INT AUTO_INCREMENT PRIMARY KEY,
            area_title VARCHAR(24),
            area_content_title TINYTEXT,
            area_content TEXT,
            area_content_image TINYTEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP
            )";

        $stmt = $db->query($sql);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function describeTable()
    {
        $db = static::getDB();

        $stmt = $db->prepare("DESCRIBE landing_page");

        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }

}
