<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Painting.php";
require_once __DIR__ . "/../models/Category.php";
require_once __DIR__ . "/../models/Cart.php";

class PaintingController extends Controller
{
    private $pdo;
    private $paintingModel;
    private $categoryModel;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->paintingModel = new Painting($pdo);
        $this->categoryModel = new Category($pdo);
    }

    // GET / - Trang chủ client: danh sách tranh, lọc theo danh mục/từ khóa/sắp xếp
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

    // GET /products/{id} - Chi tiết 1 tranh
    public function show($id)
    {
        $painting = $this->paintingModel->getById($id);

        if (!$painting || $painting["status"] === "hidden") {
            http_response_code(404);
            die("Không tìm thấy sản phẩm");
        }

        $this->render("client/product-detail", [
            "painting" => $painting,
            "cartCount" => $this->getCartCount()
        ]);
    }

    // Đếm số lượng sản phẩm trong giỏ (hiển thị badge trên navbar), trả 0 nếu chưa đăng nhập
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
