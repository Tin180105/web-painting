<?php
if (!isset($users)) { $users = []; }
if (!isset($keyword)) { $keyword = ""; }
if (!isset($message)) { $message = ""; }

require __DIR__ . "/../../layouts/admin_header.php";
?>

    <div class="admin-topbar">
        <div>
            <p class="admin-topbar-eyebrow">Quản lý</p>
            <h1>Tài khoản</h1>
            <p class="admin-topbar-sub">Danh sách khách hàng và quản trị viên trong hệ thống.</p>
        </div>
    </div>

    <section class="admin-panel">

        <div class="admin-panel-head">
            <h2>Danh sách tài khoản</h2>

            <form method="GET" action="<?= BASE_URL ?>/admin/users" class="admin-chart-filter">
                <input
                    type="text"
                    name="keyword"
                    placeholder="Tìm theo tên hoặc email..."
                    value="<?= htmlspecialchars($keyword) ?>"
                    style="min-height:36px;padding:0 10px;border:1px solid var(--line-dark);border-radius:6px;font:inherit;font-size:13px"
                >
                <button type="submit" class="admin-form-submit" style="min-height:36px;padding:0 14px">Tìm</button>
            </form>
        </div>

        <div id="admin-list-container">

            <?php if (!empty($message)): ?>
                <p class="address-message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <?php if (empty($users)): ?>

                <p class="admin-panel-empty">Không tìm thấy tài khoản nào.</p>

            <?php else: ?>

                <table class="admin-table">
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>

                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>#<?= $user["user_id"] ?></td>
                            <td><?= htmlspecialchars($user["full_name"]) ?></td>
                            <td><?= htmlspecialchars($user["email"]) ?></td>
                            <td><?= htmlspecialchars($user["phone"] ?? "") ?></td>
                            <td><?= $user["role"] === "admin" ? "Quản trị viên" : "Khách hàng" ?></td>
                            <td>
                                <span class="status-badge <?= $user["status"] === "locked" ? "status-cancelled" : "status-completed" ?>">
                                    <?= $user["status"] === "locked" ? "Đã khóa" : "Hoạt động" ?>
                                </span>
                            </td>
                            <td><?= date("d/m/Y", strtotime($user["created_at"])) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>/admin/users/<?= $user["user_id"] ?>" class="admin-table-link">Xem</a>

                                <?php if ((int) $user["user_id"] !== (int) $_SESSION["user_id"]): ?>
                                    |
                                    <?php if ($user["status"] === "locked"): ?>
                                        
                                            href="<?= BASE_URL ?>/admin/users/unlock/<?= $user["user_id"] ?>"
                                            class="admin-table-link"
                                            data-confirm-action
                                            data-confirm-message="Mở khóa tài khoản này?"
                                            data-success-message="Đã mở khóa tài khoản"
                                        >
                                            Mở khóa
                                        </a>
                                    <?php else: ?>
                                        
                                            href="<?= BASE_URL ?>/admin/users/lock/<?= $user["user_id"] ?>"
                                            class="admin-table-link"
                                            data-confirm-action
                                            data-confirm-message="Khóa tài khoản này? Người dùng sẽ không thể đăng nhập."
                                            data-success-message="Đã khóa tài khoản"
                                        >
                                            Khóa
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            <?php endif; ?>

        </div>

    </section>

<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>