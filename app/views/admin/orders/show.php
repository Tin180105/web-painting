<?php
if (!isset($order)) { $order = []; }
if (!isset($details)) { $details = []; }
if (!isset($message)) { $message = ""; }

[$statusLabel, $statusClass] = orderStatusMeta($order["status"] ?? "pending");

require __DIR__ . "/../../layouts/admin_header.php";
?>

    <div class="admin-topbar">
        <div>
            <p class="admin-topbar-eyebrow">Đơn hàng</p>
            <h1>Chi tiết đơn hàng #<?= $order["order_id"] ?></h1>
            <p class="admin-topbar-sub">Đặt lúc <?= date("d/m/Y H:i", strtotime($order["created_at"] ?? "now")) ?></p>
        </div>

        <a href="<?= BASE_URL ?>/admin/orders" class="admin-view-store">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Quay lại danh sách
        </a>
    </div>

    <?php if (!empty($message)): ?>
        <p class="address-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div class="admin-dashboard-columns">

        <section class="admin-panel">

            <div class="admin-panel-head">
                <h2>Sản phẩm trong đơn</h2>
                <span class="status-badge <?= $statusClass ?>"><?= $statusLabel ?></span>
            </div>

            <table class="admin-table">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>

                <?php foreach ($details as $item): ?>
                    <tr>
                        <td class="cart-product">
                            <img
                                src="<?= htmlspecialchars($item["image"] ?: "https://via.placeholder.com/60") ?>"
                                width="46"
                                style="border-radius:6px"
                            >
                            <?= htmlspecialchars($item["painting_name"]) ?>
                        </td>
                        <td><?= number_format($item["price"], 0, ",", ".") ?> đ</td>
                        <td><?= (int) $item["quantity"] ?></td>
                        <td><?= number_format($item["price"] * $item["quantity"], 0, ",", ".") ?> đ</td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="cart-total">
                Tổng cộng: <strong><?= number_format($order["total_amount"], 0, ",", ".") ?> đ</strong>
            </div>

        </section>

        <aside>

            <section class="admin-panel" style="margin-bottom:20px">

                <div class="admin-panel-head">
                    <h2>Khách hàng &amp; giao hàng</h2>
                </div>

                <p><strong><?= htmlspecialchars($order["receiver_name"] ?? "") ?></strong></p>
                <p style="color:var(--muted)"><?= htmlspecialchars($order["phone"] ?? "") ?></p>

                <p style="margin-top:12px">
                    <?= htmlspecialchars($order["address_detail"] ?? "") ?>,
                    <?= htmlspecialchars($order["ward"] ?? "") ?>,
                    <?= htmlspecialchars($order["district"] ?? "") ?>,
                    <?= htmlspecialchars($order["province"] ?? "") ?>
                </p>

                <?php if (!empty($order["note"])): ?>
                    <p style="margin-top:12px"><strong>Ghi chú:</strong> <?= htmlspecialchars($order["note"]) ?></p>
                <?php endif; ?>

                <hr style="border:none;border-top:1px solid var(--line);margin:16px 0">

                <p>
                    Thanh toán:
                    <span class="status-badge <?= $order["payment_status"] === "paid" ? "status-completed" : "status-pending" ?>">
                        <?= $order["payment_status"] === "paid" ? "Đã thanh toán" : "Chưa thanh toán" ?>
                    </span>
                </p>
                <p style="margin-top:8px;color:var(--muted);font-size:14px">
                    Phương thức: <?= $order["payment_method"] === "bank_transfer" ? "Chuyển khoản ngân hàng" : "Thanh toán khi nhận hàng (COD)" ?>
                </p>

            </section>

            <section class="admin-panel">

                <div class="admin-panel-head">
                    <h2>Cập nhật trạng thái</h2>
                </div>

                <form method="POST" action="<?= BASE_URL ?>/admin/orders/<?= $order["order_id"] ?>/status">

                    <div class="form-field">
                        <select
                            name="status"
                            style="width:100%;min-height:42px;padding:0 12px;border:1px solid var(--line-dark);border-radius:6px;font:inherit"
                        >
                            <?php foreach (["pending", "confirmed", "shipping", "completed", "cancelled"] as $s): [$label, ] = orderStatusMeta($s); ?>
                                <option value="<?= $s ?>" <?= $order["status"] === $s ? "selected" : "" ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="admin-form-submit" style="width:100%;margin-top:12px">
                        Cập nhật trạng thái
                    </button>

                </form>

            </section>

        </aside>

    </div>

<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>