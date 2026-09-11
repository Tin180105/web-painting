<?php
if (!isset($painting)) { $painting = []; }
if (!isset($categories)) { $categories = []; }
if (!isset($message)) { $message = ""; }

require __DIR__ . "/../../layouts/admin_header.php"; ?>

    <h1>Sửa sản phẩm</h1>
    <div class="admin-modal-source">
    <?php if (!empty($message)): ?>
        <p class="address-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div class="admin-form-card">

        <form action="<?= BASE_URL ?>/admin/paintings/edit/<?= $painting["painting_id"] ?>" method="POST" enctype="multipart/form-data">

            <div class="form-field">
                <label>Danh mục</label>
                <select name="category_id" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $category): ?>
                        <option
                            value="<?= $category["category_id"] ?>"
                            <?= (string) $category["category_id"] === (string) ($painting["category_id"] ?? "") ? "selected" : "" ?>
                        >
                            <?= htmlspecialchars($category["category_name"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-field">
                <label>Tên tranh</label>
                <input type="text" name="painting_name" value="<?= htmlspecialchars($painting["painting_name"] ?? "") ?>" required>
            </div>

            <div class="form-field">
                <label>Họa sĩ</label>
                <input type="text" name="artist" value="<?= htmlspecialchars($painting["artist"] ?? "") ?>">
            </div>

            <div class="form-field">
                <label>Mô tả</label>
                <textarea name="description"><?= htmlspecialchars($painting["description"] ?? "") ?></textarea>
            </div>

            <div class="form-field">
                <label>Giá (đ)</label>
                <input type="number" name="price" min="0" step="1000" value="<?= htmlspecialchars($painting["price"] ?? "") ?>" required>
            </div>

            <div class="form-field">
                <label>Số lượng tồn kho</label>
                <input type="number" name="quantity" min="0" value="<?= (int) ($painting["quantity"] ?? 0) ?>">
            </div>

            <div class="form-field">
                <label>Chiều rộng (cm)</label>
                <input type="number" name="width" min="0" step="0.1" value="<?= htmlspecialchars($painting["width"] ?? "") ?>">
            </div>

            <div class="form-field">
                <label>Chiều cao (cm)</label>
                <input type="number" name="height" min="0" step="0.1" value="<?= htmlspecialchars($painting["height"] ?? "") ?>">
            </div>

            <div class="form-field">
                <label>Chất liệu</label>
                <input type="text" name="material" value="<?= htmlspecialchars($painting["material"] ?? "") ?>">
            </div>

            <div class="form-field">
                <label>Hình ảnh</label>
                <input type="file" name="image" accept="image/*">
                <p style="margin-top:6px;color:var(--muted);font-size:13px">
                    Để trống nếu muốn giữ nguyên ảnh hiện tại.
                </p>

                <?php if (!empty($painting["image"])): ?>
                    <div class="admin-form-preview">
                        <img src="<?= htmlspecialchars($painting["image"]) ?>" alt="Ảnh hiện tại">
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-field">
                <label>Trạng thái</label>
                <select name="status">
                    <?php foreach (["available" => "Còn hàng", "out_of_stock" => "Hết hàng", "hidden" => "Ẩn"] as $value => $label): ?>
                        <option value="<?= $value ?>" <?= ($painting["status"] ?? "") === $value ? "selected" : "" ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-form-submit">Cập nhật</button>
                <a href="<?= BASE_URL ?>/admin/paintings" class="admin-form-back">Hủy, quay lại</a>
            </div>

        </form>

    </div>
</div>
<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>