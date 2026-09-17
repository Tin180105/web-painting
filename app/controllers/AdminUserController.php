<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/User.php";

class AdminUserController extends Controller
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
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

    // GET /admin/users/create - hiển thị form thêm tài khoản
    public function create()
    {
        requireAdmin();

        $this->render("admin/users/create", [
            "message" => "",
            "activeMenu" => "users",
            "pageTitle" => "Thêm tài khoản"
        ]);
    }

    // POST /admin/users/create - xử lý thêm tài khoản
    public function store()
    {
        requireAdmin();

        $fullName = trim($_POST["full_name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $phone = trim($_POST["phone"] ?? "");
        $role = ($_POST["role"] ?? "customer") === "admin" ? "admin" : "customer";

        if ($fullName === "" || $email === "" || $password === "") {
            $this->render("admin/users/create", [
                "message" => "Vui lòng nhập đầy đủ họ tên, email và mật khẩu",
                "activeMenu" => "users",
                "pageTitle" => "Thêm tài khoản"
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render("admin/users/create", [
                "message" => "Email không hợp lệ",
                "activeMenu" => "users",
                "pageTitle" => "Thêm tài khoản"
            ]);
            return;
        }

        if (strlen($password) < 6) {
            $this->render("admin/users/create", [
                "message" => "Mật khẩu phải có ít nhất 6 ký tự",
                "activeMenu" => "users",
                "pageTitle" => "Thêm tài khoản"
            ]);
            return;
        }

        if ($this->userModel->findByEmail($email)) {
            $this->render("admin/users/create", [
                "message" => "Email đã được sử dụng",
                "activeMenu" => "users",
                "pageTitle" => "Thêm tài khoản"
            ]);
            return;
        }

        $this->userModel->createByAdmin($fullName, $email, $password, $phone, $role);

        $this->redirect("/admin/users?message=" . urlencode("Thêm tài khoản thành công"));
    }

    // GET /admin/users/edit/{id} - hiển thị form sửa tài khoản
    public function edit($id)
    {
        requireAdmin();

        $user = $this->userModel->getById($id);

        if (!$user) {
            die("Không tìm thấy tài khoản");
        }

        $this->render("admin/users/edit", [
            "user" => $user,
            "message" => "",
            "activeMenu" => "users",
            "pageTitle" => "Sửa tài khoản"
        ]);
    }

    // POST /admin/users/edit/{id} - xử lý cập nhật tài khoản
    public function update($id)
    {
        requireAdmin();

        $user = $this->userModel->getById($id);

        if (!$user) {
            die("Không tìm thấy tài khoản");
        }

        $fullName = trim($_POST["full_name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $phone = trim($_POST["phone"] ?? "");
        $role = ($_POST["role"] ?? "customer") === "admin" ? "admin" : "customer";
        $isSelf = (int) $id === (int) $_SESSION["user_id"];

        // Không cho admin tự đổi vai trò của chính mình (tránh tự khóa quyền admin)
        if ($isSelf) {
            $role = $user["role"];
        }

        if ($fullName === "" || $email === "") {
            $this->render("admin/users/edit", [
                "user" => array_merge($user, $_POST),
                "message" => "Vui lòng nhập đầy đủ họ tên và email",
                "activeMenu" => "users",
                "pageTitle" => "Sửa tài khoản"
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render("admin/users/edit", [
                "user" => array_merge($user, $_POST),
                "message" => "Email không hợp lệ",
                "activeMenu" => "users",
                "pageTitle" => "Sửa tài khoản"
            ]);
            return;
        }

        $existing = $this->userModel->findByEmail($email);

        if ($existing && (int) $existing["user_id"] !== (int) $id) {
            $this->render("admin/users/edit", [
                "user" => array_merge($user, $_POST),
                "message" => "Email đã được sử dụng bởi tài khoản khác",
                "activeMenu" => "users",
                "pageTitle" => "Sửa tài khoản"
            ]);
            return;
        }

        if ($password !== "" && strlen($password) < 6) {
            $this->render("admin/users/edit", [
                "user" => array_merge($user, $_POST),
                "message" => "Mật khẩu mới phải có ít nhất 6 ký tự",
                "activeMenu" => "users",
                "pageTitle" => "Sửa tài khoản"
            ]);
            return;
        }

        $this->userModel->updateProfile($id, $fullName, $email, $phone, $role);

        if ($password !== "") {
            $this->userModel->updatePassword($id, $password);
        }

        $this->redirect("/admin/users?message=" . urlencode("Cập nhật tài khoản thành công"));
    }

    // GET /admin/users/delete/{id} - xử lý xóa tài khoản
    public function delete($id)
    {
        requireAdmin();

        if ((int) $id === (int) $_SESSION["user_id"]) {
            $this->redirect("/admin/users?message=" . urlencode("Không thể tự xóa tài khoản của chính mình"));
            return;
        }

        try {

            $user = $this->userModel->getById($id);

            if (!$user) {
                $this->redirect("/admin/users?message=" . urlencode("Không tìm thấy tài khoản"));
                return;
            }

            $this->userModel->delete($id);

            $this->redirect("/admin/users?message=" . urlencode("Xóa tài khoản thành công"));

        } catch (PDOException $e) {

            $this->redirect("/admin/users?message=" . urlencode(
                "Không thể xóa tài khoản này (có thể đã có đơn hàng hoặc dữ liệu liên quan)"
            ));
        }
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