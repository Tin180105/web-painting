<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng ký</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>

<body class="auth">

    <main class="auth-wrap">
        <section class="auth-info">
            <a class="auth-logo" href="<?= BASE_URL ?>/">Painting Shop</a>
            <img class="auth-logo-image" src="<?= BASE_URL ?>/images/image1.png" alt="Logo Painting Shop">
            <p class="auth-tag">FINE ART GALLERY</p>
            <h1>Đưa sắc màu vào không gian sống.</h1>
            <p>Tạo tài khoản để khám phá bộ sưu tập, lưu lựa chọn yêu thích và mua sắm thuận tiện hơn.</p>
        </section>

        <section class="auth-box">
            <p class="auth-box-tag">Bắt đầu hành trình</p>
            <h2>Đăng ký tài khoản</h2>
            <p class="auth-sub">Chỉ vài thông tin để mở cánh cửa vào phòng tranh.</p>

            <?php if (!empty($message)): ?>
                <p class="msg" role="alert"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <form class="auth-form" action="<?= BASE_URL ?>/register" method="POST">

        <div>
            <label>Họ và tên</label>

                <input type="text" name="full_name" autocomplete="name" required>
        </div>

        <div>
            <label>Email</label>

                <input type="email" name="email" autocomplete="email" required>
        </div>

        <div>
            <label>Mật khẩu</label>

                <input type="password" name="password" autocomplete="new-password" required>
        </div>

        <div>
            <label>Số điện thoại</label>

                <input type="text" name="phone" autocomplete="tel">
        </div>

                <button class="auth-submit" type="submit">Tạo tài khoản</button>

            </form>

            <p class="auth-switch">Đã có tài khoản? <a href="<?= BASE_URL ?>/login">Đăng nhập</a></p>
        </section>
    </main>

</body>

</html>
