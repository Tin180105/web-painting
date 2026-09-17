<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Category.php";

class CategoryController extends Controller
{
    private $categoryModel;

    public function __construct($pdo)
    {
        $this->categoryModel = new Category($pdo);
    }

    // GET /admin/categories - danh sách category
    public function index()
    {
        requireAdmin();

        $categories = $this->categoryModel->getAll();

        $this->render("admin/categories/index", [
            "categories" => $categories,
            "message" => $_GET["message"] ?? ""
        ]);
    }

        // GET /admin/categories/create - hiển thị form thêm
    public function create()
    {
        requireAdmin();

        $this->render("admin/categories/create", [
            "message" => "",
            "activeMenu" => "categories",
            "pageTitle" => "Thêm danh mục"
        ]);
    }
    // POST /admin/categories/create - xử lý thêm category
    public function store()
    {
        requireAdmin();

        $categoryName = trim($_POST["category_name"] ?? "");
        $description = trim($_POST["description"] ?? "");

        if ($categoryName === "") {
            $this->render("admin/categories/create", [
                "message" => "Tên danh mục không được để trống",
                "activeMenu" => "categories",
                "pageTitle" => "Thêm danh mục"
            ]);
            return;
        }

        foreach ($this->categoryModel->getAll() as $item) {
            if (strtolower($item["category_name"]) === strtolower($categoryName)) {
                $this->render("admin/categories/create", [
                    "message" => "Tên danh mục đã tồn tại",
                    "activeMenu" => "categories",
                    "pageTitle" => "Thêm danh mục"
                ]);
                return;
            }
        }

        $this->categoryModel->create($categoryName, $description);

        $this->redirect("/admin/categories");
    }


    // GET /admin/categories/edit/{id} - hiển thị form sửa
    public function edit($id)
    {
        requireAdmin();

        $category = $this->categoryModel->getById($id);

        if (!$category) {
            die("Không tìm thấy danh mục");
        }

        $this->render("admin/categories/edit", [
            "category" => $category,
            "message" => "",
            "activeMenu" => "categories",
            "pageTitle" => "Sửa danh mục"
        ]);
    }

// POST /admin/categories/edit/{id} - xử lý cập nhật
public function update($id)
{
    requireAdmin();

    $categoryName = trim($_POST["category_name"] ?? "");
    $description = trim($_POST["description"] ?? "");

    $category = $this->categoryModel->getById($id);

    if (!$category) {
        die("Không tìm thấy danh mục");
    }

    if ($categoryName === "") {
        $this->render("admin/categories/edit", [
            "category" => $category,
            "message" => "Tên danh mục không được để trống",
            "activeMenu" => "categories",
            "pageTitle" => "Sửa danh mục"
        ]);
        return;
    }

    $this->categoryModel->update($id, $categoryName, $description);

    $this->redirect("/admin/categories");
}

    // GET /admin/categories/delete/{id} - xử lý xóa
    public function delete($id)
    {
        requireAdmin();

        try {

            $category = $this->categoryModel->getById($id);

            if (!$category) {
                $this->redirect("/admin/categories?message=" . urlencode("Không tìm thấy danh mục"));
                return;
            }

            $this->categoryModel->delete($id);

            $this->redirect("/admin/categories?message=" . urlencode("Xóa danh mục thành công"));

        } catch (PDOException $e) {

            $this->redirect("/admin/categories?message=" . urlencode(
                "Không thể xóa danh mục vì đang có tranh thuộc danh mục này"
            ));
        }
    }
}
