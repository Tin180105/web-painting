<?php
if (!isset($paintings)) { $paintings = []; }
if (!isset($message)) { $message = ""; }

// Nhãn + màu badge cho trạng thái sản phẩm (dùng lại class có sẵn trong admin.css)
function paintingStatusMeta($status)
{
    $map = [
        "available" => ["Còn hàng", "status-completed"],
        "out_of_stock" => ["Hết hàng", "status-cancelled"],
        "hidden" => ["Đã ẩn", "status-pending"],
    ];

    return $map[$status] ?? [$status, "status-pending"];
}

require __DIR__ . "/../../layouts/admin_header.php"; ?>

    <h1>Quản lý sản phẩm</h1>

    <a href="<?= BASE_URL ?>/admin/paintings/create" class="btn">Thêm sản phẩm</a>

    <br><br>

    <?php if (!empty($message)): ?>
        <p class="address-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (empty($paintings)): ?>

        <p class="admin-panel-empty">Chưa có sản phẩm nào.</p>

    <?php else: ?>

        <table class="admin-table">

            <tr>
                <th>Ảnh</th>
                <th>Tên tranh</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Tồn kho</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>

            <?php foreach ($paintings as $painting): [$statusLabel, $statusClass] = paintingStatusMeta($painting["status"]); ?>
                <tr>
                    <td>
                        <img
                            src="<?= htmlspecialchars($painting["image"] ?: "https://via.placeholder.com/60") ?>"
                            width="60"
                            style="border-radius:6px;object-fit:cover;"
                        >
                    </td>
                    <td><?= htmlspecialchars($painting["painting_name"]) ?></td>
                    <td><?= htmlspecialchars($painting["category_name"]) ?></td>
                    <td><?= number_format($painting["price"], 0, ",", ".") ?> đ</td>
                    <td><?= (int) $painting["quantity"] ?></td>
                    <td><span class="status-badge <?= $statusClass ?>"><?= $statusLabel ?></span></td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/paintings/edit/<?= $painting["painting_id"] ?>" class="admin-table-link">Sửa</a>
                        |
                        <a href="<?= BASE_URL ?>/admin/paintings/delete/<?= $painting["painting_id"] ?>"
                           class="admin-table-link"
                           onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                            Xóa
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php endif; ?>

<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>