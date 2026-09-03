<?php

// Yêu cầu người dùng phải đăng nhập, nếu chưa thì đá về trang login
function requireLogin()
{
    if (!isset($_SESSION["user_id"])) {

        header("Location: " . BASE_URL . "/login");
        exit;
    }
}
