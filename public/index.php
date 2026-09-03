<?php

/**
 * Front Controller
 *
 * Đây là điểm vào (entry point) DUY NHẤT của ứng dụng.
 * Mọi request (nhờ .htaccess rewrite) đều đi qua file này.
 */

session_start();

// BASE_URL: tính tự động từ request thực tế, KHÔNG hardcode.
// Chạy qua XAMPP (http://localhost/web-painting/public) hay
// php -S localhost:8000 -t public (http://localhost:8000) đều đúng.
define("BASE_URL", rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/"));

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../app/core/Router.php";
require_once __DIR__ . "/../app/core/Controller.php";
require_once __DIR__ . "/../app/middleware/auth.php";
require_once __DIR__ . "/../app/middleware/admin_auth.php";

$router = new Router();

require_once __DIR__ . "/../routes/web.php";

$router->dispatch();
