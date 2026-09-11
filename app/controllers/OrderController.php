<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Order.php";
require_once __DIR__ . "/../models/Cart.php";
require_once __DIR__ . "/../models/Address.php";
require_once __DIR__ . "/../models/Painting.php";

class OrderController extends Controller
{
    private $conn;
    private $orderModel;
    private $cartModel;
    private $addressModel;
    private $paintingModel;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->orderModel = new Order($conn);
        $this->cartModel = new Cart($conn);
        $this->addressModel = new Address($conn);
        $this->paintingModel = new Painting($conn);
    }

    // GET /checkout - trang xác nhận đơn hàng trước khi đặt
    public function checkout()
    {
        requireLogin();

        $cartId = $this->cartModel->getOrCreateCartId($_SESSION["user_id"]);
        $items = $this->cartModel->getItems($cartId);

        if (empty($items)) {
            $this->redirect("/cart");
        }

        $this->render("client/checkout", [
            "items" => $items,
            "total" => $this->cartModel->getTotal($cartId),
            "addresses" => $this->addressModel->getAllByUserId($_SESSION["user_id"]),
            "message" => ""
        ]);
    }

    // POST /checkout - tạo đơn hàng từ giỏ hàng
    public function store()
    {
        requireLogin();

        $userId = $_SESSION["user_id"];
        $addressId = (int) ($_POST["address_id"] ?? 0);
        $paymentMethod = ($_POST["payment_method"] ?? "cod") === "bank_transfer" ? "bank_transfer" : "cod";
        $note = trim($_POST["note"] ?? "");

        $address = $this->addressModel->getById($addressId);

        $cartId = $this->cartModel->getOrCreateCartId($userId);
        $items = $this->cartModel->getItems($cartId);

        if (empty($items)) {
            $this->redirect("/cart");
        }

        if (!$address || (int) $address["user_id"] !== (int) $userId) {
            $this->render("client/checkout", [
                "items" => $items,
                "total" => $this->cartModel->getTotal($cartId),
                "addresses" => $this->addressModel->getAllByUserId($userId),
                "message" => "Vui lòng chọn địa chỉ giao hàng hợp lệ"
            ]);
            return;
        }

        // Kiểm tra lại tồn kho trước khi đặt (phòng trường hợp vừa có người mua hết)
        foreach ($items as $item) {
            if ($item["quantity"] > $item["stock"]) {
                $this->render("client/checkout", [
                    "items" => $items,
                    "total" => $this->cartModel->getTotal($cartId),
                    "addresses" => $this->addressModel->getAllByUserId($userId),
                    "message" => "Sản phẩm \"" . $item["painting_name"] . "\" không đủ số lượng tồn kho"
                ]);
                return;
            }
        }

        $total = $this->cartModel->getTotal($cartId);

        try {
            $this->conn->beginTransaction();

            $orderId = $this->orderModel->create($userId, $addressId, $total, $paymentMethod, $note);

            foreach ($items as $item) {
                if (!$this->paintingModel->decreaseStock($item["painting_id"], $item["quantity"])) {
                    throw new RuntimeException("Sản phẩm không còn đủ số lượng tồn kho");
                }

                $this->orderModel->addDetail($orderId, $item["painting_id"], $item["quantity"], $item["price"]);
            }

            $this->cartModel->clear($cartId);
            $this->conn->commit();
        } catch (Throwable $exception) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            $this->render("client/checkout", [
                "items" => $this->cartModel->getItems($cartId),
                "total" => $this->cartModel->getTotal($cartId),
                "addresses" => $this->addressModel->getAllByUserId($userId),
                "message" => $exception->getMessage()
            ]);
            return;
        }

        $this->redirect("/orders/" . $orderId . "/pay");
    }

    // GET /orders/{id}/pay - trang thanh toán (nhập số tiền)
    public function showPayment($id)
    {
        requireLogin();

        $order = $this->getOwnedOrderOrDie($id);

        if ($order["payment_status"] === "paid") {
            $this->redirect("/orders/" . $id);
        }

        $this->render("client/orders/pay", [
            "order" => $order,
            "message" => ""
        ]);
    }

    // POST /orders/{id}/pay - xử lý thanh toán giả lập
    public function pay($id)
    {
        requireLogin();

        $order = $this->getOwnedOrderOrDie($id);

        if ($order["payment_status"] === "paid") {
            $this->redirect("/orders/" . $id);
        }

        $amount = (float) ($_POST["amount"] ?? 0);

        // Thanh toán thành công khi số tiền nhập khớp với tổng đơn hàng
        if (abs($amount - (float) $order["total_amount"]) < 0.01) {

            $this->orderModel->markPaid($id);

            $this->redirect("/orders/" . $id . "?message=" . urlencode("Thanh toán thành công"));
        }

        $this->render("client/orders/pay", [
            "order" => $order,
            "message" => "Số tiền không khớp với tổng đơn hàng, thanh toán thất bại. Vui lòng thử lại."
        ]);
    }

    // GET /orders - danh sách đơn hàng của user đang đăng nhập
    public function index()
    {
        requireLogin();

        $this->render("client/orders/index", [
            "orders" => $this->orderModel->getAllByUserId($_SESSION["user_id"])
        ]);
    }

    // GET /orders/{id} - chi tiết 1 đơn hàng
    public function show($id)
    {
        requireLogin();

        $order = $this->getOwnedOrderOrDie($id);

        $this->render("client/orders/show", [
            "order" => $order,
            "details" => $this->orderModel->getDetails($id),
            "message" => $_GET["message"] ?? ""
        ]);
    }

    // Lấy đơn hàng theo ID và kiểm tra thuộc đúng user đang đăng nhập
    private function getOwnedOrderOrDie($id)
    {
        $order = $this->orderModel->getByIdAndUser($id, $_SESSION["user_id"]);

        if (!$order) {
            die("Không tìm thấy đơn hàng");
        }

        return $order;
    }
}