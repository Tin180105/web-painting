<?php
if (!isset($paintings)) { $paintings = []; }
if (!isset($message)) { $message = ""; }

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

    <a href="<?= BASE_URL ?>/admin/paintings/create" class="btn" data-modal-form data-modal-title="Thêm sản phẩm">
        Thêm sản phẩm
    </a>

    <br><br>

    <div id="admin-list-container">

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
                                onerror="this.onerror=null;this.src='https://via.placeholder.com/60?text=Loi';"
                            >
                        </td>
                        <td><?= htmlspecialchars($painting["painting_name"]) ?></td>
                        <td><?= htmlspecialchars($painting["category_name"]) ?></td>
                        <td><?= number_format($painting["price"], 0, ",", ".") ?> đ</td>
                        <td><?= (int) $painting["quantity"] ?></td>
                        <td><span class="status-badge <?= $statusClass ?>"><?= $statusLabel ?></span></td>
                        <td>
                            <a
                                href="<?= BASE_URL ?>/admin/paintings/edit/<?= $painting["painting_id"] ?>"
                                class="admin-table-link"
                                data-modal-form
                                data-modal-title="Sửa sản phẩm"
                            >
                                Sửa
                            </a>
                            |
                            <a
                                href="<?= BASE_URL ?>/admin/paintings/delete/<?= $painting["painting_id"] ?>"
                                class="admin-table-link"
                                data-modal-delete
                            >
                                Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>

        <?php endif; ?>

    </div>

<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>