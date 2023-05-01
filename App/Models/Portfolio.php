<?php

namespace App\Models;

use PDO;

class Portfolio extends \Core\Model
{

    public $errors = [];

    protected $title;
    protected $description;
    protected $hosted_url;
    protected $github_url;
    protected $image_url;
    protected $video_url;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function getAll()
    {

        $sql = "SELECT * 
                FROM portfolio_items";

        $db = static::getDB();

        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemByID($id)
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

    public function addItem()
    {
        $this->validate();
        
        if (empty($this->errors)) {

            $sql = 'INSERT INTO portfolio_items
                (title, description, hosted_url, github_url, image_url, video_url)
                VALUES
                (:title, :description, :hosted_url, :github_url, :image_url, :video_url)
                ';//RETURNING *

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
            $stmt->bindValue(':hosted_url', $this->hosted_url, PDO::PARAM_STR);
            $stmt->bindValue(':github_url', $this->github_url, PDO::PARAM_STR);
            $stmt->bindValue(':image_url', $this->image_url, PDO::PARAM_STR);
            $stmt->bindValue(':video_url', $this->video_url, PDO::PARAM_STR);

            return $stmt->execute();

        }
        return false;
    }

    public function updateItemById($id)
    {
        $this->validate();

        if (!is_numeric($id) ){
            $this->errors[] = 'Id MUST be a number.';
        }

        if (empty($this->errors)) {

            $sql = 'UPDATE portfolio_items
                    SET
                    title = :title,
                    description = :description,
                    hosted_url = :hosted_url,
                    github_url = :github_url,
                    image_url = :image_url,
                    video_url = :video_url
                    WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
            $stmt->bindValue(':hosted_url', $this->hosted_url, PDO::PARAM_STR);
            $stmt->bindValue(':github_url', $this->github_url, PDO::PARAM_STR);
            $stmt->bindValue(':image_url', $this->image_url, PDO::PARAM_STR);
            $stmt->bindValue(':video_url', $this->video_url, PDO::PARAM_STR);

            return $stmt->execute();

        }
        return false;
    }

    public function deleteItemById($id)
    {
        if (!is_numeric($id)) {
            $this->errors[] = 'Id MUST be a number.';
        }

        if (!$this->getItemByID($id)) {
            $this->errors[] = 'Item does not exist';
        }

        if (empty($this->errors)) {

            $db = static::getDB();
            
            $sql = "DELETE 
                FROM portfolio_items 
                WHERE id = :id";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        }
    }

    public function validate()
    {

        if ($this->title == '' || strlen($this->title) < 6 || strlen($this->title) > 254) {
            $this->errors[] = 'A title is required or must be 6 chars or more';
        }

        if ($this->description == '' || strlen($this->description) < 20 || strlen($this->description) > 10000) {
            $this->errors[] = 'A description is required or must be 20 chars and less than 10000';
        }

        if ($this->hosted_url == '' || !filter_var($this->hosted_url, FILTER_VALIDATE_URL) || strlen($this->hosted_url) > 254) {
            $this->errors[] = 'The provided url is not valid for hosted_url';
        }

        if ($this->github_url == '' || !filter_var($this->github_url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) || strlen($this->github_url) > 254) {
            $this->errors[] = 'The provided url is not valid for github_url';
        }

        if ($this->image_url == '' || !filter_var($this->image_url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) || !getimagesize($this->image_url)  || strlen($this->image_url) > 254) {
            $this->errors[] = 'The provided url is not valid for image_url';
        }

        if ($this->video_url) {
            if (!filter_var($this->video_url, FILTER_VALIDATE_URL) || strlen($this->video_url) > 254) {
                $this->errors[] = 'The provided url is not valid for video_url: ' . $this->video_url;
            }
        }
    }
}
