<?php
if (!isset($user)) { $user = []; }
if (!isset($message)) { $message = ""; }

$isSelf = (int) ($user["user_id"] ?? 0) === (int) ($_SESSION["user_id"] ?? 0);

require __DIR__ . "/../../layouts/admin_header.php"; ?>

    <h1>Sửa tài khoản</h1>
    <div class="admin-modal-source">
    <?php if (!empty($message)): ?>
        <p class="address-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div class="admin-form-card">

        <form action="<?= BASE_URL ?>/admin/users/edit/<?= $user["user_id"] ?>" method="POST">

            <div class="form-field">
                <label>Họ tên</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($user["full_name"] ?? "") ?>" required>
            </div>

            <div class="form-field">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user["email"] ?? "") ?>" required>
            </div>

            <div class="form-field">
                <label>Mật khẩu mới</label>
                <input type="password" name="password" minlength="6">
                <p style="margin-top:6px;color:var(--muted);font-size:13px">
                    Để trống nếu không muốn đổi mật khẩu.
                </p>
            </div>

            <div class="form-field">
                <label>Điện thoại</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user["phone"] ?? "") ?>">
            </div>

            <div class="form-field">
                <label>Vai trò</label>
                <select name="role" <?= $isSelf ? "disabled" : "" ?>>
                    <option value="customer" <?= ($user["role"] ?? "") === "customer" ? "selected" : "" ?>>Khách hàng</option>
                    <option value="admin" <?= ($user["role"] ?? "") === "admin" ? "selected" : "" ?>>Quản trị viên</option>
                </select>
                <?php if ($isSelf): ?>
                    <p style="margin-top:6px;color:var(--muted);font-size:13px">
                        Không thể tự đổi vai trò của chính mình.
                    </p>
                <?php endif; ?>
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-form-submit">Cập nhật</button>
                <a href="<?= BASE_URL ?>/admin/users" class="admin-form-back">Hủy, quay lại</a>
            </div>

        </form>

    </div>
    </div>
<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>
