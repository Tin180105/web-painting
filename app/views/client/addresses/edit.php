<?php require __DIR__ . "/../../layouts/header.php"; ?>

    <h1>Sửa địa chỉ giao hàng</h1>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/addresses/edit/<?= $address["address_id"] ?>">

        <div>
            <label>Người nhận</label>
            <input type="text" name="receiver_name" value="<?= htmlspecialchars($address["receiver_name"]) ?>" required>
        </div>

        <div>
            <label>Số điện thoại</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($address["phone"]) ?>" required>
        </div>

        <div>
            <label>Địa chỉ chi tiết (số nhà, đường...)</label>
            <input type="text" name="address_detail" value="<?= htmlspecialchars($address["address_detail"]) ?>" required>
        </div>

        <div>
            <label>Phường/Xã</label>
            <input type="text" name="ward" value="<?= htmlspecialchars($address["ward"] ?? "") ?>">
        </div>

        <div>
            <label>Quận/Huyện</label>
            <input type="text" name="district" value="<?= htmlspecialchars($address["district"] ?? "") ?>">
        </div>

        <div>
            <label>Tỉnh/Thành phố</label>
            <input type="text" name="province" value="<?= htmlspecialchars($address["province"] ?? "") ?>">
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_default" value="1" <?= $address["is_default"] ? "checked" : "" ?>>
                Đặt làm địa chỉ mặc định
            </label>
        </div>

        <br>

        <button type="submit">Cập nhật</button>

    </form>

    <br>

    <a href="<?= BASE_URL ?>/addresses">Quay lại</a>

<?php require __DIR__ . "/../../layouts/footer.php"; ?>
