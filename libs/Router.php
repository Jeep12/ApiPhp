<?php

class Route {
    private $url;
    private $verb;
    private $controller;
    private $method;
    private $params;

    public function __construct($url, $verb, $controller, $method){
        $this->url = $url;
        $this->verb = $verb;
        $this->controller = $controller;
        $this->method = $method;
        $this->params = [];
    }

    public function match($url, $verb) {
        if($this->verb != $verb){
            return false;
        }
        $partsURL = explode("/", trim($url,'/'));
        $partsRoute = explode("/", trim($this->url,'/'));
        if(count($partsRoute) != count($partsURL)){
            return false;
        }
        $this->params = []; // Reset params
        foreach ($partsRoute as $key => $part) {
            if($part[0] != ":"){
                if($part != $partsURL[$key])
                    return false;
            } else { // It's a parameter
                $paramName = substr($part, 1); // Remove the colon
                $this->params[$paramName] = $partsURL[$key];
            }
        }
        return true;
    }

    public function run(){
        $controller = $this->controller;
        $method = $this->method;
        $params = $this->params;

        if (class_exists($controller) && method_exists($controller, $method)) {
            call_user_func_array([new $controller(), $method], [$params]);
        } else {
            header("HTTP/1.1 404 Not Found");
            echo "404 Not Found";
        }
    }
}

class Router {
    private $routeTable = [];
    private $defaultRoute;

    public function __construct() {
        $this->defaultRoute = null;
    }
    

    public function route($url, $verb) {
        foreach ($this->routeTable as $route) {
            if ($route->match($url, $verb)) {
                $route->run();
                return;
            }
        }
        if ($this->defaultRoute != null) {
            $this->defaultRoute->run();
        } else {
            header("HTTP/1.1 404 Not Found");
            echo "404 Not Found";
        }
    }
    
    public function addRoute($url, $verb, $controller, $method) {
        $this->routeTable[] = new Route($url, $verb, $controller, $method);
    }

    public function setDefaultRoute($controller, $method) {
        $this->defaultRoute = new Route("", "*", $controller, $method);
    }
}
?>
