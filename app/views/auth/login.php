<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng nhập</title>

    <link rel="stylesheet" href="<?= asset("css/style.css") ?>">
</head>

<body>

    <h1>Đăng nhập</h1>

    <?php if (!empty($message)): ?>

        <p>
            <?= htmlspecialchars($message) ?>
        </p>

    <?php endif; ?>

    <form action="<?= url("login") ?>" method="POST">

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

        <button type="submit">
            Đăng nhập
        </button>

    </form>

    <p>
        Chưa có tài khoản?
        <a href="<?= url("register") ?>">Đăng ký</a>
    </p>

</body>

</html>