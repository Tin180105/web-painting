<?php require __DIR__ . "/../../layouts/header.php"; ?>

    <h1>Địa chỉ giao hàng</h1>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <a href="<?= BASE_URL ?>/addresses/create" class="btn">Thêm địa chỉ mới</a>

    <br><br>

    <?php if (empty($addresses)): ?>

        <p>Bạn chưa có địa chỉ nào.</p>

    <?php else: ?>

        <div class="addr-list">

            <?php foreach ($addresses as $address): ?>

                <div class="addr-card" data-address-row data-address-id="<?= $address["address_id"] ?>">

                    <?php if ($address["is_default"]): ?>
                        <span class="badge-default">Mặc định</span>
                    <?php endif; ?>

                    <p><strong><?= htmlspecialchars($address["receiver_name"]) ?></strong> | <?= htmlspecialchars($address["phone"]) ?></p>

                    <p>
                        <?= htmlspecialchars($address["address_detail"]) ?>,
                        <?= htmlspecialchars($address["ward"]) ?>,
                        <?= htmlspecialchars($address["district"]) ?>,
                        <?= htmlspecialchars($address["province"]) ?>
                    </p>

                    <div class="addr-actions">

                        <a href="<?= BASE_URL ?>/addresses/edit/<?= $address["address_id"] ?>">Sửa</a>

                        <?php if (!$address["is_default"]): ?>
                            <button type="button" data-set-default>Đặt làm mặc định</button>
                        <?php endif; ?>

                        <button type="button" class="btn-remove" data-address-delete>Xóa</button>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

<?php require __DIR__ . "/../../layouts/footer.php"; ?>
