<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/User.php";

class AdminUserController extends Controller
{
    private $userModel;

    public function __construct($conn)
    {
        $this->userModel = new User($conn);
    }

    // GET /admin/users - danh sách tài khoản
    public function index()
    {
        requireAdmin();

        $keyword = trim($_GET["keyword"] ?? "");

        $this->render("admin/users/index", [
            "users" => $this->userModel->getAll($keyword),
            "keyword" => $keyword,
            "message" => $_GET["message"] ?? "",
            "activeMenu" => "users",
            "pageTitle" => "Quản lý tài khoản"
        ]);
    }

    // GET /admin/users/{id} - chi tiết 1 tài khoản
    public function show($id)
    {
        requireAdmin();

        $user = $this->userModel->getById($id);

        if (!$user) {
            die("Không tìm thấy tài khoản");
        }

        $this->render("admin/users/show", [
            "user" => $user,
            "activeMenu" => "users",
            "pageTitle" => "Chi tiết tài khoản"
        ]);
    }

    // GET /admin/users/lock/{id} - khóa tài khoản
    public function lock($id)
    {
        requireAdmin();

        // Không cho admin tự khóa chính mình
        if ((int) $id === (int) $_SESSION["user_id"]) {
            $this->redirect("/admin/users?message=" . urlencode("Không thể tự khóa tài khoản của chính mình"));
            return;
        }

        $user = $this->userModel->getById($id);

        if (!$user) {
            $this->redirect("/admin/users?message=" . urlencode("Không tìm thấy tài khoản"));
            return;
        }

        $this->userModel->updateStatus($id, "locked");

        $this->redirect("/admin/users?message=" . urlencode("Đã khóa tài khoản"));
    }

    // GET /admin/users/unlock/{id} - mở khóa tài khoản
    public function unlock($id)
    {
        requireAdmin();

        $user = $this->userModel->getById($id);

        if (!$user) {
            $this->redirect("/admin/users?message=" . urlencode("Không tìm thấy tài khoản"));
            return;
        }

        $this->userModel->updateStatus($id, "active");

        $this->redirect("/admin/users?message=" . urlencode("Đã mở khóa tài khoản"));
    }
}