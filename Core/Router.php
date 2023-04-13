<?php

namespace Core;

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
     * paramaters from url
     *
     * @var array
     */
    protected $params = [];

    /**
     * Undocumented function
     *
     * @param $ string $route
     * @param array $params
     * @return void
     */
    public function add($route, $params = [])
    {
        // escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);

        // convert variables {something}
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        // Convert custom variables, e.g. id's etc
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        // add start & end delimeters and set case insensitive
        $route = '/^' . $route . '$/i';

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

    /**
     * Match controller / action pattern in url
     *
     * @param $ string $url
     * @return boolean
     */
    public function match($url)
    {

        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {

                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    /**
     * Dispatcher: calls specific action in given controller 
     * if exists
     *
     * @param string $url
     * @return void
     */
    public function dispatch($url, $request_method)
    {
        $url = $this->removeQueryStringVariables($url);
        
        //var_dump($request_method);
        $request_method = strtolower($request_method);
        //var_dump($request_method);

        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->converToStudlyCaps($controller);
            //$controller = "App\Controllers\\$controller";
            $controller = $this->getNamespace() . $controller;

            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);

                $action = $request_method . '-' . $this->params['action'];
                //var_dump($action);
                $action = $this->convertToCamelCase($action);
                //var_dump($action);

                //if (is_callable([$controller_object, $action])) {
                if (preg_match('/action$/i', $action) == 0) {
                    $controller_object->$action();
                } else {
                    throw new \Exception("Method $action (in controller $controller) not found");
                    //echo "Method $action (in controller $controller) not found";
                }
            } else {
                throw new \Exception("Controller class $controller not found");
                //echo "Controller class $controller not found";
            }
        } else {
            throw new \Exception("No route matched", 404);
        }
    }

    /**
     * return all matching parameters 
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * getNamespace
     * return namespace for given controller
     *
     * @return string
     */
    protected function getNamespace()
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }

        return $namespace;
    }

    /** Utility */

    /**
     * converToStudlyCaps 
     *
     * @param string $str
     * @return string
     */
    public function converToStudlyCaps($str)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }

    /**
     * convertToCamelCase
     *
     * @param string $str
     * @return string    
     */
    public function convertToCamelCase($str)
    {
        return lcfirst($this->converToStudlyCaps($str));
    }
    
    /**
     * returns url with ? query string removed
     *
     * @param string $url
     * @return string
     */
    protected function removeQueryStringVariables($url) 
    {
        if ($url !== ''){
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }
}
