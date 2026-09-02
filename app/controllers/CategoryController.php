<?php

require_once __DIR__ . "/../models/Category.php";

class CategoryController
{
    private $categoryModel;

    public function __construct($conn)
    {
        $this->categoryModel = new Category($conn);
    }

    // Danh sách category
    public function index()
    {
        return $this->categoryModel->getAll();
    }

    // Chi tiết category
    public function show($id)
    {
        return $this->categoryModel->getById($id);
    }

    // Thêm category
    public function create()
    {
        $categoryName = trim($_POST["category_name"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $image = trim($_POST["image"] ?? "");

        if ($categoryName === "") {
            return [
                "success" => false,
                "message" => "Tên danh mục không được để trống"
            ];
        }

        $category = $this->categoryModel->getAll();

        foreach ($category as $item) {
            if (strtolower($item["category_name"]) === strtolower($categoryName)) {
                return [
                    "success" => false,
                    "message" => "Tên danh mục đã tồn tại"
                ];
            }
        }

        $this->categoryModel->create(
            $categoryName,
            $description,
            $image
        );

        return [
            "success" => true,
            "message" => "Thêm danh mục thành công"
        ];
    }

    // Sửa category
    public function update($id)
    {
        $categoryName = trim($_POST["category_name"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $image = trim($_POST["image"] ?? "");

        if ($categoryName === "") {
            return [
                "success" => false,
                "message" => "Tên danh mục không được để trống"
            ];
        }

        $category = $this->categoryModel->getById($id);

        if (!$category) {
            return [
                "success" => false,
                "message" => "Không tìm thấy danh mục"
            ];
        }

        $this->categoryModel->update(
            $id,
            $categoryName,
            $description,
            $image
        );

        return [
            "success" => true,
            "message" => "Cập nhật danh mục thành công"
        ];
    }

    // Xóa category
    public function delete($id)
    {
        try {

            $category = $this->categoryModel->getById($id);

            if (!$category) {
                return [
                    "success" => false,
                    "message" => "Không tìm thấy danh mục"
                ];
            }

            $this->categoryModel->delete($id);

            return [
                "success" => true,
                "message" => "Xóa danh mục thành công"
            ];

        } catch (PDOException $e) {

            return [
                "success" => false,
                "message" => "Không thể xóa danh mục vì đang có tranh thuộc danh mục này"
            ];
        }
    }
}