<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Order.php";
require_once __DIR__ . "/../models/Cart.php";
require_once __DIR__ . "/../models/Address.php";
require_once __DIR__ . "/../models/Painting.php";

class OrderController extends Controller
{
    private $pdo;
    private $orderModel;
    private $cartModel;
    private $addressModel;
    private $paintingModel;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->orderModel = new Order($pdo);
        $this->cartModel = new Cart($pdo);
        $this->addressModel = new Address($pdo);
        $this->paintingModel = new Painting($pdo);
    }

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
            $this->pdo->beginTransaction();

            $orderId = $this->orderModel->create($userId, $addressId, $total, $paymentMethod, $note);

            foreach ($items as $item) {
                if (!$this->paintingModel->decreaseStock($item["painting_id"], $item["quantity"])) {
                    throw new RuntimeException("Sản phẩm không còn đủ số lượng tồn kho");
                }

                $this->orderModel->addDetail($orderId, $item["painting_id"], $item["quantity"], $item["price"]);
            }

            $this->cartModel->clear($cartId);
            $this->pdo->commit();
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
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

    public function pay($id)
    {
        requireLogin();

        $order = $this->getOwnedOrderOrDie($id);

        if ($order["payment_status"] === "paid") {
            $this->redirect("/orders/" . $id);
        }

        $amount = (float) ($_POST["amount"] ?? 0);

        if (abs($amount - (float) $order["total_amount"]) < 0.01) {

            $this->orderModel->markPaid($id);

            $this->redirect("/orders/" . $id . "?message=" . urlencode("Thanh toán thành công"));
        }

        $this->render("client/orders/pay", [
            "order" => $order,
            "message" => "Số tiền không khớp với tổng đơn hàng, thanh toán thất bại. Vui lòng thử lại."
        ]);
    }

    public function index()
    {
        requireLogin();

        $this->render("client/orders/index", [
            "orders" => $this->orderModel->getAllByUserId($_SESSION["user_id"])
        ]);
    }

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

    private function getOwnedOrderOrDie($id)
    {
        $order = $this->orderModel->getByIdAndUser($id, $_SESSION["user_id"]);

        if (!$order) {
            die("Không tìm thấy đơn hàng");
        }

        return $order;
    }
}