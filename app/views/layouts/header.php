<?php if (!isset($cartCount)) { $cartCount = 0; } ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . " - " : "" ?>Painting Shop</title>
    <?php $styleCssPath = __DIR__ . "/../../../public/css/style.css"; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css?v=<?= is_file($styleCssPath) ? filemtime($styleCssPath) : 1 ?>">
    <script>
        window.BASE_URL = "<?= BASE_URL ?>";
    </script>
</head>

<body>

    <nav class="nav">

        <a class="nav-brand" href="<?= BASE_URL ?>/">
            <img src="<?= BASE_URL ?>/images/image1.png" alt="" class="logo">
            <span>Painting Shop</span>
        </a>

        <div class="nav-links">

            <a href="<?= BASE_URL ?>/">Trang chủ</a>

            <?php if (isset($_SESSION["user_id"])): ?>

                <a href="<?= BASE_URL ?>/addresses">Địa chỉ</a>
                <a href="<?= BASE_URL ?>/orders">Đơn hàng của tôi</a>
                <a href="<?= BASE_URL ?>/cart" class="cart-link">
                    Giỏ hàng
                    <span class="cart-badge" id="cart-badge"><?= $cartCount ?></span>
                </a>

                <span class="nav-user">
                    Xin chào, <?= htmlspecialchars($_SESSION["full_name"] ?? "") ?>
                </span>

                <a href="<?= BASE_URL ?>/logout">Đăng xuất</a>

            <?php else: ?>

                <a href="<?= BASE_URL ?>/login">Đăng nhập</a>
                <a href="<?= BASE_URL ?>/register">Đăng ký</a>

            <?php endif; ?>

        </div>

    </nav>

    <div id="toast" class="toast"></div>

    <main class="container">
