<?php


session_start();

define("BASE_URL", rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/"));

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../app/core/Router.php";
require_once __DIR__ . "/../app/core/Controller.php";
require_once __DIR__ . "/../app/core/helpers.php";
require_once __DIR__ . "/../app/middleware/auth.php";
require_once __DIR__ . "/../app/middleware/admin_auth.php";

$router = new Router();

require_once __DIR__ . "/../routes/web.php";

$router->dispatch();
