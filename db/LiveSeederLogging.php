<?php 

namespace db;

use Core\Model;
use PDO;

class LiveSeederLogging extends Model
{

    public function dropTable()
    {
        $db = static::getDB();
        $stmt = $db->query("DROP TABLE IF EXISTS logs");
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function createLoggingTable()
    {
        $db = static::getDB();

        $sql = "CREATE TABLE IF NOT EXISTS logs(
            id INT AUTO_INCREMENT PRIMARY KEY,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            addr VARCHAR(15) NOT NULL,
            host TINYTEXT,
            contr TINYTEXT,
            action TINYTEXT,
            params TINYTEXT,
            username TINYTEXT,
            validated TINYINT(1) NOT NULL DEFAULT 0
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

        $stmt = $db->prepare("DESCRIBE logs");

        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }
}
