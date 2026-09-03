<?php
if (!isset($order)) { $order = []; }
if (!isset($details)) { $details = []; }
if (!isset($message)) { $message = ""; }

require __DIR__ . "/../../layouts/header.php"; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>
</head>
<body>

    <h1>Chi tiết đơn hàng #<?= $order["order_id"] ?></h1>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <p>Người nhận: <?= htmlspecialchars($order["receiver_name"] ?? "") ?> - <?= htmlspecialchars($order["phone"] ?? "") ?></p>
    <p>Địa chỉ: <?= htmlspecialchars($order["address_detail"] ?? "") ?>, <?= htmlspecialchars($order["ward"] ?? "") ?>, <?= htmlspecialchars($order["district"] ?? "") ?>, <?= htmlspecialchars($order["province"] ?? "") ?></p>
    <p>Thanh toán: <?= $order["payment_status"] === "paid" ? "Đã thanh toán" : "Chưa thanh toán" ?> (<?= htmlspecialchars($order["payment_method"]) ?>)</p>

    <table border="1" cellpadding="10">
        <tr>
            <th>Sản phẩm</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>
        <?php foreach ($details as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item["painting_name"]) ?></td>
                <td><?= number_format($item["price"], 0, ",", ".") ?> đ</td>
                <td><?= (int) $item["quantity"] ?></td>
                <td><?= number_format($item["price"] * $item["quantity"], 0, ",", ".") ?> đ</td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p>Tổng cộng: <strong><?= number_format($order["total_amount"], 0, ",", ".") ?> đ</strong></p>

    <form method="POST" action="<?= BASE_URL ?>/admin/orders/<?= $order["order_id"] ?>/status">
        <label>Cập nhật trạng thái</label>
        <select name="status">
            <?php foreach (["pending", "confirmed", "shipping", "completed", "cancelled"] as $s): ?>
                <option value="<?= $s ?>" <?= $order["status"] === $s ? "selected" : "" ?>><?= $s ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Cập nhật</button>
    </form>

    <br>
    <a href="<?= BASE_URL ?>/admin/orders">Quay lại</a>

</body>
</html>