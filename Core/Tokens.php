<?php

namespace Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Tokens
{
    
    private $expires;
    private $issued;
    private $payload; 

    public function __construct() 
    {
        
    }

    public function getNewJWTToken($type, $user_email) 
    {

        $phrase = ($type === 'access') ? $_ENV['ACCESS_KEY'] : $_ENV['REFRESH_KEY']; 
        $expires_addition = ($type === 'access') ? 60 * 1 : 60 * 3;
        //$expires_addition = ($type === 'access') ? 60 * 10 : 24 * (60 * 60);

        $this->issued = time();       
        $this->expires = $this->issued + $expires_addition;

        $this->payload = [
            'iss' => $_ENV['DOMAIN'],
            'aud' => $_ENV['AUDIENCE'],
            'iat' => $this->issued,
            'exp' => $this->expires,
            'user' => $user_email
        ];

        return ['token' => $this->generateJWT($this->payload, $phrase), 'expiry' => $this->expires];
    }

    private function generateJWT($payload, $phrase)
    {
        return JWT::encode($payload, $phrase, 'HS512');
    }

    public function decodeAccessToken($token) 
    {
        return JWT::decode($token, new Key($_ENV['ACCESS_KEY'], 'HS512'));
    }

    public function decodeRefreshToken($token)
    {
        return JWT::decode($token, new Key($_ENV['REFRESH_KEY'], 'HS512'));
    }

}