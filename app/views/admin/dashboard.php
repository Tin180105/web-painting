<?php
if (!isset($outOfStockCount)) { $outOfStockCount = 0; }
if (!isset($lowStockPaintings)) { $lowStockPaintings = []; }
if (!isset($recentOrders)) { $recentOrders = []; }

// Nhãn + màu hiển thị cho từng trạng thái đơn hàng
function orderStatusMeta($status)
{
    $map = [
        "pending"   => ["Chờ xử lý", "status-pending"],
        "confirmed" => ["Đã xác nhận", "status-confirmed"],
        "shipping"  => ["Đang giao", "status-shipping"],
        "completed" => ["Hoàn thành", "status-completed"],
        "cancelled" => ["Đã hủy", "status-cancelled"],
    ];

    return $map[$status] ?? [$status, "status-pending"];
}

require __DIR__ . "/../layouts/admin_header.php";
?>

    <div class="admin-topbar">
        <div>
            <p class="admin-topbar-eyebrow">Tổng quan</p>
            <h1>Xin chào, <?= htmlspecialchars($_SESSION["full_name"] ?? "Admin") ?></h1>
            <p class="admin-topbar-sub">Tình hình cửa hàng hôm nay, <?= date("d/m/Y") ?>.</p>
        </div>

        <a href="<?= BASE_URL ?>/" target="_blank" class="admin-view-store">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            Xem cửa hàng
        </a>
    </div>

    <div class="admin-stats-grid">

        <div class="admin-stat-card">
            <span class="stat-icon stat-icon-teal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
            </span>
            <p class="admin-stat-label">Tổng đơn hàng</p>
            <p class="admin-stat-value"><?= $totalOrders ?></p>
        </div>

        <div class="admin-stat-card">
            <span class="stat-icon stat-icon-gold">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </span>
            <p class="admin-stat-label">Đơn chờ xử lý</p>
            <p class="admin-stat-value"><?= $pendingCount ?></p>
        </div>

        <div class="admin-stat-card">
            <span class="stat-icon stat-icon-coral">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"></path></svg>
            </span>
            <p class="admin-stat-label">Doanh thu (đã thanh toán)</p>
            <p class="admin-stat-value"><?= number_format($totalRevenue, 0, ",", ".") ?> đ</p>
        </div>

        <div class="admin-stat-card">
            <span class="stat-icon stat-icon-ink">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="M21 15l-5-5L5 21"></path></svg>
            </span>
            <p class="admin-stat-label">Sản phẩm</p>
            <p class="admin-stat-value"><?= $totalPaintings ?></p>
        </div>

        <div class="admin-stat-card">
            <span class="stat-icon stat-icon-teal-dark">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
            </span>
            <p class="admin-stat-label">Danh mục</p>
            <p class="admin-stat-value"><?= $totalCategories ?></p>
        </div>

        <div class="admin-stat-card">
            <span class="stat-icon stat-icon-danger">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            </span>
            <p class="admin-stat-label">Hết hàng</p>
            <p class="admin-stat-value"><?= $outOfStockCount ?></p>
        </div>

    </div>

    <div class="admin-dashboard-columns">

        <section class="admin-panel">

            <div class="admin-panel-head">
                <h2>Đơn hàng gần đây</h2>
                <a href="<?= BASE_URL ?>/admin/orders">Xem tất cả</a>
            </div>

            <?php if (empty($recentOrders)): ?>

                <p class="admin-panel-empty">Chưa có đơn hàng nào.</p>

            <?php else: ?>

                <table class="admin-table">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>

                    <?php foreach ($recentOrders as $order): [$statusLabel, $statusClass] = orderStatusMeta($order["status"]); ?>
                        <tr>
                            <td>#<?= $order["order_id"] ?></td>
                            <td><?= htmlspecialchars($order["full_name"]) ?></td>
                            <td><?= number_format($order["total_amount"], 0, ",", ".") ?> đ</td>
                            <td><span class="status-badge <?= $statusClass ?>"><?= $statusLabel ?></span></td>
                            <td><a href="<?= BASE_URL ?>/admin/orders/<?= $order["order_id"] ?>" class="admin-table-link">Xem</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            <?php endif; ?>

        </section>

        <aside class="admin-panel admin-attention">

            <div class="admin-panel-head">
                <h2>Cần chú ý</h2>
            </div>

            <?php if ($pendingCount > 0): ?>
                <a class="attention-item" href="<?= BASE_URL ?>/admin/orders?status=pending">
                    <span class="attention-icon attention-icon-gold">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </span>
                    <span class="attention-text">
                        <strong><?= $pendingCount ?></strong> đơn đang chờ xử lý
                    </span>
                    <svg class="attention-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            <?php endif; ?>

            <?php if ($outOfStockCount > 0): ?>
                <div class="attention-item attention-item-static">
                    <span class="attention-icon attention-icon-danger">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    </span>
                    <span class="attention-text">
                        <strong><?= $outOfStockCount ?></strong> sản phẩm đã hết hàng
                    </span>
                </div>
            <?php endif; ?>

            <?php if (!empty($lowStockPaintings)): ?>

                <p class="attention-subhead">Sắp hết hàng</p>

                <?php foreach ($lowStockPaintings as $painting): ?>
                    <div class="attention-item attention-item-static">
                        <span class="attention-icon attention-icon-muted">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="M21 15l-5-5L5 21"></path></svg>
                        </span>
                        <span class="attention-text">
                            <?= htmlspecialchars($painting["painting_name"]) ?>
                            <small>còn <?= (int) $painting["quantity"] ?></small>
                        </span>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

            <?php if ($pendingCount === 0 && $outOfStockCount === 0 && empty($lowStockPaintings)): ?>
                <p class="admin-panel-empty">Mọi thứ đều ổn, chưa có gì cần chú ý.</p>
            <?php endif; ?>

        </aside>

    </div>

<?php require __DIR__ . "/../layouts/admin_footer.php"; ?>