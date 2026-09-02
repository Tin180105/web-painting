<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Category.php";

class CategoryController extends Controller
{
    private $categoryModel;

    public function __construct($conn)
    {
        $this->categoryModel = new Category($conn);
    }

    // Danh sách category
    public function index()
    {
        $this->requireAdmin();

        $categories = $this->categoryModel->getAll();

        $this->view("admin/categories/index", [
            "categories" => $categories,
            "message" => $_GET["message"] ?? ""
        ]);
    }

    // Form thêm category
    public function create()
    {
        $this->requireAdmin();

        $this->view("admin/categories/create", [
            "message" => ""
        ]);
    }

    // Xử lý thêm category
    public function store()
    {
        $this->requireAdmin();

        $result = $this->processCreate();

        if ($result["success"]) {
            $this->redirect("admin/categories");
        }

        $this->view("admin/categories/create", [
            "message" => $result["message"]
        ]);
    }

    private function processCreate()
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

        $categories = $this->categoryModel->getAll();

        foreach ($categories as $item) {
            if (strtolower($item["category_name"]) === strtolower($categoryName)) {
                return [
                    "success" => false,
                    "message" => "Tên danh mục đã tồn tại"
                ];
            }
        }

        $this->categoryModel->create($categoryName, $description, $image);

        return [
            "success" => true,
            "message" => "Thêm danh mục thành công"
        ];
    }

    // Form sửa category
    public function edit($id)
    {
        $this->requireAdmin();

        $category = $this->categoryModel->getById($id);

        if (!$category) {
            die("Không tìm thấy danh mục");
        }

        $this->view("admin/categories/edit", [
            "category" => $category,
            "message" => ""
        ]);
    }

    // Xử lý sửa category
    public function update($id)
    {
        $this->requireAdmin();

        $category = $this->categoryModel->getById($id);

        if (!$category) {
            die("Không tìm thấy danh mục");
        }

        $result = $this->processUpdate($id);

        if ($result["success"]) {
            $this->redirect("admin/categories");
        }

        $this->view("admin/categories/edit", [
            "category" => $this->categoryModel->getById($id),
            "message" => $result["message"]
        ]);
    }

    private function processUpdate($id)
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

        $this->categoryModel->update($id, $categoryName, $description, $image);

        return [
            "success" => true,
            "message" => "Cập nhật danh mục thành công"
        ];
    }

    // Xóa category
    public function delete($id)
    {
        $this->requireAdmin();

        try {
            $category = $this->categoryModel->getById($id);

            if ($category) {
                $this->categoryModel->delete($id);
                $message = "Xóa danh mục thành công";
            } else {
                $message = "Không tìm thấy danh mục";
            }

        } catch (PDOException $e) {
            $message = "Không thể xóa danh mục vì đang có tranh thuộc danh mục này";
        }

        $this->redirect("admin/categories?message=" . urlencode($message));
    }
}