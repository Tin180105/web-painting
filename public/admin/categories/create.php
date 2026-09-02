<?php

session_start();

require_once "../../../config/database.php";
require_once "../../../app/controllers/CategoryController.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$message = "";

$categoryController = new CategoryController($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $result = $categoryController->create();

    $message = $result["message"];

    if ($result["success"]) {
        header("Location: index.php");
        exit;
    }
}

require_once "../../../app/views/admin/categories/create.php";