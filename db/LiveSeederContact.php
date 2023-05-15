<?php

namespace db;

use Core\Model;
use PDO;

class LiveSeederContact extends Model
{

    public function dropTable()
    {
        $db = static::getDB();
        $stmt = $db->query("DROP TABLE IF EXISTS contacts");
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function createContactTable()
    {
        $db = static::getDB();

        $sql = "CREATE TABLE IF NOT EXISTS contacts(
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(72) NOT NULL,
            email TINYTEXT NOT NULL,
            subject VARCHAR(100) NOT NULL,
            query TEXT NOT NULL,
            source TINYTEXT,
            notified TINYINT(1) NOT NULL DEFAULT 0,
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

        $stmt = $db->prepare("DESCRIBE contacts");

        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }

    public function addContactItems()
    {
        $db = static::getDB();

        $sql = "INSERT INTO contacts
                (name, email, subject, query, source)
                VALUES
                ('Bob Mortimar', 'bob@bob.com', 'Subject here', 'Wassssssup?', 'Indeed CV'),
                ('Captain Kirk', 'kirk@starfleet.com', 'Enterprise UI', 'Beam me up doesn\'t work!', 'GitHub')";

        if ($db->exec($sql)) {
            return true;
        }
        return false;
    }

    public function getContactItems()
    {
        $db = static::getDB();

        $stmt = $db->query("SELECT * FROM contacts");

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }
}
