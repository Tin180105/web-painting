<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Cart.php";
require_once __DIR__ . "/../models/Painting.php";

class CartController extends Controller
{
    private $pdo;
    private $cartModel;
    private $paintingModel;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->cartModel = new Cart($pdo);
        $this->paintingModel = new Painting($pdo);
    }

    // GET /cart - trang xem giỏ hàng
    public function index()
    {
        requireLogin();

        $cartId = $this->cartModel->getOrCreateCartId($_SESSION["user_id"]);

        $items = $this->cartModel->getItems($cartId);
        $total = $this->cartModel->getTotal($cartId);

        $this->render("client/cart", [
            "items" => $items,
            "total" => $total,
            "cartCount" => $this->cartModel->countItems($cartId)
        ]);
    }

    // POST /cart/add - JS gọi AJAX khi bấm "Thêm vào giỏ" (ở trang chủ hoặc trang chi tiết)
    public function add()
    {
        requireLogin();

        $paintingId = (int) ($_POST["painting_id"] ?? 0);
        $quantity = max(1, (int) ($_POST["quantity"] ?? 1));

        $painting = $this->paintingModel->getById($paintingId);

        if (!$painting) {
            $this->json(["success" => false, "message" => "Sản phẩm không tồn tại"], 404);
        }

        if ($painting["status"] !== "available") {
            $this->json(["success" => false, "message" => "Sản phẩm hiện không có sẵn"], 400);
        }

        if ($quantity > $painting["quantity"]) {
            $this->json(["success" => false, "message" => "Số lượng vượt quá tồn kho"], 400);
        }

        $cartId = $this->cartModel->getOrCreateCartId($_SESSION["user_id"]);
        $this->cartModel->addItem($cartId, $paintingId, $quantity);

        $this->json([
            "success" => true,
            "message" => "Đã thêm vào giỏ hàng",
            "cartCount" => $this->cartModel->countItems($cartId)
        ]);
    }

    // POST /cart/update - JS gọi AJAX khi bấm nút +/- số lượng trong giỏ hàng
    public function update()
    {
        requireLogin();

        $cartDetailId = (int) ($_POST["cart_detail_id"] ?? 0);
        $quantity = (int) ($_POST["quantity"] ?? 1);

        $cartId = $this->cartModel->getOrCreateCartId($_SESSION["user_id"]);
        $item = $this->cartModel->findItem($cartDetailId, $cartId);

        if (!$item) {
            $this->json(["success" => false, "message" => "Không tìm thấy sản phẩm trong giỏ"], 404);
        }

        // Số lượng <= 0 -> coi như xóa luôn sản phẩm này
        if ($quantity < 1) {
            $this->cartModel->removeItem($cartDetailId, $cartId);

            $this->json([
                "success" => true,
                "removed" => true,
                "total" => $this->cartModel->getTotal($cartId),
                "cartCount" => $this->cartModel->countItems($cartId)
            ]);
        }

        if ($quantity > $item["stock"]) {
            $this->json(["success" => false, "message" => "Số lượng vượt quá tồn kho"], 400);
        }

        $this->cartModel->updateQuantity($cartDetailId, $cartId, $quantity);

        $this->json([
            "success" => true,
            "removed" => false,
            "subtotal" => $quantity * $item["price"],
            "total" => $this->cartModel->getTotal($cartId),
            "cartCount" => $this->cartModel->countItems($cartId)
        ]);
    }

    // POST /cart/delete - JS gọi AJAX khi bấm nút "Xóa" 1 sản phẩm khỏi giỏ
    public function delete()
    {
        requireLogin();

        $cartDetailId = (int) ($_POST["cart_detail_id"] ?? 0);

        $cartId = $this->cartModel->getOrCreateCartId($_SESSION["user_id"]);
        $item = $this->cartModel->findItem($cartDetailId, $cartId);

        if (!$item) {
            $this->json(["success" => false, "message" => "Không tìm thấy sản phẩm trong giỏ"], 404);
        }

        $this->cartModel->removeItem($cartDetailId, $cartId);

        $this->json([
            "success" => true,
            "message" => "Đã xóa sản phẩm khỏi giỏ hàng",
            "total" => $this->cartModel->getTotal($cartId),
            "cartCount" => $this->cartModel->countItems($cartId)
        ]);
    }
}
