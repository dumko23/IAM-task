<?php

namespace App\core;

use Exception;

class Router
{
    /**
     * @var array array of stored routes to handle incoming requests
     */
    protected array $routes = [
        'POST' => [],
        'GET' => [],
        '404' => 'PageController@page404'
    ];

    /**
     * Stores declared routes in 'routes' array
     *
     * @param  string  $file  file name where routes can be found
     * @return static Router class
     */
    public static function load(string $file): static
    {
        $router = new static;

        require $file;

        return $router;
    }

    /**
     * Redirect user according request uri and method
     *
     * @param  string  $uri            request uri
     * @param  string  $requestMethod  request method
     * @return mixed|null resolved uri response
     * @throws Exception
     */
    public function redirect(string $uri, string $requestMethod)
    {
        if (array_key_exists($uri, $this->routes[$requestMethod])) {
            return $this->callAction(
                ...explode('@', $this->routes[$requestMethod][$uri])
            );
        } else {
            return $this->callAction(
                ...explode('@', $this->routes['404'])
            );
        }
    }

    /**
     * Stores get method uri in 'routes'
     *
     * @param  string  $uri         uri to resolve
     * @param  string  $controller  controller class with bound method to resolve request uri
     * @return void
     */
    public function get(string $uri, string $controller): void
    {
        $this->routes['GET'][$uri] = $controller;
    }

    /**
     * Stores post method uri in 'routes'
     *
     * @param  string  $uri         uri to resolve
     * @param  string  $controller  controller class with bound method to resolve request uri
     * @return void
     */
    public function post(string $uri, string $controller): void
    {
        $this->routes['POST'][$uri] = $controller;
    }

    /**
     * Calling controller's method bound to request uri
     *
     * @param  string  $controller Controller class to invoke
     * @param  string  $action method to call
     * @return mixed|void requested page OR void (delegating response to calling controller's method)
     * @throws Exception
     */
    protected function callAction(string $controller, string $action)
    {
        $controllerName = $controller;
        $controller = "App\app\controllers\\$controller";
        $controller = new $controller;
        if (!method_exists($controller, $action)) {
            throw new Exception("{$controller} does not respond to the {$action} action");
        }
        if ($controllerName === 'HandleController') {
            echo $controller->$action();
            exit();
        }
        $result = $controller->$action();
        return (new View)->showView($result);
    }
}