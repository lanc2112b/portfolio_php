<?php

/**
 * 
 * 
 */
class Router
{
    /**
     * routing 'table'
     *
     * @var array
     */
    protected $routes = [];

    /**
     * Undocumented function
     *
     * @param $ string $route
     * @param array $params
     * @return void
     */
    public function add($route, $params)
    {
        $this->routes[$route] = $params;
    }

    /**
     * Return all routes from the routing table
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}