<?php

namespace Core;

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
            echo "Method $method not found in controller " . get_class($this);
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
}