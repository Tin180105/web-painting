<?php 
if (!isset($orders)) { $orders = []; }
if (!isset($status)) { $status = ""; }
if (!isset($message)) { $message = ""; } 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
</head>
<body>

    <h1>Quản lý đơn hàng</h1>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="GET" action="<?= BASE_URL ?>/admin/orders">
        <select name="status" onchange="this.form.submit()">
            <option value="">-- Tất cả trạng thái --</option>
            <?php foreach (["pending", "confirmed", "shipping", "completed", "cancelled"] as $s): ?>
                <option value="<?= $s ?>" <?= $status === $s ? "selected" : "" ?>><?= $s ?></option>
            <?php endforeach; ?>
        </select>
    </form>

    <br>

    <table border="1" cellpadding="10">
        <tr>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Tổng tiền</th>
            <th>Thanh toán</th>
            <th>Trạng thái</th>
            <th>Ngày đặt</th>
            <th>Thao tác</th>
        </tr>

        <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?= $order["order_id"] ?></td>
                <td><?= htmlspecialchars($order["full_name"]) ?> (<?= htmlspecialchars($order["email"]) ?>)</td>
                <td><?= number_format($order["total_amount"], 0, ",", ".") ?> đ</td>
                <td><?= $order["payment_status"] === "paid" ? "Đã thanh toán" : "Chưa thanh toán" ?></td>
                <td><?= htmlspecialchars($order["status"]) ?></td>
                <td><?= $order["created_at"] ?></td>
                <td><a href="<?= BASE_URL ?>/admin/orders/<?= $order["order_id"] ?>">Xem</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>