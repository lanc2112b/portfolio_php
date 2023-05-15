<?php

namespace db;

use Core\Model;
use PDO;

class Seeder extends Model
{

    public function getPosts()
    {
        $db = static::getDB();

        $stmt = $db->query("SELECT * FROM posts");

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public function dropTable()
    {
        $db = static::getDB();
        $stmt = $db->query("DROP TABLE IF EXISTS posts");
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function createPostsTable()
    {
        $db = static::getDB();

        $sql = "CREATE TABLE IF NOT EXISTS posts(
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(64) NOT NULL,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

        $stmt = $db->query($sql);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function addPosts()
    {
        $db = static::getDB();

        $sql = "INSERT INTO posts
                (title, content)
                VALUES
                ('First post in DB', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'),
                ('Second post in DB', 'In faucibus enim justo, ut facilisis tortor consectetur in.')";

        if ($db->exec($sql)) {
            return true;
        }
        return false;

    }



}
