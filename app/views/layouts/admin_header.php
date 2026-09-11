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
                    <svg class="admin-menu-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
                    Tổng quan
                </a>

                <a href="<?= BASE_URL ?>/admin/categories" class="<?= $activeMenu === "categories" ? "active" : "" ?>">
                    <svg class="admin-menu-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41L11 3.83A2 2 0 0 0 9.59 3.24H4a1 1 0 0 0-1 1v5.59a2 2 0 0 0 .59 1.41l9.58 9.59a2 2 0 0 0 2.82 0l4.6-4.6a2 2 0 0 0 0-2.82z"></path><circle cx="7.5" cy="7.5" r="1.5"></circle></svg>
                    Danh mục
                </a>
                
                                <a href="<?= BASE_URL ?>/admin/paintings" class="<?= $activeMenu === "paintings" ? "active" : "" ?>">
                    <svg class="admin-menu-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2z"></path><line x1="7" y1="20" x2="17" y2="20"></line><line x1="12" y1="16" x2="12" y2="20"></line></svg>
                    Sản phẩm
                </a>

                <a href="<?= BASE_URL ?>/admin/orders" class="<?= $activeMenu === "orders" ? "active" : "" ?>">
                    <svg class="admin-menu-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    Đơn hàng
                </a>

                <a href="<?= BASE_URL ?>/admin/users" class="<?= $activeMenu === "users" ? "active" : "" ?>">
                    <svg class="admin-menu-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Tài khoản
                </a>

            </nav>

            <div class="admin-sidebar-footer">
                <div class="admin-sidebar-user">
                    <span class="admin-avatar"><?= strtoupper(substr($_SESSION["full_name"] ?? "A", 0, 1)) ?></span>
                    <span>Xin chào, <?= htmlspecialchars($_SESSION["full_name"] ?? "") ?></span>
                </div>
                <a href="<?= BASE_URL ?>/logout" class="admin-logout-btn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Đăng xuất
                </a>
            </div>

        </aside>

        <main class="admin-content">