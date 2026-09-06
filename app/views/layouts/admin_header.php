<?php
if (!isset($pageTitle)) { $pageTitle = "Admin"; }
if (!isset($activeMenu)) { $activeMenu = ""; }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Admin | Painting Shop</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/admin.css">
</head>
<body class="admin-body">

    <div class="admin-wrapper">

        <aside class="admin-sidebar">

            <div class="admin-sidebar-brand">
                <span>Painting Shop</span>
                <small>Trang quản trị</small>
            </div>

            <nav class="admin-menu">
                <a href="<?= BASE_URL ?>/admin/dashboard" class="<?= $activeMenu === "dashboard" ? "active" : "" ?>">
                    Tổng quan
                </a>
                <a href="<?= BASE_URL ?>/admin/categories" class="<?= $activeMenu === "categories" ? "active" : "" ?>">
                    Danh mục
                </a>
                <a href="<?= BASE_URL ?>/admin/orders" class="<?= $activeMenu === "orders" ? "active" : "" ?>">
                    Đơn hàng
                </a>
            </nav>

            <div class="admin-sidebar-footer">
                <span>Xin chào, <?= htmlspecialchars($_SESSION["full_name"] ?? "") ?></span>
                <a href="<?= BASE_URL ?>/logout" class="admin-logout-btn">Đăng xuất</a>
            </div>

        </aside>

        <main class="admin-content"></main>