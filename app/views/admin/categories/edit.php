<?php
if (!isset($category)) {
    $category = [];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa danh mục</title>
</head>

<body>

    <h1>Sửa danh mục</h1>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form action="<?= url("admin/categories/edit/" . $category["category_id"]) ?>" method="POST">

        <div>
            <label>Tên danh mục</label>
            <input
                type="text"
                name="category_name"
                value="<?= htmlspecialchars($category["category_name"] ?? "") ?>"
                required
            >
        </div>

        <br>

        <div>
            <label>Mô tả</label>
            <textarea name="description"><?= htmlspecialchars($category["description"] ?? "") ?></textarea>
        </div>

        <br>

        <div>
            <label>Hình ảnh</label>
            <input
                type="text"
                name="image"
                value="<?= htmlspecialchars($category["image"] ?? "") ?>"
            >
        </div>

        <br>

        <button type="submit">
            Cập nhật
        </button>

    </form>

    <br>

    <a href="<?= url("admin/categories") ?>">
        Quay lại
    </a>

</body>

</html>