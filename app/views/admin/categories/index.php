<?php
if (!isset($categories)) { $categories = []; }
if (!isset($message)) { $message = ""; }

require __DIR__ . "/../../layouts/admin_header.php"; ?>

    <h1>Quản lý danh mục</h1>

    <a href="<?= BASE_URL ?>/admin/categories/create" class="btn">Thêm danh mục</a>

    <br><br>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <table class="admin-table">

        <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Mô tả</th>
            <th>Hình ảnh</th>
            <th>Ngày tạo</th>
            <th>Thao tác</th>
        </tr>

        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= $category["category_id"] ?></td>
                <td><?= htmlspecialchars($category["category_name"]) ?></td>
                <td><?= htmlspecialchars($category["description"] ?? "") ?></td>
                <td>
                    <?php if (!empty($category["image"])): ?>
                        <img src="<?= htmlspecialchars($category["image"]) ?>" width="80">
                    <?php endif; ?>
                </td>
                <td><?= $category["created_at"] ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/admin/categories/edit/<?= $category["category_id"] ?>">Sửa</a>
                    |
                    <a href="<?= BASE_URL ?>/admin/categories/delete/<?= $category["category_id"] ?>"
                       onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                        Xóa
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

<?php require __DIR__ . "/../../layouts/admin_footer.php"; ?>