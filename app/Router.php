<?php
// app/Router.php

class Router {
    private $routes = [];

    public function get($route, $action) {
        $this->routes['GET'][$route] = $action;
    }

    public function post($route, $action) {
        $this->routes['POST'][$route] = $action;
    }

    public function dispatch() {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        // Rimuovi "/public" dal percorso per lavorare con il router
        $uri = str_replace('/opalix_server/public', '', $uri);

        // Verifica se la rotta è registrata
        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            list($controller, $method) = explode('@', $action);

            // Assicurati che il controller esista
            if (class_exists($controller)) {
                $controller = new $controller();
                $controller->$method();
            } else {
                echo "Controller non trovato!";
            }
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Route not found"]);
        }
    }
}
