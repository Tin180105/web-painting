<?php
if (!isset($categories)) { $categories = []; }
if (!isset($message)) { $message = ""; }

require __DIR__ . "/../../layouts/admin_header.php"; ?>

    <h1>Quản lý danh mục</h1>

    <a href="<?= BASE_URL ?>/admin/categories/create" class="btn" data-modal-form data-modal-title="Thêm danh mục">
        Thêm danh mục
    </a>

    <br><br>

    <div id="admin-list-container">

        <?php if (!empty($message)): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <table class="admin-table">

            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>

            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= $category["category_id"] ?></td>
                    <td><?= htmlspecialchars($category["category_name"]) ?></td>
                    <td><?= htmlspecialchars($category["description"] ?? "") ?></td>
                    <td><?= date("d/m/Y", strtotime($category["created_at"])) ?></td>
                    <td>
                        <a
                            href="<?= BASE_URL ?>/admin/categories/edit/<?= $category["category_id"] ?>"
                            class="admin-table-link"
                            data-modal-form
                            data-modal-title="Sửa danh mục"
                        >
                            Sửa
                        </a>
                        |
                        <a
                            href="<?= BASE_URL ?>/admin/categories/delete/<?= $category["category_id"] ?>"
                            class="admin-table-link"
                            data-modal-delete
                        >
                            Xóa
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>

    </div>

<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>