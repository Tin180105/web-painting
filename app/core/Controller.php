<?php

/**
 * Base Controller
 *
 * Mọi Controller nên kế thừa class này để dùng chung
 * render() (nạp View) và redirect() (điều hướng URL).
 */
class Controller
{
    // Nạp file View trong app/views/, truyền $data ra làm biến cho View dùng
    protected function render($view, $data = [])
    {
        extract($data);

        $viewPath = __DIR__ . "/../views/{$view}.php";

        if (!file_exists($viewPath)) {
            die("Lỗi hệ thống: Không tìm thấy view [{$view}]");
        }

        require $viewPath;
    }

    // Trả về JSON - dùng cho các API được gọi bằng JS (fetch/AJAX)
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($data);
        exit;
    }

    // Điều hướng tới 1 route khác (path tính từ base URL của project)
    protected function redirect($path)
    {
        header("Location: " . BASE_URL . $path);
        exit;
    }
}
