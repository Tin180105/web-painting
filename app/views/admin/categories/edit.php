<?php
if (!isset($category)) {
    $category = [];
}

require __DIR__ . "/../../layouts/admin_header.php";
?>

    <h1>Sửa danh mục</h1>

    <div class="admin-modal-source">

        <?php if (!empty($message)): ?>
            <p class="admin-message-error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <div class="admin-form-card">

            <form action="<?= BASE_URL ?>/admin/categories/edit/<?= $category["category_id"] ?>" method="POST">

                <div class="form-field">
                    <label>Tên danh mục</label>
                    <input
                        type="text"
                        name="category_name"
                        value="<?= htmlspecialchars($category["category_name"] ?? "") ?>"
                        required
                    >
                </div>

                <div class="form-field">
                    <label>Mô tả</label>
                    <textarea name="description"><?= htmlspecialchars($category["description"] ?? "") ?></textarea>
                </div>

                <div class="form-field">
                    <label>Hình ảnh</label>
                    <input
                        type="text"
                        name="image"
                        value="<?= htmlspecialchars($category["image"] ?? "") ?>"
                        placeholder="Đường dẫn hình ảnh"
                    >

                    <?php if (!empty($category["image"])): ?>
                        <div class="admin-form-preview">
                            <img src="<?= htmlspecialchars($category["image"]) ?>" alt="Xem trước hình ảnh">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="admin-form-submit">Cập nhật</button>
                    <a href="<?= BASE_URL ?>/admin/categories" class="admin-form-back">Hủy, quay lại</a>
                </div>

            </form>

        </div>

    </div>

<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>