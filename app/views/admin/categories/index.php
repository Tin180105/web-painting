<?php

if (!isset($categories)) {
    $categories = [];
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý danh mục</title>
</head>

<body>

    <h1>Quản lý danh mục</h1>

    <a href="<?= url("admin/categories/create") ?>">Thêm danh mục</a>

    <br><br>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <table border="1" cellpadding="10">

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

                <td>
                    <?= $category["category_id"] ?>
                </td>

                <td>
                    <?= htmlspecialchars($category["category_name"]) ?>
                </td>

                <td>
                    <?= htmlspecialchars($category["description"] ?? "") ?>
                </td>

                <td>
                    <?php if (!empty($category["image"])): ?>
                        <img
                            src="<?= htmlspecialchars($category["image"]) ?>"
                            width="100"
                        >
                    <?php endif; ?>
                </td>

                <td>
                    <?= $category["created_at"] ?>
                </td>

                <td>
                    <a href="<?= url("admin/categories/edit/" . $category["category_id"]) ?>">
                        Sửa
                    </a>

                    |

                    <a
                        href="<?= url("admin/categories/delete/" . $category["category_id"]) ?>"
                        onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')"
                    >
                        Xóa
                    </a>
                </td>

            </tr>

        <?php endforeach; ?>

    </table>

</body>

</html>