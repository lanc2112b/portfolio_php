<?php

namespace App\Models;

use PDO;
use GuzzleHttp\Client;

class Contact extends \Core\Model
{

    public $errors = [];

    protected $name;
    protected $email;
    protected $subject;
    protected $query;
    protected $source;
    protected $notified;
    protected $token;
    protected $clrchck;
    protected $client;
    protected $recaptcha_response;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function getAll($limit, $page)
    {
        $offset = ($page * $limit) - $limit; 

        $sql = "SELECT * 
                FROM contacts
                ORDER BY created_at DESC
                LIMIT :limit
                OFFSET :offset";

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMessageCount()
    {
        $sql = 'SELECT COUNT(*) AS total_rows FROM contacts';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getItemByID($id)
    {
        $sql = "SELECT * 
                FROM contacts 
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

            $sql = 'INSERT INTO contacts
                (name, email, subject, query, source)
                VALUES
                (:name, :email, :subject, :query, :source)
                '; //RETURNING *

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':subject', $this->subject, PDO::PARAM_STR);
            $stmt->bindValue(':query', $this->query, PDO::PARAM_STR);
            $stmt->bindValue(':source', $this->source, PDO::PARAM_STR);

            return $stmt->execute();

        }
        return false;
    }

    public function updateItemById($id)
    {
        if (!is_numeric($id)) {
            $this->errors[] = 'Id MUST be a number.';
        }

        if (empty($this->notified) || !is_numeric($this->notified)) {
            $this->errors[] = 'A value for notified must be supplied';
        }

        if (empty($this->errors)) {

            $sql = 'UPDATE contacts
                    SET
                    notified = :notified
                    WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':notified', $this->notified, PDO::PARAM_INT);


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
                FROM contacts 
                WHERE id = :id";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        }
    }

    public function validate()
    {

        if ($this->token == ''){
            $this->errors[] = 'ReCaptcha token is required';
        } else {

            $this->recaptcha_response = $this->validateReCaptcha();

            if($this->recaptcha_response->success == false){
                $this->errors[] = 'Invalid ReCaptcha token';
            }elseif ($this->recaptcha_response->action !== 'contact_form') {
                $this->errors[] = 'Incorrect source';
            }elseif ($this->recaptcha_response->score <= 0.3) {
                $this->errors[] = 'Score too low';
            } 
        }

        if($this->clrchck !== '') {
            $this->errors[] = 'Bot filled?';
        }

        if ($this->name == '' || strlen($this->name) < 4 || strlen($this->name) > 254) {
            $this->errors[] = 'A fullname is required, between 10 & 254 chars';
        }

        if ($this->email == '' || !filter_var($this->email, FILTER_VALIDATE_EMAIL) || strlen($this->email) > 254) {
            $this->errors[] = 'A valid email is required, max 254 chars';
        }

        if ($this->subject == '' || strlen($this->subject) < 10 || strlen($this->subject) > 254) {
            $this->errors[] = 'A subject is required, between 10 & 254 chars';
        }

        if ($this->query == '' || strlen($this->query) < 10 || strlen($this->query) > 1500) {
            $this->errors[] = 'A query is required, between 10 & 1500 chars';
        }

        if ($this->source !== '') {
            if (strlen($this->source) < 10  || strlen($this->source) > 254) {
                $this->errors[] = 'Source should be between 10 & 254 chars';
            }
        }
    }

    public function validateReCaptcha()
    {
        $post_data = [
            'secret' => $_ENV['RC_SECRET'],
            'response' => $this->token
        ];

        $this->client = new Client([
            'base_uri' => 'https://www.google.com/',
        ]);

        $response = $this->client->request(
            'POST',
            '/recaptcha/api/siteverify',
            ['query' => $post_data]
        );

        return json_decode($response->getBody());
    }
}
