<?php
if (!isset($items)) { $items = []; }
if (!isset($total)) { $total = 0; }
if (!isset($addresses)) { $addresses = []; }
if (!isset($message)) { $message = ""; }

require __DIR__ . "/../layouts/header.php"; ?>

    <h1>Xác nhận đơn hàng</h1>

    <?php if (!empty($message)): ?>
        <p style="color:red"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <table class="cart-table">
        <tr>
            <th>Sản phẩm</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>

        <?php foreach ($items as $item): ?>
            <tr>
                <td class="cart-prod">
                    <img src="<?= htmlspecialchars($item["image"] ?: "https://via.placeholder.com/60") ?>" width="60">
                    <?= htmlspecialchars($item["painting_name"]) ?>
                </td>
                <td><?= number_format($item["price"], 0, ",", ".") ?> đ</td>
                <td><?= (int) $item["quantity"] ?></td>
                <td><?= number_format($item["quantity"] * $item["price"], 0, ",", ".") ?> đ</td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="cart-total">
        Tổng cộng: <strong><?= number_format($total, 0, ",", ".") ?> đ</strong>
    </div>

    <br>

    <?php if (empty($addresses)): ?>

        <p>
            Bạn chưa có địa chỉ giao hàng nào.
            <a href="<?= BASE_URL ?>/addresses/create">Thêm địa chỉ ngay</a>
        </p>

    <?php else: ?>

        <form method="POST" action="<?= BASE_URL ?>/checkout">

            <h3>Địa chỉ giao hàng</h3>

            <?php foreach ($addresses as $address): ?>
                <div>
                    <label>
                        <input
                            type="radio"
                            name="address_id"
                            value="<?= $address["address_id"] ?>"
                            <?= $address["is_default"] ? "checked" : "" ?>
                        >
                        <strong><?= htmlspecialchars($address["receiver_name"]) ?></strong> | <?= htmlspecialchars($address["phone"]) ?> -
                        <?= htmlspecialchars($address["address_detail"]) ?>, <?= htmlspecialchars($address["ward"]) ?>,
                        <?= htmlspecialchars($address["district"]) ?>, <?= htmlspecialchars($address["province"]) ?>
                    </label>
                </div>
            <?php endforeach; ?>

            <br>

            <div>
                <label>Phương thức thanh toán</label>
                <select name="payment_method">
                    <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                    <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                </select>
            </div>

            <div>
                <label>Ghi chú</label>
                <textarea name="note" placeholder="Ghi chú cho đơn hàng (tùy chọn)"></textarea>
            </div>

            <br>

            <button type="submit" class="btn checkout">Đặt hàng</button>

        </form>

    <?php endif; ?>

<?php require __DIR__ . "/../layouts/footer.php"; ?>