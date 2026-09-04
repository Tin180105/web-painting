<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng nhập</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>

<body class="auth-page">

    <main class="auth-shell">
        <section class="auth-intro">
            <a class="auth-logo" href="<?= BASE_URL ?>/">Painting Shop</a>
            <p class="auth-kicker">FINE ART GALLERY</p>
            <h1>Mỗi bức tranh bắt đầu một câu chuyện.</h1>
            <p>Đăng nhập để lưu lại những tác phẩm bạn yêu thích và theo dõi đơn hàng của mình.</p>
        </section>

        <section class="auth-panel">
            <p class="auth-panel-kicker">Chào mừng trở lại</p>
            <h2>Đăng nhập</h2>
            <p class="auth-subtitle">Tiếp tục hành trình chọn tác phẩm dành cho bạn.</p>

            <?php if (!empty($message)): ?>
                <p class="auth-message" role="alert"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <form class="auth-form" action="<?= BASE_URL ?>/login" method="POST">

        <div>
            <label>Email</label>

                <input type="email" name="email" autocomplete="email" required>
        </div>

        <div>
            <label>Mật khẩu</label>

                <input type="password" name="password" autocomplete="current-password" required>
        </div>

                <button class="auth-submit" type="submit">Đăng nhập</button>

            </form>

            <p class="auth-switch">Chưa có tài khoản? <a href="<?= BASE_URL ?>/register">Đăng ký ngay</a></p>
        </section>
    </main>

</body>

</html>
