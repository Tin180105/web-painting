<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Painting.php";
require_once __DIR__ . "/../models/Category.php";
require_once __DIR__ . "/../models/PaintingImage.php";

class AdminPaintingController extends Controller
{
    private $paintingModel;
    private $categoryModel;
    private $paintingImageModel;

    public function __construct($pdo)
    {
        $this->paintingModel = new Painting($pdo);
        $this->categoryModel = new Category($pdo);
        $this->paintingImageModel = new PaintingImage($pdo);
    }

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

        $uploadedPaths = $this->uploadImages("images");

        if ($uploadedPaths === false) {
            $this->render("admin/paintings/create", [
                "categories" => $this->categoryModel->getAll(),
                "message" => "Ảnh không hợp lệ (chỉ nhận jpg, png, gif, webp, dung lượng dưới 2MB mỗi ảnh)",
                "activeMenu" => "paintings",
                "pageTitle" => "Thêm sản phẩm"
            ]);
            return;
        }

        $data["image"] = $uploadedPaths[0] ?? "";

        $paintingId = $this->paintingModel->create($data);

        if (!empty($uploadedPaths)) {
            $this->paintingImageModel->addImages($paintingId, $uploadedPaths);
        }

        $this->redirect("/admin/paintings?message=" . urlencode("Thêm sản phẩm thành công"));
    }

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
            "galleryImages" => $this->paintingImageModel->getByPaintingId($id),
            "message" => "",
            "activeMenu" => "paintings",
            "pageTitle" => "Sửa sản phẩm"
        ]);
    }

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
                "galleryImages" => $this->paintingImageModel->getByPaintingId($id),
                "message" => "Vui lòng nhập đầy đủ thông tin bắt buộc (danh mục, tên tranh, giá)",
                "activeMenu" => "paintings",
                "pageTitle" => "Sửa sản phẩm"
            ]);
            return;
        }

        $uploadedPaths = $this->uploadImages("images");

        if ($uploadedPaths === false) {
            $this->render("admin/paintings/edit", [
                "painting" => array_merge($painting, $_POST),
                "categories" => $this->categoryModel->getAll(),
                "galleryImages" => $this->paintingImageModel->getByPaintingId($id),
                "message" => "Ảnh không hợp lệ (chỉ nhận jpg, png, gif, webp, dung lượng dưới 2MB mỗi ảnh)",
                "activeMenu" => "paintings",
                "pageTitle" => "Sửa sản phẩm"
            ]);
            return;
        }

        $deleteIds = [];

        if (!empty($_POST["delete_images"])) {
            foreach ($_POST["delete_images"] as $value) {
                $imageId = (int) $value;

                if ($imageId > 0) {
                    $deleteIds[] = $imageId;
                }
            }
        }

        $currentImage = $painting["image"] ?? "";

        foreach ($deleteIds as $imageId) {
            $image = $this->paintingImageModel->getById($imageId);

            if (!$image || (int) $image["painting_id"] !== (int) $id) {
                continue;
            }

            $this->deleteUploadedImageIfLocal($image["image_path"]);
            $this->paintingImageModel->deleteById($imageId);

            if ($image["image_path"] === $currentImage) {
                $currentImage = "";
            }
        }

        if (!empty($uploadedPaths)) {
            $this->paintingImageModel->addImages($id, $uploadedPaths);

            if ($currentImage === "") {
                $currentImage = $uploadedPaths[0];
            }
        }

        if ($currentImage === "") {
            $remaining = $this->paintingImageModel->getByPaintingId($id);
            $currentImage = $remaining[0]["image_path"] ?? "";
        }

        $data["image"] = $currentImage;

        $this->paintingModel->update($id, $data);

        $this->redirect("/admin/paintings?message=" . urlencode("Cập nhật sản phẩm thành công"));
    }

    public function delete($id)
    {
        requireAdmin();

        try {

            $painting = $this->paintingModel->getById($id);

            if (!$painting) {
                $this->redirect("/admin/paintings?message=" . urlencode("Không tìm thấy sản phẩm"));
                return;
            }

            $galleryImages = $this->paintingImageModel->getByPaintingId($id);
            foreach ($galleryImages as $image) {
                $this->deleteUploadedImageIfLocal($image["image_path"]);
            }

            $this->paintingModel->delete($id);

            $this->deleteUploadedImageIfLocal($painting["image"] ?? "");

            $this->redirect("/admin/paintings?message=" . urlencode("Xóa sản phẩm thành công"));

        } catch (PDOException $e) {

            $this->redirect("/admin/paintings?message=" . urlencode(
                "Không thể xóa sản phẩm này (có thể đang thuộc đơn hàng đã đặt)"
            ));
        }
    }

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
            "status" => in_array($status, ["available", "out_of_stock", "hidden"], true) ? $status : "available"
        ];
    }

    private function uploadImages($fieldName)
    {
        if (empty($_FILES[$fieldName]["name"][0])) {
            return [];
        }

        $files = $_FILES[$fieldName];
        $count = count($files["name"]);

        $allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
        $allowedExt = ["jpg", "jpeg", "png", "gif", "webp"];
        $maxSize = 2 * 1024 * 1024;

        for ($i = 0; $i < $count; $i++) {
            if ($files["error"][$i] !== UPLOAD_ERR_OK) {
                return false;
            }

            if (!in_array($files["type"][$i], $allowedTypes, true)) {
                return false;
            }

            if ($files["size"][$i] > $maxSize) {
                return false;
            }

            $ext = strtolower(pathinfo($files["name"][$i], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExt, true)) {
                return false;
            }
        }

        $uploadDir = __DIR__ . "/../../public/uploads/paintings/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $savedPaths = [];

        for ($i = 0; $i < $count; $i++) {
            $ext = strtolower(pathinfo($files["name"][$i], PATHINFO_EXTENSION));
            $fileName = uniqid("painting_") . "_" . $i . "." . $ext;

            if (!move_uploaded_file($files["tmp_name"][$i], $uploadDir . $fileName)) {
                return false;
            }

            $savedPaths[] = BASE_URL . "/uploads/paintings/" . $fileName;
        }

        return $savedPaths;
    }

    private function deleteUploadedImageIfLocal($imagePath)
    {
        if (empty($imagePath) || strpos($imagePath, "/uploads/paintings/") === false) {
            return;
        }

        $fileName = basename($imagePath);
        $fullPath = __DIR__ . "/../../public/uploads/paintings/" . $fileName;

        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}