<?php

session_start();

require_once "../../../config/database.php";
require_once "../../../app/controllers/CategoryController.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$message = $_GET["message"] ?? "";

$categoryController = new CategoryController($conn);

$categories = $categoryController->index();

require_once "../../../app/views/admin/categories/index.php";