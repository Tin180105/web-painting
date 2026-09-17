<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Order.php";

class AdminOrderController extends Controller
{
    private $orderModel;

    public function __construct($pdo)
    {
        $this->orderModel = new Order($pdo);
    }

    // GET /admin/orders - danh sách đơn hàng, lọc theo status
    public function index()
    {
        requireAdmin();

        $status = $_GET["status"] ?? "";

        $this->render("admin/orders/index", [
            "orders" => $this->orderModel->getAll($status),
            "status" => $status,
            "message" => $_GET["message"] ?? "",
            "activeMenu" => "orders",
            "pageTitle" => "Quản lý đơn hàng"
        ]);
    }

    // GET /admin/orders/{id} - chi tiết đơn hàng
    public function show($id)
    {
        requireAdmin();

        $order = $this->orderModel->getById($id);

        if (!$order) {
            die("Không tìm thấy đơn hàng");
        }

        $this->render("admin/orders/show", [
            "order" => $order,
            "details" => $this->orderModel->getDetails($id),
            "message" => $_GET["message"] ?? "",
            "activeMenu" => "orders",
            "pageTitle" => "Đơn hàng #" . $id
        ]);
    }

    // POST /admin/orders/{id}/status - cập nhật trạng thái đơn hàng
    public function updateStatus($id)
    {
        requireAdmin();

        $status = $_POST["status"] ?? "";
        $allowed = ["pending", "confirmed", "shipping", "completed", "cancelled"];

        if (!in_array($status, $allowed, true)) {
            $this->redirect("/admin/orders/" . $id);
        }

        $this->orderModel->updateStatus($id, $status);

        $this->redirect("/admin/orders/" . $id . "?message=" . urlencode("Cập nhật trạng thái thành công"));
    }
}