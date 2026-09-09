<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Painting.php";
require_once __DIR__ . "/../models/Category.php";

class AdminPaintingController extends Controller
{
    private $paintingModel;
    private $categoryModel;

    public function __construct($conn)
    {
        $this->paintingModel = new Painting($conn);
        $this->categoryModel = new Category($conn);
    }

    // GET /admin/paintings - danh sách sản phẩm
    public function index()
    {
        requireAdmin();

        $this->render("admin/paintings/index", [
            "paintings" => $this->paintingModel->getAllForAdmin(),
            "message" => $_GET["message"] ?? "",
            "activeMenu" => "paintings",
            "pageTitle" => "Quản lý sản phẩm"
        ]);
    }

    // GET /admin/paintings/create - hiển thị form thêm
    public function create()
    {
        requireAdmin();

        $this->render("admin/paintings/create", [
            "categories" => $this->categoryModel->getAll(),
            "message" => "",
            "activeMenu" => "paintings",
            "pageTitle" => "Thêm sản phẩm"
        ]);
    }

    // POST /admin/paintings/create - xử lý thêm sản phẩm
    public function store()
    {
        requireAdmin();

        $data = $this->getFormData();

        if ($data === null) {
            $this->render("admin/paintings/create", [
                "categories" => $this->categoryModel->getAll(),
                "message" => "Vui lòng nhập đầy đủ thông tin bắt buộc (danh mục, tên tranh, giá)",
                "activeMenu" => "paintings",
                "pageTitle" => "Thêm sản phẩm"
            ]);
            return;
        }

        $this->paintingModel->create($data);

        $this->redirect("/admin/paintings?message=" . urlencode("Thêm sản phẩm thành công"));
    }

    // GET /admin/paintings/edit/{id} - hiển thị form sửa
    public function edit($id)
    {
        requireAdmin();

        $painting = $this->paintingModel->getById($id);

        if (!$painting) {
            die("Không tìm thấy sản phẩm");
        }

        $this->render("admin/paintings/edit", [
            "painting" => $painting,
            "categories" => $this->categoryModel->getAll(),
            "message" => "",
            "activeMenu" => "paintings",
            "pageTitle" => "Sửa sản phẩm"
        ]);
    }

    // POST /admin/paintings/edit/{id} - xử lý cập nhật
    public function update($id)
    {
        requireAdmin();

        $painting = $this->paintingModel->getById($id);

        if (!$painting) {
            die("Không tìm thấy sản phẩm");
        }

        $data = $this->getFormData();

        if ($data === null) {
            $this->render("admin/paintings/edit", [
                "painting" => array_merge($painting, $_POST),
                "categories" => $this->categoryModel->getAll(),
                "message" => "Vui lòng nhập đầy đủ thông tin bắt buộc (danh mục, tên tranh, giá)",
                "activeMenu" => "paintings",
                "pageTitle" => "Sửa sản phẩm"
            ]);
            return;
        }

        $this->paintingModel->update($id, $data);

        $this->redirect("/admin/paintings?message=" . urlencode("Cập nhật sản phẩm thành công"));
    }

    // GET /admin/paintings/delete/{id} - xử lý xóa
    public function delete($id)
    {
        requireAdmin();

        try {

            $painting = $this->paintingModel->getById($id);

            if (!$painting) {
                $this->redirect("/admin/paintings?message=" . urlencode("Không tìm thấy sản phẩm"));
                return;
            }

            $this->paintingModel->delete($id);

            $this->redirect("/admin/paintings?message=" . urlencode("Xóa sản phẩm thành công"));

        } catch (PDOException $e) {

            $this->redirect("/admin/paintings?message=" . urlencode(
                "Không thể xóa sản phẩm này (có thể đang thuộc đơn hàng đã đặt)"
            ));
        }
    }

    // Lấy + validate dữ liệu form (dùng chung cho store/update). Trả null nếu thiếu trường bắt buộc.
    private function getFormData()
    {
        $categoryId = (int) ($_POST["category_id"] ?? 0);
        $paintingName = trim($_POST["painting_name"] ?? "");
        $price = trim($_POST["price"] ?? "");
        $width = trim($_POST["width"] ?? "");
        $height = trim($_POST["height"] ?? "");

        if ($categoryId <= 0 || $paintingName === "" || $price === "" || !is_numeric($price)) {
            return null;
        }

        $status = $_POST["status"] ?? "available";

        return [
            "category_id" => $categoryId,
            "painting_name" => $paintingName,
            "description" => trim($_POST["description"] ?? ""),
            "artist" => trim($_POST["artist"] ?? ""),
            "price" => (float) $price,
            "quantity" => max(0, (int) ($_POST["quantity"] ?? 0)),
            "width" => $width !== "" ? (float) $width : null,
            "height" => $height !== "" ? (float) $height : null,
            "material" => trim($_POST["material"] ?? ""),
            "image" => trim($_POST["image"] ?? ""),
            "status" => in_array($status, ["available", "out_of_stock", "hidden"], true) ? $status : "available"
        ];
    }
}