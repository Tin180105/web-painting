<?php
if (!isset($categories)) { $categories = []; }
if (!isset($message)) { $message = ""; }

require __DIR__ . "/../../layouts/admin_header.php"; ?>

    <h1>Thêm sản phẩm</h1>

    <?php if (!empty($message)): ?>
        <p class="address-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div class="admin-form-card">

        <form action="<?= BASE_URL ?>/admin/paintings/create" method="POST">

            <div class="form-field">
                <label>Danh mục</label>
                <select name="category_id" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category["category_id"] ?>">
                            <?= htmlspecialchars($category["category_name"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-field">
                <label>Tên tranh</label>
                <input type="text" name="painting_name" required>
            </div>

            <div class="form-field">
                <label>Họa sĩ</label>
                <input type="text" name="artist">
            </div>

            <div class="form-field">
                <label>Mô tả</label>
                <textarea name="description"></textarea>
            </div>

            <div class="form-field">
                <label>Giá (đ)</label>
                <input type="number" name="price" min="0" step="1000" required>
            </div>

            <div class="form-field">
                <label>Số lượng tồn kho</label>
                <input type="number" name="quantity" min="0" value="0">
            </div>

            <div class="form-field">
                <label>Chiều rộng (cm)</label>
                <input type="number" name="width" min="0" step="0.1">
            </div>

            <div class="form-field">
                <label>Chiều cao (cm)</label>
                <input type="number" name="height" min="0" step="0.1">
            </div>

            <div class="form-field">
                <label>Chất liệu</label>
                <input type="text" name="material">
            </div>

            <div class="form-field">
                <label>Hình ảnh</label>
                <input type="text" name="image" placeholder="Đường dẫn hình ảnh">
            </div>

            <div class="form-field">
                <label>Trạng thái</label>
                <select name="status">
                    <option value="available">Còn hàng</option>
                    <option value="out_of_stock">Hết hàng</option>
                    <option value="hidden">Ẩn</option>
                </select>
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-form-submit">Thêm sản phẩm</button>
                <a href="<?= BASE_URL ?>/admin/paintings" class="admin-form-back">Hủy, quay lại</a>
            </div>

        </form>

    </div>

<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>