<?php
// app/Router.php

class Router
{
    private $routes = [];

    public function get($route, $action)
    {
        $this->routes['GET'][$route] = $action;
    }

    public function post($route, $action)
    {
        $this->routes['POST'][$route] = $action;
    }

    public function put($route, $action)
    {
        $this->routes['PUT'][$route] = $action;
    }

    public function delete($route, $action)
    {
        $this->routes['DELETE'][$route] = $action;
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Supporto override metodo via POST (opzionale)
        if ($method === 'POST' && isset($_POST['_method'])) {
            $overrideMethod = strtoupper($_POST['_method']);
            if (in_array($overrideMethod, ['PUT', 'DELETE'])) {
                $method = $overrideMethod;
            }
        }

        // Rimuovi prefisso se necessario (adatta al tuo caso)
        $uri = str_replace('/opalix_server/public', '', $uri);

        if (!isset($this->routes[$method])) {
            http_response_code(405);
            echo json_encode(["error" => "Metodo non supportato"]);
            return;
        }

        foreach ($this->routes[$method] as $route => $action) {
            $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([a-zA-Z0-9_-]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Rimuove il match completo
                list($controller, $methodName) = explode('@', $action);

                if (class_exists($controller)) {
                    $instance = new $controller();
                    if (method_exists($instance, $methodName)) {
                        call_user_func_array([$instance, $methodName], $matches);
                        return;
                    } else {
                        http_response_code(500);
                        echo json_encode(["error" => "Metodo '$methodName' non trovato in '$controller'"]);
                        return;
                    }
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Controller '$controller' non trovato"]);
                    return;
                }
            }
        }

        http_response_code(404);
        echo json_encode(["error" => "Route '$uri' non trovata"]);
    }
}
