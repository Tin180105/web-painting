<?php require __DIR__ . "/../layouts/header.php"; ?>

    <h1>Danh sách tranh</h1>

    <form class="filter-bar" method="GET" action="<?= BASE_URL ?>/">

        <select name="category_id">
            <option value="">-- Tất cả danh mục --</option>
            <?php foreach ($categories as $category): ?>
                <option
                    value="<?= $category["category_id"] ?>"
                    <?= (string) $categoryId === (string) $category["category_id"] ? "selected" : "" ?>
                >
                    <?= htmlspecialchars($category["category_name"]) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input
            type="text"
            name="keyword"
            placeholder="Tìm theo tên tranh..."
            value="<?= htmlspecialchars($keyword) ?>"
        >

        <select name="sort">
            <option value="newest" <?= $sort === "newest" ? "selected" : "" ?>>Mới nhất</option>
            <option value="price_asc" <?= $sort === "price_asc" ? "selected" : "" ?>>Giá tăng dần</option>
            <option value="price_desc" <?= $sort === "price_desc" ? "selected" : "" ?>>Giá giảm dần</option>
        </select>

        <button type="submit">Lọc</button>

    </form>

    <?php if (empty($paintings)): ?>

        <p>Không tìm thấy tranh nào phù hợp.</p>

    <?php else: ?>

        <div class="product-grid">

            <?php foreach ($paintings as $painting): ?>

                <div class="product-card">

                    <a href="<?= BASE_URL ?>/products/<?= $painting["painting_id"] ?>">
                        <img
                            src="<?= htmlspecialchars($painting["image"] ?: "https://via.placeholder.com/300x220?text=No+Image") ?>"
                            alt="<?= htmlspecialchars($painting["painting_name"]) ?>"
                            class="product-image"
                        >
                    </a>

                    <div class="product-info">

                        <a href="<?= BASE_URL ?>/products/<?= $painting["painting_id"] ?>" class="product-name">
                            <?= htmlspecialchars($painting["painting_name"]) ?>
                        </a>

                        <p class="product-category"><?= htmlspecialchars($painting["category_name"]) ?></p>

                        <p class="product-price"><?= number_format($painting["price"], 0, ",", ".") ?> đ</p>

                        <?php if ($painting["status"] === "out_of_stock" || $painting["quantity"] <= 0): ?>

                            <button class="btn btn-disabled" disabled>Hết hàng</button>

                        <?php else: ?>

                            <button
                                class="btn btn-add-cart"
                                data-add-to-cart
                                data-painting-id="<?= $painting["painting_id"] ?>"
                            >
                                Thêm vào giỏ
                            </button>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
