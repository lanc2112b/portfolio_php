<?php

namespace App\Models;

use PDO;

class Landing extends \Core\Model
{

    public $errors = [];

    protected $area_title;
    protected $area_content_title;
    protected $area_content;
    protected $area_content_image;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function getAll()
    {

        $sql = "SELECT * 
                FROM landing_page";

        $db = static::getDB();

        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}