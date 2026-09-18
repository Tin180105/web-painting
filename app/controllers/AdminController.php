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

    public function __construct($pdo)
    {
        $this->orderModel = new Order($pdo);
        $this->paintingModel = new Painting($pdo);
        $this->categoryModel = new Category($pdo);
    }

        public function dashboard()
    {
        requireAdmin();

        $orders = $this->orderModel->getAll();
        $paintings = $this->paintingModel->getAll();
        $categories = $this->categoryModel->getAll();

        $totalRevenue = 0;
        $pendingCount = 0;

        foreach ($orders as $order) {
            if ($order["payment_status"] === "paid" && $order["status"] !== "cancelled") {
                $totalRevenue += $order["total_amount"];
            }
            if ($order["status"] === "pending") {
                $pendingCount++;
            }
        }

        $outOfStockCount = 0;
        $lowStockPaintings = [];

        foreach ($paintings as $painting) {
            if ($painting["status"] === "out_of_stock" || (int) $painting["quantity"] <= 0) {
                $outOfStockCount++;
                continue;
            }

            if ((int) $painting["quantity"] <= 3) {
                $lowStockPaintings[] = $painting;
            }
        }

        $availableYears = $this->orderModel->getYearsWithOrders();

        if (empty($availableYears)) {
            $availableYears = [(int) date("Y")];
        }

        $chartYear = (int) ($_GET["year"] ?? date("Y"));

        if (!in_array($chartYear, $availableYears, true)) {
            $chartYear = $availableYears[0];
        }

        $monthlyRevenue = $this->orderModel->getMonthlyRevenue($chartYear);

        $this->render("admin/dashboard", [
            "totalOrders" => count($orders),
            "pendingCount" => $pendingCount,
            "totalRevenue" => $totalRevenue,
            "totalPaintings" => count($paintings),
            "totalCategories" => count($categories),
            "outOfStockCount" => $outOfStockCount,
            "lowStockPaintings" => array_slice($lowStockPaintings, 0, 5),
            "recentOrders" => array_slice($orders, 0, 5),
            "availableYears" => $availableYears,
            "chartYear" => $chartYear,
            "monthlyRevenue" => $monthlyRevenue,
            "activeMenu" => "dashboard",
            "pageTitle" => "Tổng quan"
        ]);
    }
}