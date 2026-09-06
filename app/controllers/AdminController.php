<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Order.php";
require_once __DIR__ . "/../models/Painting.php";
require_once __DIR__ . "/../models/Category.php";

class AdminController extends Controller
{
    private $orderModel;
    private $paintingModel;
    private $categoryModel;

    public function __construct($conn)
    {
        $this->orderModel = new Order($conn);
        $this->paintingModel = new Painting($conn);
        $this->categoryModel = new Category($conn);
    }

    // GET /admin/dashboard - trang tổng quan sau khi admin đăng nhập
    public function dashboard()
    {
        requireAdmin();

        $orders = $this->orderModel->getAll();
        $paintings = $this->paintingModel->getAll();
        $categories = $this->categoryModel->getAll();

        $totalRevenue = 0;
        $pendingCount = 0;

        foreach ($orders as $order) {
            if ($order["payment_status"] === "paid") {
                $totalRevenue += $order["total_amount"];
            }
            if ($order["status"] === "pending") {
                $pendingCount++;
            }
        }

        $this->render("admin/dashboard", [
            "totalOrders" => count($orders),
            "pendingCount" => $pendingCount,
            "totalRevenue" => $totalRevenue,
            "totalPaintings" => count($paintings),
            "totalCategories" => count($categories),
            "recentOrders" => array_slice($orders, 0, 5),
            "activeMenu" => "dashboard",
            "pageTitle" => "Tổng quan"
        ]);
    }
}