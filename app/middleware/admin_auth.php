<?php

// Yêu cầu người dùng phải đăng nhập VÀ có role = admin
function requireAdmin()
{
    if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {

        header("Location: " . BASE_URL . "/admin/login");
        exit;
    }
}
