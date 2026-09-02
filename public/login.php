<?php

session_start();

require_once "../config/database.php";
require_once "../app/controllers/AuthController.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $authController = new AuthController($conn);

    $result = $authController->login();

    if ($result["success"]) {

        if ($result["role"] === "admin") {

            header("Location: ../app/views/admin/dashboard.php");

        } else {

            header("Location: ../app/views/client/index.php");
        }

        exit;
    }

    $message = $result["message"];
}

require_once "../app/views/auth/login.php";