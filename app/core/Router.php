<?php

/**
 * Router - Front Controller pattern
 *
 * Mọi request đều đi qua public/index.php, được Router này
 * đối chiếu (method + path) với danh sách route đã đăng ký,
 * rồi gọi đúng Controller@method tương ứng.
 */
class Router
{
    private $routes = [];

    // Đăng ký route GET
    public function get($path, $handler)
    {
        $this->addRoute("GET", $path, $handler);
    }

    // Đăng ký route POST
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

    // Tính base path để route hoạt động đúng dù project nằm ở thư mục con nào
    // Ví dụ: http://localhost/web-painting/public/admin/categories
    // -> basePath = /web-painting/public
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

        // Bỏ dấu "/" cuối (trừ khi uri gốc chỉ là "/")
        if ($uri !== "/" && substr($uri, -1) === "/") {
            $uri = rtrim($uri, "/");
        }

        return $uri;
    }

    // Chạy router: tìm route khớp và gọi controller tương ứng
    public function dispatch()
    {
        $uri = $this->getUri();
        $method = $_SERVER["REQUEST_METHOD"];

        foreach ($this->routes as $route) {

            if ($route["method"] !== $method) {
                continue;
            }

            // Chuyển {id}, {slug}... thành pattern regex
            $pattern = preg_replace("#\{[a-zA-Z_]+\}#", "([^/]+)", $route["path"]);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {

                array_shift($matches); // bỏ phần tử [0] (chuỗi khớp toàn bộ)

                [$controllerName, $action] = explode("@", $route["handler"]);

                $this->callController($controllerName, $action, $matches);

                return;
            }
        }

        $this->notFound();
    }

    private function callController($controllerName, $action, $params)
    {
        global $conn;

        $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";

        if (!file_exists($controllerFile)) {
            $this->notFound();
            return;
        }

        require_once $controllerFile;

        $controller = new $controllerName($conn);

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
