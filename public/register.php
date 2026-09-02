<?php

require_once "../config/database.php";
require_once "../app/controllers/AuthController.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $authController = new AuthController($conn);

    $result = $authController->register();

    $message = $result["message"];

    if ($result["success"]) {

        header("Location: login.php");
        exit;
    }
}

require_once "../app/views/auth/register.php";