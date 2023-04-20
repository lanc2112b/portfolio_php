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

        $db = static::getDB();

        $sql = "SELECT * 
                FROM landing_page";

        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLandingContentById($id)
    {
        $sql = "SELECT * 
                FROM landing_page 
                WHERE id = :id";

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addItem()
    {
        $this->validate();

        if (empty($this->errors)) {

            $sql = 'INSERT INTO landing_page
                (area_title, area_content_title, area_content, area_content_image)
                VALUES
                (:area_title, :area_content_title, :area_content, :area_content_image)
                RETURNING *';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':area_title', $this->area_title, PDO::PARAM_STR);
            $stmt->bindValue(':area_content_title', $this->area_content_title, PDO::PARAM_STR);
            $stmt->bindValue(':area_content', $this->area_content, PDO::PARAM_STR);
            $stmt->bindValue(':area_content_image', $this->area_content_image, PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function updateItemById($id)
    {
        $this->validate();

        if (!is_numeric($id)) {
            $this->errors[] = 'Id MUST be a number.';
        }

        if (empty($this->errors)) {

            $sql = 'UPDATE landing_page
                    SET
                    area_title = :area_title,
                    area_content_title = :area_content_title,
                    area_content = :area_content,
                    area_content_image = :area_content_image
                    WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':area_title', $this->area_title, PDO::PARAM_STR);
            $stmt->bindValue(':area_content_title', $this->area_content_title, PDO::PARAM_STR);
            $stmt->bindValue(':area_content', $this->area_content, PDO::PARAM_STR);
            $stmt->bindValue(':area_content_image', $this->area_content_image, PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function deleteItemById($id)
    {
        if (!is_numeric($id)) {
            $this->errors[] = 'Id MUST be a number.';
        }

        if (empty($this->errors)) {

            $db = static::getDB();

            $sql = "DELETE 
                FROM landing_page 
                WHERE id = :id";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        }
    }

    public function validate()
    {

        if ($this->area_title == '' || strlen($this->area_title) < 3 || strlen($this->area_title) > 24) {
            $this->errors[] = 'Area title must be over 3 chars & less than 24';
        }

        if ($this->area_content_title == '' || strlen($this->area_content_title) < 12 || strlen($this->area_content_title) > 254) {
            $this->errors[] = 'Content title must be over 12 chars & less than 254';
        }

        if ($this->area_content == '' || strlen($this->area_content) < 20 || strlen($this->area_content) > 3000) {
            $this->errors[] = 'Content must be over 20 chars & less than 3000';
        }

        if ($this->area_content_image) {
            if ($this->area_content_image == '' || !filter_var($this->area_content_image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) || !getimagesize($this->area_content_image)  || strlen($this->area_content_image) > 254) {
                $this->errors[] = 'The provided url is not valid for content image';
            }
        }
    }
}
