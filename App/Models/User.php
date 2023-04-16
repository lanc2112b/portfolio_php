<?php

namespace App\Models;

use Core\Model;
use Google_Client;
use DateTime;
use PDO;

class User extends Model
{
    public $errors = [];

    protected $client;

    protected $credential;
    protected $payload;

    protected $user;


    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };

        $this->client = new Google_Client(['client_id' => $_ENV['GOOGLE_CLIENT_ID']]);

    }

    public function createUser()
    {
        if (!$this->verifyToken())
            return ['msg' => 'Invalid token or user'];

        if ($this->getUserByGoogleID())
            return ['msg' => 'User already exists'];

        if ($this->user = $this->saveUser())
            return $this->user;

        return [];
    }

    public function validateUserLogin()
    {
        if (!$this->verifyToken())
            return ['msg' => 'Invalid token or user'];

        $validUser = $this->getUserByGoogleID();
        if(!$validUser)
            return ['msg' => 'User not found'];

        if($validUser['refresh_at'] <= date("Y-m-d H:i:s")) {
            // update user
            $this->updateUser();
        }

        return $this->getUserByGoogleID();
    }

    public function validateAuthorisedUser()
    {
        if (!$this->verifyToken())
            return ['msg' => 'Invalid token or user'];

        $validUser = $this->getUserPermsByGoogleID();
        if (!$validUser)
            return ['msg' => 'User not found'];

        if(!$validUser['is_admin'])
            return ['msg' => 'Unauthorised user'];

        //$str = $validUser['refresh_at'] . " vs. " . date('Y-m-d H:i:s');
        if ($validUser['refresh_at'] <= date("Y-m-d H:i:s"))
            return ['msg' => "Token expired"];

        return ['msg' => true];

    }

    private function verifyToken()
    {
        $this->payload = $this->client->verifyIdToken($this->credential);

        if ($this->payload && $this->payload['aud'] === $_ENV['GOOGLE_CLIENT_ID'])           
            return true;

        return false;
    }

    private function getUserByGoogleID()
    {
        
        $gid = $this->payload['sub'];

  
        $sql = "SELECT gid, first_name, last_name, email, photo_url, access_token, refresh_at 
                FROM users 
                WHERE gid = :gid";

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':gid', $gid, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getUserPermsByGoogleID()
    {

        $gid = $this->payload['sub'];


        $sql = "SELECT is_admin, refresh_at 
                FROM users 
                WHERE gid = :gid";

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':gid', $gid, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function saveUser()
    {
        //$this->validate();

        //if (empty($this->errors)) {

            $sql = 'INSERT INTO users
                (gid, first_name, last_name, photo_url, email, access_token, refresh_at)
                VALUES
                (:gid, :first_name, :last_name, :photo_url, :email, :access_token, :refresh_at)
                RETURNING gid, first_name, last_name, email, photo_url, access_token, refresh_at';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':gid', $this->payload['sub'], PDO::PARAM_STR);
            $stmt->bindValue(':first_name', $this->payload['given_name'], PDO::PARAM_STR);
            $stmt->bindValue(':last_name', $this->payload['family_name'], PDO::PARAM_STR);
            $stmt->bindValue(':photo_url', $this->payload['picture'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->payload['email'], PDO::PARAM_STR);
            $stmt->bindValue(':access_token', $this->credential, PDO::PARAM_STR);
            $stmt->bindValue(':refresh_at', date("Y-m-d H:i:s", $this->payload['exp']), PDO::PARAM_STR);
        
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        //}
        //return false;
    }

    private function updateUser(){

        $sql = 'UPDATE users
                SET
                access_token = :access_token,
                refresh_at = :refresh_at
                WHERE 
                gid = :gid';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':gid', $this->payload['sub'], PDO::PARAM_STR);
        $stmt->bindValue(':access_token', $this->credential, PDO::PARAM_STR);
        $stmt->bindValue(':refresh_at', date("Y-m-d H:i:s", $this->payload['exp']), PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    protected $Json = '{
    ***REMOVED***
    ***REMOVED***
    ***REMOVED***
    ***REMOVED***
    ***REMOVED***
    ***REMOVED***
    ***REMOVED***
    ***REMOVED***
    ***REMOVED***
    ***REMOVED***
    ***REMOVED***
    ***REMOVED***
    ***REMOVED***
    ***REMOVED***
    }';

 /*    public function validate()
    {

        if (
            $this->name == '' || strlen($this->name) < 10 || strlen($this->name) > 254
        ) {
            $this->errors[] = 'A fullname is required, between 10 & 254 chars';
        }

        if ($this->email == '' || !filter_var($this->email, FILTER_VALIDATE_EMAIL) || strlen($this->email) > 254) {
            $this->errors[] = 'A valid email is required, max 254 chars';
        }

        if ($this->subject == '' || strlen($this->subject) < 10 || strlen($this->subject) > 254) {
            $this->errors[] = 'A subject is required, between 10 & 254 chars';
        }

        if ($this->query == '' || strlen($this->query) < 10 || strlen($this->query) > 1500) {
            $this->errors[] = 'A subject is required, between 10 & 1500 chars';
        }

        if ($this->source == '' || strlen($this->source) < 10  || strlen($this->source) > 254) {
            $this->errors[] = 'A source is required, between 10 & 254 chars';
        }
    } */
}