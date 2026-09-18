<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Painting.php";
require_once __DIR__ . "/../models/Category.php";
require_once __DIR__ . "/../models/Cart.php";
require_once __DIR__ . "/../models/PaintingImage.php";

class PaintingController extends Controller
{
    private $pdo;
    private $paintingModel;
    private $categoryModel;
    private $paintingImageModel;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->paintingModel = new Painting($pdo);
        $this->categoryModel = new Category($pdo);
        $this->paintingImageModel = new PaintingImage($pdo);
    }

    public function index()
    {
        $categoryId = $_GET["category_id"] ?? "";
        $keyword = trim($_GET["keyword"] ?? "");
        $sort = $_GET["sort"] ?? "newest";

        $paintings = $this->paintingModel->getAll([
            "category_id" => $categoryId,
            "keyword" => $keyword,
            "sort" => $sort
        ]);

        $categories = $this->categoryModel->getAll();

        $this->render("client/home", [
            "paintings" => $paintings,
            "categories" => $categories,
            "categoryId" => $categoryId,
            "keyword" => $keyword,
            "sort" => $sort,
            "cartCount" => $this->getCartCount()
        ]);
    }

    public function show($id)
    {
        $painting = $this->paintingModel->getById($id);

        if (!$painting || $painting["status"] === "hidden") {
            http_response_code(404);
            die("Không tìm thấy sản phẩm");
        }

        $gallery = $this->paintingImageModel->getByPaintingId($id);

        $images = [];

        if (!empty($painting["image"]) && !$this->isLocalImageMissing($painting["image"])) {
            $images[] = $painting["image"];
        }

        foreach ($gallery as $img) {
            $path = $img["image_path"];

            if (in_array($path, $images, true)) {
                continue;
            }

            if ($this->isLocalImageMissing($path)) {
                continue;
            }

            $images[] = $path;
        }

        $this->render("client/product-detail", [
            "painting" => $painting,
            "images" => $images,
            "mainImage" => $images[0] ?? "",
            "cartCount" => $this->getCartCount()
        ]);
    }

    private function isLocalImageMissing($path)
    {
        if (empty($path)) {
            return true;
        }

        $prefix = BASE_URL . "/uploads/paintings/";

        if (strpos($path, $prefix) !== 0) {
            return false;
        }

        $fullPath = __DIR__ . "/../../public/uploads/paintings/" . basename($path);

        return !is_file($fullPath);
    }

    private function getCartCount()
    {
        if (!isset($_SESSION["user_id"])) {
            return 0;
        }

        $cartModel = new Cart($this->pdo);
        $cartId = $cartModel->getOrCreateCartId($_SESSION["user_id"]);

        return $cartModel->countItems($cartId);
    }
}
