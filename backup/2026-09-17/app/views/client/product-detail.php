<?php require __DIR__ . "/../layouts/header.php"; ?>

    <a href="<?= BASE_URL ?>/">&larr; Quay lại danh sách</a>

    <div class="detail">

        <img
            src="<?= htmlspecialchars($painting["image"] ?: "https://via.placeholder.com/500x400?text=No+Image") ?>"
            alt="<?= htmlspecialchars($painting["painting_name"]) ?>"
            class="detail-img"
        >

        <div class="detail-info">

            <h1><?= htmlspecialchars($painting["painting_name"]) ?></h1>

            <p class="prod-cat">Danh mục: <?= htmlspecialchars($painting["category_name"]) ?></p>

            <?php if (!empty($painting["artist"])): ?>
                <p>Họa sĩ: <?= htmlspecialchars($painting["artist"]) ?></p>
            <?php endif; ?>

            <?php if (!empty($painting["material"])): ?>
                <p>Chất liệu: <?= htmlspecialchars($painting["material"]) ?></p>
            <?php endif; ?>

            <?php if (!empty($painting["width"]) && !empty($painting["height"])): ?>
                <p>Kích thước: <?= htmlspecialchars($painting["width"]) ?> x <?= htmlspecialchars($painting["height"]) ?> cm</p>
            <?php endif; ?>

            <p class="price"><?= number_format($painting["price"], 0, ",", ".") ?> đ</p>

            <p><?= nl2br(htmlspecialchars($painting["description"] ?? "")) ?></p>

            <?php if ($painting["status"] === "out_of_stock" || $painting["quantity"] <= 0): ?>

                <p><strong>Sản phẩm tạm hết hàng</strong></p>

            <?php else: ?>

                <p>Còn lại: <?= (int) $painting["quantity"] ?> sản phẩm</p>

                <div class="qty">
                    <button type="button" data-qty-decrease>-</button>
                    <input type="number" id="detail-quantity" value="1" min="1" max="<?= (int) $painting["quantity"] ?>">
                    <button type="button" data-qty-increase>+</button>
                </div>

                <button
                    class="btn btn-add-cart"
                    data-add-to-cart
                    data-painting-id="<?= $painting["painting_id"] ?>"
                    data-quantity-input="detail-quantity"
                >
                    Thêm vào giỏ
                </button>

            <?php endif; ?>

        </div>

    </div>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
