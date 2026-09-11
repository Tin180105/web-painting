<?php require __DIR__ . "/../../layouts/header.php"; ?>

    <h1>Đơn hàng của tôi</h1>

    <?php if (empty($orders)): ?>

        <p>Bạn chưa có đơn hàng nào.</p>

    <?php else: ?>

        <table class="cart-table">
            <tr>
                <th>Mã đơn</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Thanh toán</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>

            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= $order["order_id"] ?></td>
                    <td><?= $order["created_at"] ?></td>
                    <td><?= number_format($order["total_amount"], 0, ",", ".") ?> đ</td>
                    <td><?= $order["payment_status"] === "paid" ? "Đã thanh toán" : "Chưa thanh toán" ?></td>
                    <td><?= htmlspecialchars($order["status"]) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/orders/<?= $order["order_id"] ?>" class="order-link">Xem</a>
                        <?php if ($order["payment_status"] !== "paid"): ?>
                            | <a href="<?= BASE_URL ?>/orders/<?= $order["order_id"] ?>/pay">Thanh toán</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php endif; ?>

<?php require __DIR__ . "/../../layouts/footer.php"; ?>