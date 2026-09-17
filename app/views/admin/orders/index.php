<?php
if (!isset($orders)) { $orders = []; }
if (!isset($status)) { $status = ""; }
if (!isset($message)) { $message = ""; }

require __DIR__ . "/../../layouts/admin_header.php";
?>

    <div class="admin-topbar">
        <div>
            <p class="admin-topbar-eyebrow">Quản lý</p>
            <h1>Đơn hàng</h1>
            <p class="admin-topbar-sub">Theo dõi và cập nhật trạng thái các đơn hàng của khách.</p>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <p class="address-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <section class="admin-panel">

        <div class="admin-panel-head">
            <h2>Danh sách đơn hàng</h2>

            <form method="GET" action="<?= BASE_URL ?>/admin/orders" class="admin-chart-filter">
                <select name="status" onchange="this.form.submit()">
                    <option value="">-- Tất cả trạng thái --</option>
                    <?php foreach (["pending", "confirmed", "shipping", "completed", "cancelled"] as $s): [$label, ] = orderStatusMeta($s); ?>
                        <option value="<?= $s ?>" <?= $status === $s ? "selected" : "" ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <?php if (empty($orders)): ?>

            <p class="admin-panel-empty">Không có đơn hàng nào phù hợp.</p>

        <?php else: ?>

            <table class="admin-table">
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th></th>
                </tr>

                <?php foreach ($orders as $order): [$statusLabel, $statusClass] = orderStatusMeta($order["status"]); ?>
                    <tr>
                        <td>#<?= $order["order_id"] ?></td>
                        <td>
                            <?= htmlspecialchars($order["full_name"]) ?>
                            <br><small style="color:var(--muted)"><?= htmlspecialchars($order["email"]) ?></small>
                        </td>
                        <td><?= number_format($order["total_amount"], 0, ",", ".") ?> đ</td>
                        <td>
                            <span class="status-badge <?= $order["payment_status"] === "paid" ? "status-completed" : "status-pending" ?>">
                                <?= $order["payment_status"] === "paid" ? "Đã thanh toán" : "Chưa thanh toán" ?>
                            </span>
                        </td>
                        <td><span class="status-badge <?= $statusClass ?>"><?= $statusLabel ?></span></td>
                        <td><?= date("d/m/Y H:i", strtotime($order["created_at"])) ?></td>
                        <td><a href="<?= BASE_URL ?>/admin/orders/<?= $order["order_id"] ?>" class="admin-table-link">Xem</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>

        <?php endif; ?>

    </section>

<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>