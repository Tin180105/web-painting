<?php require __DIR__ . "/../../layouts/admin_header.php"; ?>

    <h1>Thêm danh mục</h1>

    <?php if (!empty($message)): ?>
        <p class="address-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div class="admin-form-card">

        <form action="<?= BASE_URL ?>/admin/categories/create" method="POST">

            <div class="form-field">
                <label>Tên danh mục</label>
                <input type="text" name="category_name" required>
            </div>

            <div class="form-field">
                <label>Mô tả</label>
                <textarea name="description"></textarea>
            </div>

            <div class="form-field">
                <label>Hình ảnh</label>
                <input type="text" name="image" placeholder="Đường dẫn hình ảnh">
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-form-submit">Thêm danh mục</button>
                <a href="<?= BASE_URL ?>/admin/categories" class="admin-form-back">Hủy, quay lại</a>
            </div>

        </form>

    </div>

<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>