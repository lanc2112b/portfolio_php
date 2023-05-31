<?php

namespace db;

use Core\Model;
use PDO;

class LiveSeederUser extends Model
{

    public function dropTable()
    {
        $db = static::getDB();
        $stmt = $db->query("DROP TABLE IF EXISTS users");
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function createUsersTable()
    {
        $db = static::getDB();

        $sql = "CREATE TABLE IF NOT EXISTS users(
            id INT AUTO_INCREMENT PRIMARY KEY,
            gid VARCHAR(32) NOT NULL,
            is_admin TINYINT(1) NOT NULL DEFAULT 0,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            email TINYTEXT NOT NULL,
            photo_url TINYTEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP,
            refresh_at TIMESTAMP,
            refresh_token TEXT
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

        $stmt = $db->prepare("DESCRIBE users");

        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }


}