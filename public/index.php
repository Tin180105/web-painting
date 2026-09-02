<?php

session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../app/controllers/AuthController.php";
require_once __DIR__ . "/../app/controllers/CategoryController.php";

$route = trim($_GET["route"] ?? "", "/");
$method = $_SERVER["REQUEST_METHOD"] ?? "GET";

$authController = new AuthController($conn);
$categoryController = new CategoryController($conn);

switch ($route) {
    case "login":
        $method === "POST" ? $authController->login() : $authController->showLogin();
        break;

    case "register":
        $method === "POST" ? $authController->register() : $authController->showRegister();
        break;

    case "logout":
        $authController->logout();
        break;

    case "admin/categories":
        $categoryController->index();
        break;

    case "admin/categories/create":
        $method === "POST" ? $categoryController->store() : $categoryController->create();
        break;

    case "admin/categories/edit":
        $id = (int) ($_GET["id"] ?? 0);
        $method === "POST" ? $categoryController->update($id) : $categoryController->edit($id);
        break;

    case "admin/categories/delete":
        $categoryController->delete((int) ($_GET["id"] ?? 0));
        break;

    default:
        $authController->showLogin();
        break;
}