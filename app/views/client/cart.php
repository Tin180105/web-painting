<?php require __DIR__ . "/../layouts/header.php"; ?>

    <h1>Giỏ hàng của bạn</h1>

    <?php if (empty($items)): ?>

        <p>Giỏ hàng đang trống. <a href="<?= BASE_URL ?>/">Tiếp tục mua sắm</a></p>

    <?php else: ?>

        <table class="cart-table" id="cart-table">

            <tr>
                <th>Sản phẩm</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th></th>
            </tr>

            <?php foreach ($items as $item): ?>

                <tr data-cart-row data-cart-detail-id="<?= $item["cart_detail_id"] ?>">

                    <td class="cart-product">
                        <img
                            src="<?= htmlspecialchars($item["image"] ?: "https://via.placeholder.com/80") ?>"
                            width="60"
                        >
                        <?= htmlspecialchars($item["painting_name"]) ?>
                    </td>

                    <td data-unit-price="<?= $item["price"] ?>">
                        <?= number_format($item["price"], 0, ",", ".") ?> đ
                    </td>

                    <td>
                        <div class="quantity-box">
                            <button type="button" data-cart-qty-decrease>-</button>
                            <input
                                type="number"
                                class="cart-qty-input"
                                value="<?= $item["quantity"] ?>"
                                min="1"
                                max="<?= $item["stock"] ?>"
                                data-cart-qty-input
                            >
                            <button type="button" data-cart-qty-increase>+</button>
                        </div>
                    </td>

                    <td data-subtotal>
                        <?= number_format($item["quantity"] * $item["price"], 0, ",", ".") ?> đ
                    </td>

                    <td>
                        <button type="button" class="btn btn-remove" data-cart-remove>Xóa</button>
                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

        <div class="cart-total">
            Tổng cộng: <strong id="cart-total"><?= number_format($total, 0, ",", ".") ?> đ</strong>
        </div>

        <a href="<?= BASE_URL ?>/addresses" class="btn btn-checkout">Tiến hành đặt hàng</a>

    <?php endif; ?>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
