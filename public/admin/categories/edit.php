<?php

session_start();

require_once "../../../config/database.php";
require_once "../../../app/controllers/CategoryController.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$id = $_GET["id"] ?? 0;

$categoryController = new CategoryController($conn);

$category = $categoryController->show($id);

if (!$category) {
    die("Không tìm thấy danh mục");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $result = $categoryController->update($id);

    $message = $result["message"];

    if ($result["success"]) {
        header("Location: index.php");
        exit;
    }
}

require_once "../../../app/views/admin/categories/edit.php";