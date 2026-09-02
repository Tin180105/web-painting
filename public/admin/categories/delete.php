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

$result = $categoryController->delete($id);

header("Location: index.php?message=" . urlencode($result["message"]));
exit;