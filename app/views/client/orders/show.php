<?php
if (!isset($order)) { $order = []; }
if (!isset($details)) { $details = []; }
if (!isset($message)) { $message = ""; }

require __DIR__ . "/../../layouts/header.php"; ?>

    <h1>Chi tiết đơn hàng #<?= $order["order_id"] ?></h1>

    <?php if (!empty($message)): ?>
        <p style="color:green"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <p>Trạng thái: <strong><?= htmlspecialchars($order["status"]) ?></strong></p>
    <p>Thanh toán: <strong><?= $order["payment_status"] === "paid" ? "Đã thanh toán" : "Chưa thanh toán" ?></strong></p>
    <p>Người nhận: <?= htmlspecialchars($order["receiver_name"] ?? "") ?> - <?= htmlspecialchars($order["phone"] ?? "") ?></p>
    <p>Địa chỉ: <?= htmlspecialchars($order["address_detail"] ?? "") ?>, <?= htmlspecialchars($order["ward"] ?? "") ?>, <?= htmlspecialchars($order["district"] ?? "") ?>, <?= htmlspecialchars($order["province"] ?? "") ?></p>

    <?php if (!empty($order["note"])): ?>
        <p>Ghi chú: <?= htmlspecialchars($order["note"]) ?></p>
    <?php endif; ?>

    <table class="cart-table">
        <tr>
            <th>Sản phẩm</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>

        <?php foreach ($details as $item): ?>
            <tr>
                <td class="cart-prod">
                    <img src="<?= htmlspecialchars($item["image"] ?: "https://via.placeholder.com/60") ?>" width="60">
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

    <?php if ($order["payment_status"] !== "paid"): ?>
        <br>
        <a href="<?= BASE_URL ?>/orders/<?= $order["order_id"] ?>/pay" class="btn checkout">Thanh toán ngay</a>
    <?php endif; ?>

    <br><br>
    <a href="<?= BASE_URL ?>/orders">&larr; Quay lại danh sách đơn hàng</a>

<?php require __DIR__ . "/../../layouts/footer.php"; ?>