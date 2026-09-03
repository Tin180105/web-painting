<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm danh mục</title>
</head>

<body>

    <h1>Thêm danh mục</h1>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/admin/categories/create" method="POST">

        <div>
            <label>Tên danh mục</label>
            <input
                type="text"
                name="category_name"
                required
            >
        </div>

        <br>

        <div>
            <label>Mô tả</label>
            <textarea name="description"></textarea>
        </div>

        <br>

        <div>
            <label>Hình ảnh</label>
            <input
                type="text"
                name="image"
                placeholder="Đường dẫn hình ảnh"
            >
        </div>

        <br>

        <button type="submit">
            Thêm danh mục
        </button>

    </form>

    <br>

    <a href="<?= BASE_URL ?>/admin/categories">
        Quay lại
    </a>

</body>

</html>
