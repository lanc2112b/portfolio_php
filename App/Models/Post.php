<?php

namespace App\Models;

use PDO;


class Post{

    public static function getAll() 
    {
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $dbuser = $_ENV['DB_USER'];
        $dbpass = $_ENV['DB_PASS'];

        try {
            $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);

            $stmt = $db->query("SELECT * FROM posts");

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }


    }


}