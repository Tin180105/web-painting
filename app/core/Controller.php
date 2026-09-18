<?php

class Controller
{
    protected function render($view, $data = [])
    {
        extract($data);

        $viewPath = __DIR__ . "/../views/{$view}.php";

        if (!file_exists($viewPath)) {
            die("Lỗi hệ thống: Không tìm thấy view [{$view}]");
        }

        require $viewPath;
    }

    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($data);
        exit;
    }

    protected function redirect($path)
    {
        header("Location: " . BASE_URL . $path);
        exit;
    }
}
