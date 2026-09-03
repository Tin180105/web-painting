<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng ký</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>

<body>

    <h1>Đăng ký tài khoản</h1>

    <?php if (!empty($message)): ?>

        <p>
            <?= htmlspecialchars($message) ?>
        </p>

    <?php endif; ?>

    <form action="<?= BASE_URL ?>/register" method="POST">

        <div>
            <label>Họ và tên</label>

            <input
                type="text"
                name="full_name"
                required
            >
        </div>

        <div>
            <label>Email</label>

            <input
                type="email"
                name="email"
                required
            >
        </div>

        <div>
            <label>Mật khẩu</label>

            <input
                type="password"
                name="password"
                required
            >
        </div>

        <div>
            <label>Số điện thoại</label>

            <input
                type="text"
                name="phone"
            >
        </div>

        <button type="submit">
            Đăng ký
        </button>

    </form>

    <p>
        Đã có tài khoản?
        <a href="<?= BASE_URL ?>/login">Đăng nhập</a>
    </p>

</body>

</html>
