<?php
if (!isset($message)) { $message = ""; }

require __DIR__ . "/../../layouts/admin_header.php"; ?>

    <h1>Thêm tài khoản</h1>
    <div class="admin-modal-source">
    <?php if (!empty($message)): ?>
        <p class="address-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div class="admin-form-card">

        <form action="<?= BASE_URL ?>/admin/users/create" method="POST">

            <div class="form-field">
                <label>Họ tên</label>
                <input type="text" name="full_name" required>
            </div>

            <div class="form-field">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-field">
                <label>Mật khẩu</label>
                <input type="password" name="password" minlength="6" required>
            </div>

            <div class="form-field">
                <label>Điện thoại</label>
                <input type="text" name="phone">
            </div>

            <div class="form-field">
                <label>Vai trò</label>
                <select name="role">
                    <option value="customer">Khách hàng</option>
                    <option value="admin">Quản trị viên</option>
                </select>
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-form-submit">Thêm tài khoản</button>
                <a href="<?= BASE_URL ?>/admin/users" class="admin-form-back">Hủy, quay lại</a>
            </div>

        </form>

    </div>
    </div>
<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>
