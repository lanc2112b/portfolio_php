<?php

namespace Core;

/** 
 * Abstract DB Class (Core)
*/

use PDO;

abstract class Model
{

    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {

            try {

                $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8';

                $db = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);

                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        }

        return $db;
    }
}
