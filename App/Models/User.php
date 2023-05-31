<?php

namespace App\Models;

use Core\Model;
use Core\Tokens;
use Google_Client;
use DateTime;
use PDO;

class User extends Model
{
    public $errors = [];

    protected $client;

    protected $credential;

    protected $google_payload;

    protected $user;

    protected $cookie_refresh;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };

        if (isset($_COOKIE['jwt']))
            $this->cookie_refresh = $_COOKIE['jwt'];

        $this->client = new Google_Client(['client_id' => $_ENV['GOOGLE_CLIENT_ID']]);
    }

    public function createUser()
    {
        if (!$this->verifyGoogleToken())
            return ['msg' => 'Invalid token'];

        if ($this->getUserByGoogleID())
            return ['msg' => 'User already exists'];

        if (!$this->saveUser())
            return ['msg' => 'Failed creating user'];

        $this->user = $this->getUserByGoogleID();

        $tkn = new Tokens();

        $refresh_info = $tkn->getNewJWTToken('refresh', $this->user['email']);
        $access_info = $tkn->getNewJWTToken('access', $this->user['email']);

        $this->saveRefreshToken($this->user['email'], $refresh_info);

        $user = array_merge($this->user['email'], $access_info);

        return ['user' => $user, 'refresh_info' => $refresh_info];
    }

    public function validateUserLogin()
    {
        if (!$this->verifyGoogleToken()) // google JWT
            return ['msg' => 'Invalid token or user'];

        $valid_user = $this->getUserByGoogleID();

        if (!$valid_user)
            return ['msg' => 'User not found'];

        $tkn = new Tokens();

        $refresh_info = $tkn->getNewJWTToken('refresh', $valid_user['email']);
        $access_info = $tkn->getNewJWTToken('access', $valid_user['email']);

        $this->saveRefreshToken($valid_user['email'], $refresh_info);

        $user = array_merge($valid_user, $access_info);

        return ['user' => $user, 'refresh_info' => $refresh_info];
    }

    private function saveRefreshToken($email, $token_info)
    {

        $sql = 'UPDATE users
                SET
                refresh_token = :refresh_token,
                refresh_at = :refresh_at
                WHERE 
                email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':refresh_token', $token_info['token'], PDO::PARAM_STR);
        $stmt->bindValue(':refresh_at', date("Y-m-d H:i:s", $token_info['expiry']), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function validateAuthorisedUser()
    {

        $decoded = $this->verifyAccessToken();

        if (method_exists($decoded, 'getMessage')) {
            if ($decoded->getMessage() === 'Expired token') {

                return ['msg' => 'Expired token'];
            } else {

                return ['msg' => 'Invalid token'];
            }
        }

        if ($decoded->iss !== $_ENV['DOMAIN'] || $decoded->aud !== $_ENV['AUDIENCE'])
            return ['msg' => 'Invalid token'];


        if (property_exists($decoded, 'user')) {

            $user = $this->getUserByEmail($decoded->user);

            if (!$user)
                return ['msg' => 'No user found'];

            if (!$user['is_admin'])
                return ['msg' => 'Unauthorised user'];
        } else {

            return ['msg' => 'Unauthorised user'];
        }

        return ['msg' => 'Valid user'];
    }

    public function getNewAccessToken()
    {

        $this->user = $this->getUserByRefreshToken();

        if (!$this->user)
            return ['msg' => 'Invalid token'];

        $decoded = $this->verifyRefreshToken();

        if (method_exists($decoded, 'getMessage')) {

            if ($decoded->getMessage() === 'Expired token') {

                return ['msg' => 'Expired token'];
            }

            return ['msg' => 'Invalid token'];
        }

        if ($decoded->iss !== $_ENV['DOMAIN'] || $decoded->aud !== $_ENV['AUDIENCE'])
            return ['msg' => 'Invalid token'];

        if (property_exists($decoded, 'user')) {

            if ($decoded->user !== $this->user['email'])
                return ['msg' => 'Invalid token'];

            if (!$this->user['is_admin'])
                return ['msg' => 'Unauthorised user'];
        } else {

            return ['msg' => 'Invalid token'];
        }

        $tkn = new Tokens();

        $token = $tkn->getNewJWTToken('access', $this->user['email']);

        unset($this->user['is_admin']);

        $refresh_array = array_merge($this->user, $token);

        return ['auth' => $refresh_array];
    }


    private function verifyRefreshToken()
    {

        $tkn = new Tokens();

        try {

            $decoded = $tkn->decodeRefreshToken($this->cookie_refresh);

            return $decoded;
        } catch (\Exception $e) {

            return $e;
        }
    }


    private function verifyAccessToken()
    {

        $tkn = new Tokens();

        try {

            $decoded = $tkn->decodeAccessToken($this->credential);

            return $decoded;
        } catch (\Exception $e) {

            return $e;
        }
    }

    private function verifyGoogleToken()
    {
        $this->google_payload = $this->client->verifyIdToken($this->credential);

        if ($this->google_payload && $this->google_payload['aud'] === $_ENV['GOOGLE_CLIENT_ID'])
            return true;

        return false;
    }

    private function getUserByEmail($email)
    {

        $sql = "SELECT first_name, last_name, email, photo_url, is_admin 
                FROM users 
                WHERE email = :email";

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getUserByRefreshToken()
    {

        $sql = "SELECT first_name, last_name, email, photo_url, is_admin 
                FROM users 
                WHERE refresh_token = :token";

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':token', $this->cookie_refresh, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getUserByGoogleID()
    {

        $gid = $this->google_payload['sub'];


        $sql = "SELECT first_name, last_name, email, photo_url 
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

        $sql = 'INSERT INTO users
                (gid, first_name, last_name, photo_url, email, refresh_token, refresh_at)
                VALUES
                (:gid, :first_name, :last_name, :photo_url, :email, :refresh_token, :refresh_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':gid', $this->google_payload['sub'], PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $this->google_payload['given_name'], PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $this->google_payload['family_name'], PDO::PARAM_STR);
        $stmt->bindValue(':photo_url', $this->google_payload['picture'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->google_payload['email'], PDO::PARAM_STR);
        $stmt->bindValue(':refresh_token', $this->credential, PDO::PARAM_STR);
        $stmt->bindValue(':refresh_at', date("Y-m-d H:i:s", $this->google_payload['exp']), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function destroyRefreshToken()
    {

        if ($this->verifyRefreshToken()) {

            $this->user = $this->getUserByRefreshToken();

            if (!$this->user)
                return ['msg' => 'Invalid token'];

            if ($this->user['email']) {

                $sql = 'UPDATE users
                SET
                refresh_token = NULL,
                refresh_at = :refresh_at
                WHERE 
                email = :email';

                $db = static::getDB();
                $stmt = $db->prepare($sql);

                $stmt->bindValue(':email', $this->user['email'], PDO::PARAM_STR);
                $stmt->bindValue(':refresh_at', date("Y-m-d H:i:s", time()), PDO::PARAM_STR);

                return $stmt->execute();
            }
        }
    }
}
