<?php

class Router
{
    private $routes = [];

    public function get($path, $handler)
    {
        $this->addRoute("GET", $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addRoute("POST", $path, $handler);
    }

    private function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            "method" => $method,
            "path" => $path,
            "handler" => $handler
        ];
    }

    private function getBasePath()
    {
        $scriptDir = dirname($_SERVER["SCRIPT_NAME"]);
        return rtrim($scriptDir, "/");
    }

    private function getUri()
    {
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $basePath = $this->getBasePath();

        if ($basePath !== "" && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        if ($uri === "" || $uri === false) {
            $uri = "/";
        }

        if ($uri !== "/" && substr($uri, -1) === "/") {
            $uri = rtrim($uri, "/");
        }

        return $uri;
    }

    public function dispatch()
    {
        $uri = $this->getUri();
        $method = $_SERVER["REQUEST_METHOD"];

        foreach ($this->routes as $route) {

            if ($route["method"] !== $method) {
                continue;
            }

            $pattern = preg_replace("#\{[a-zA-Z_]+\}#", "([^/]+)", $route["path"]);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {

                array_shift($matches);

                [$controllerName, $action] = explode("@", $route["handler"]);

                $this->callController($controllerName, $action, $matches);

                return;
            }
        }

        $this->notFound();
    }

    private function callController($controllerName, $action, $params)
    {
        global $pdo;

        $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";

        if (!file_exists($controllerFile)) {
            $this->notFound();
            return;
        }

        require_once $controllerFile;

        $controller = new $controllerName($pdo);

        if (!method_exists($controller, $action)) {
            $this->notFound();
            return;
        }

        call_user_func_array([$controller, $action], $params);
    }

    private function notFound()
    {
        http_response_code(404);
        echo "404 - Không tìm thấy trang";
    }
}
