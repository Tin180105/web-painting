<?php
if (!isset($order)) { $order = []; }
if (!isset($message)) { $message = ""; }

require __DIR__ . "/../../layouts/header.php"; ?>

    <h1>Thanh toán đơn hàng #<?= $order["order_id"] ?></h1>

    <?php if (!empty($message)): ?>
        <p style="color:red"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <p>Tổng tiền cần thanh toán: <strong><?= number_format($order["total_amount"], 0, ",", ".") ?> đ</strong></p>

    <p>Đây là bước thanh toán giả lập: nhập đúng số tiền ở trên rồi bấm "Thanh toán" để mô phỏng thanh toán thành công.</p>

    <form method="POST" action="<?= BASE_URL ?>/orders/<?= $order["order_id"] ?>/pay">

        <div>
            <label>Số tiền thanh toán</label>
            <input type="number" name="amount" step="1000" min="0" required>
        </div>

        <br>

        <button type="submit" class="btn btn-checkout">Thanh toán</button>

    </form>

<?php require __DIR__ . "/../../layouts/footer.php"; ?>