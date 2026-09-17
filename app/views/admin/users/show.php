<?php
if (!isset($user)) { $user = []; }

require __DIR__ . "/../../layouts/admin_header.php";
?>

    <div class="admin-topbar">
        <div>
            <p class="admin-topbar-eyebrow">Tài khoản</p>
            <h1><?= htmlspecialchars($user["full_name"]) ?></h1>
        </div>

        <a href="<?= BASE_URL ?>/admin/users" class="admin-view-store">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Quay lại danh sách
        </a>
    </div>

    <section class="admin-panel" style="max-width:640px">

        <div class="admin-panel-head">
            <h2>Thông tin tài khoản</h2>
            <span class="status-badge <?= $user["status"] === "locked" ? "status-cancelled" : "status-completed" ?>">
                <?= $user["status"] === "locked" ? "Đã khóa" : "Hoạt động" ?>
            </span>
        </div>

        <p><strong>Email:</strong> <?= htmlspecialchars($user["email"]) ?></p>
        <p><strong>Điện thoại:</strong> <?= htmlspecialchars($user["phone"] ?? "Chưa cập nhật") ?></p>
        <p><strong>Vai trò:</strong> <?= $user["role"] === "admin" ? "Quản trị viên" : "Khách hàng" ?></p>
        <p><strong>Ngày tạo:</strong> <?= date("d/m/Y H:i", strtotime($user["created_at"])) ?></p>

        <?php if ((int) $user["user_id"] !== (int) $_SESSION["user_id"]): ?>
            <hr style="border:none;border-top:1px solid var(--line);margin:18px 0">

            <?php if ($user["status"] === "locked"): ?>
                <a
                    href="<?= BASE_URL ?>/admin/users/unlock/<?= $user["user_id"] ?>"
                    class="admin-form-submit"
                    style="display:inline-block;text-decoration:none"
                    data-confirm-action
                    data-confirm-message="Mở khóa tài khoản này?"
                    data-success-message="Đã mở khóa tài khoản"
                >
                    Mở khóa tài khoản
                </a>
            <?php else: ?>
                <a
                    href="<?= BASE_URL ?>/admin/users/lock/<?= $user["user_id"] ?>"
                    class="admin-form-submit"
                    style="display:inline-block;text-decoration:none"
                    data-confirm-action
                    data-confirm-message="Khóa tài khoản này? Người dùng sẽ không thể đăng nhập."
                    data-success-message="Đã khóa tài khoản"
                >
                    Khóa tài khoản
                </a>
            <?php endif; ?>
        <?php endif; ?>

    </section>

<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>