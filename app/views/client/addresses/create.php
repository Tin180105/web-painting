<?php require __DIR__ . "/../../layouts/header.php"; ?>

    <h1>Thêm địa chỉ giao hàng</h1>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/addresses/create">

        <div>
            <label>Người nhận</label>
            <input type="text" name="receiver_name" required>
        </div>

        <div>
            <label>Số điện thoại</label>
            <input type="text" name="phone" required>
        </div>

        <div>
            <label>Địa chỉ chi tiết (số nhà, đường...)</label>
            <input type="text" name="address_detail" required>
        </div>

        <div>
            <label>Phường/Xã</label>
            <input type="text" name="ward">
        </div>

        <div>
            <label>Quận/Huyện</label>
            <input type="text" name="district">
        </div>

        <div>
            <label>Tỉnh/Thành phố</label>
            <input type="text" name="province">
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_default" value="1">
                Đặt làm địa chỉ mặc định
            </label>
        </div>

        <br>

        <button type="submit">Lưu địa chỉ</button>

    </form>

    <br>

    <a href="<?= BASE_URL ?>/addresses">Quay lại</a>

<?php require __DIR__ . "/../../layouts/footer.php"; ?>
