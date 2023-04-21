<?php

namespace Core;


use \App\Authenticate;

/** Controller abstract class */
abstract class Controller{

    /**
     * params from matched route
     *
     * @var array
     */
    protected $route_params = [];

    /**
     * constructor
     *
     * @param [array] $route_params
     */
    public function __construct($route_params)
    {
        $this->route_params = $route_params;

    }

    /**
     * Magic __call
     *
     * @param string $name
     * @param array $args
     * @return void
     */
    public function __call($name, $args)
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false){
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }
    /**
     * Called before any action
     *
     * @return void
     */
    protected function before()
    {

    }

    /**
     * called after any action
     *
     * @return void
     */
    protected function after()
    {

    }

    public function requireLogin()
    {
        if(!$user = Authenticate::getUser())
            throw new \Exception('Not valid user or token expired', 401);

        if(!array_key_exists('msg', $user))
            throw new \Exception('Token expired or invalid user', 401);

        if ($user['msg'] !== true)
            throw new \Exception($user['msg'], 401);

        return true;
        
        //return false;
    }

}