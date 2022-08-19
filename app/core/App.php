<?php

// This handles routing

class App {

    // Default values if no routing/method/param is specified
    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Set controller if $url[0] exists as controller
        if(isset($url[0])) {
            if(file_exists('../app/controllers/' . $url[0] . '.php')) {
                $this->controller = $url[0];
                unset($url[0]);
            }
        }

        require_once '../app/controllers/' . $this->controller . '.php';

        $this->controller = new $this->controller;

        // Set method if $url[1] exists as a method in controller
        if(isset($url[1])) {
            if(method_exists($this->controller, $url[1])){
                $this->method = $url[1];
                unset($url[1]);
            }

        }

        // Set extra parameters if they exist
        $this->params = $url ? array_values($url) : [];

        // Call controller with method and parameters
        call_user_func_array([$this->controller, $this->method], $this->params);

    }

    // Fetch and prepare URL (rewritten by .htaccess)
    public function parseUrl() {
        if(isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}