<?php

function requireAdmin()
{
    if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {

        header("Location: " . BASE_URL . "/admin/login");
        exit;
    }
}
