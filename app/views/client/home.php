<?php require __DIR__ . "/../layouts/header.php"; ?>

<section class="hero">
    <div class="hero-wrap">
        <div class="hero-copy">
            <p class="hero-tag">PAINTING SHOP</p>
            <h2>
                Nghệ thuật<br>
                <span>chạm đến cảm xúc</span>
            </h2>
            <p class="hero-desc">
                Khám phá những tác phẩm nghệ thuật độc đáo, mang vẻ đẹp và cảm xúc vào không gian sống của bạn.
            </p>
            <a href="#painting-list" class="hero-btn">
                Khám phá tranh
            </a>
        </div>
        <div class="hero-art">
            <div class="frame frame-1">
                <img
                    src="<?= BASE_URL ?>/images/banner-painting-1.png"
                    alt="Tranh nghệ thuật"
                >
            </div>
            <div class="frame frame-2">
                <img
                    src="<?= BASE_URL ?>/images/banner-painting-2.png"
                    alt="Tranh nghệ thuật"
                >
            </div>
            <div class="frame frame-3">
                <img
                    src="<?= BASE_URL ?>/images/banner-painting-3.png"
                    alt="Tranh nghệ thuật"
                >
            </div>
        </div>
    </div>
</section>

<section id="painting-list" class="paint-sec">

    <div class="paint-head">
        <p class="section-kicker">BỘ SƯU TẬP</p>
        <h1>Danh sách tranh</h1>
        <p>
            Những tác phẩm được chọn lọc để mang nghệ thuật đến gần hơn với không gian của bạn.
        </p>
    </div>
    <form class="filters" method="GET" action="<?= BASE_URL ?>/">
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
            <option
                value="newest"
                <?= $sort === "newest" ? "selected" : "" ?>
            >
                Mới nhất
            </option>
            <option
                value="price_asc"
                <?= $sort === "price_asc" ? "selected" : "" ?>
            >
                Giá tăng dần
            </option>
            <option
                value="price_desc"
                <?= $sort === "price_desc" ? "selected" : "" ?>
            >
                Giá giảm dần
            </option>
        </select>
        <button type="submit">
            Lọc
        </button>
    </form>
    <?php if (empty($paintings)): ?>
        <p class="empty">
            Không tìm thấy tranh nào phù hợp.
        </p>
    <?php else: ?>
        <div class="products">
            <?php foreach ($paintings as $painting): ?>
                <div class="product">
                    <a href="<?= BASE_URL ?>/products/<?= $painting["painting_id"] ?>">
                        <img
                            src="<?= htmlspecialchars(
                                $painting["image"]
                                    ?: "https://via.placeholder.com/300x220?text=No+Image"
                            ) ?>"
                            alt="<?= htmlspecialchars($painting["painting_name"]) ?>"
                            class="prod-img"
                        >
                    </a>
                    <div class="prod-info">
                        <a
                            href="<?= BASE_URL ?>/products/<?= $painting["painting_id"] ?>"
                            class="prod-name"
                        >
                            <?= htmlspecialchars($painting["painting_name"]) ?>
                        </a>

                        <p class="prod-cat">
                            <?= htmlspecialchars($painting["category_name"]) ?>
                        </p>

                        <p class="price">
                            <?= number_format($painting["price"], 0, ",", ".") ?> đ
                        </p>
                        <?php if (
                            $painting["status"] === "out_of_stock"
                            || $painting["quantity"] <= 0
                        ): ?>
                            <button
                                class="btn btn-disabled"
                                disabled
                            >
                                Hết hàng
                            </button>
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
</section>

<?php require __DIR__ . "/../layouts/footer.php"; ?>