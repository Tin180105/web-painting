<?php require __DIR__ . "/../layouts/admin_header.php"; ?>

    <h1>Tổng quan</h1>

    <div class="admin-stats-grid">

        <div class="admin-stat-card">
            <p class="admin-stat-label">Tổng đơn hàng</p>
            <p class="admin-stat-value"><?= $totalOrders ?></p>
        </div>

        <div class="admin-stat-card">
            <p class="admin-stat-label">Đơn chờ xử lý</p>
            <p class="admin-stat-value"><?= $pendingCount ?></p>
        </div>

        <div class="admin-stat-card">
            <p class="admin-stat-label">Doanh thu (đã thanh toán)</p>
            <p class="admin-stat-value"><?= number_format($totalRevenue, 0, ",", ".") ?> đ</p>
        </div>

        <div class="admin-stat-card">
            <p class="admin-stat-label">Sản phẩm</p>
            <p class="admin-stat-value"><?= $totalPaintings ?></p>
        </div>

        <div class="admin-stat-card">
            <p class="admin-stat-label">Danh mục</p>
            <p class="admin-stat-value"><?= $totalCategories ?></p>
        </div>

    </div>

    <h2>Đơn hàng gần đây</h2>

    <table class="admin-table">
        <tr>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th></th>
        </tr>

        <?php if (empty($recentOrders)): ?>
            <tr><td colspan="5">Chưa có đơn hàng nào.</td></tr>
        <?php endif; ?>

        <?php foreach ($recentOrders as $order): ?>
            <tr>
                <td>#<?= $order["order_id"] ?></td>
                <td><?= htmlspecialchars($order["full_name"]) ?></td>
                <td><?= number_format($order["total_amount"], 0, ",", ".") ?> đ</td>
                <td><?= htmlspecialchars($order["status"]) ?></td>
                <td><a href="<?= BASE_URL ?>/admin/orders/<?= $order["order_id"] ?>">Xem</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php require __DIR__ . "/../layouts/admin_footer.php"; ?>