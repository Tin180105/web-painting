<?php require __DIR__ . "/../../layouts/header.php"; ?>

    <section class="addr-form-page">
        <div class="addr-head">
            <p class="section-kicker">DELIVERY DETAILS</p>
            <h1>Sửa địa chỉ giao hàng</h1>
            <p>Cập nhật thông tin để đơn hàng tiếp theo được giao đến đúng nơi bạn mong muốn.</p>
        </div>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form class="addr-form" method="POST" action="<?= BASE_URL ?>/addresses/edit/<?= $address["address_id"] ?>">

        <div class="addr-field">
            <label>Người nhận</label>
            <input type="text" name="receiver_name" value="<?= htmlspecialchars($address["receiver_name"]) ?>" required>
        </div>

        <div class="addr-field">
            <label>Số điện thoại</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($address["phone"]) ?>" required>
        </div>

        <div class="addr-field addr-wide">
            <label>Địa chỉ chi tiết (số nhà, đường...)</label>
            <input type="text" name="address_detail" value="<?= htmlspecialchars($address["address_detail"]) ?>" required>
        </div>

        <div class="addr-field">
            <label>Phường/Xã</label>
            <input type="text" name="ward" value="<?= htmlspecialchars($address["ward"] ?? "") ?>">
        </div>

        <div class="addr-field">
            <label>Quận/Huyện</label>
            <input type="text" name="district" value="<?= htmlspecialchars($address["district"] ?? "") ?>">
        </div>

        <div class="addr-field">
            <label>Tỉnh/Thành phố</label>
            <input type="text" name="province" value="<?= htmlspecialchars($address["province"] ?? "") ?>">
        </div>

        <div class="addr-default">
            <label>
                <input type="checkbox" name="is_default" value="1" <?= $address["is_default"] ? "checked" : "" ?>>
                Đặt làm địa chỉ mặc định
            </label>
        </div>

        <button class="addr-submit" type="submit">Cập nhật địa chỉ</button>

    </form>

        <a class="addr-back" href="<?= BASE_URL ?>/addresses">Quay lại danh sách địa chỉ</a>
    </section>

<?php require __DIR__ . "/../../layouts/footer.php"; ?>
