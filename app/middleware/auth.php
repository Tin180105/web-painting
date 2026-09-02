<?php

session_start();

function requireLogin()
{
    if (!isset($_SESSION["user_id"])) {

        header("Location: /web-painting/public/index.php?route=login");
        exit;
    }
}