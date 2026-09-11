<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/User.php";

class AuthController extends Controller
{
    private $userModel;

    public function __construct($conn)
    {
        $this->userModel = new User($conn);
    }

    // GET /login - hiển thị form đăng nhập
    public function showLogin()
    {
        $this->render("auth/login", [
            "message" => ""
        ]);
    }

    // POST /login - xử lý đăng nhập
    public function login()
    {
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        if ($email === "" || $password === "") {
            $this->render("auth/login", [
                "message" => "Vui lòng nhập email và mật khẩu"
            ]);
            return;
        }

                $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user["password"])) {
            $this->render("auth/login", [
                "message" => "Email hoặc mật khẩu không đúng"
            ]);
            return;
        }

        if ($user["status"] === "locked") {
            $this->render("auth/login", [
                "message" => "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên."
            ]);
            return;
        }

        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["full_name"] = $user["full_name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        if ($user["role"] === "admin") {
            $this->redirect("/admin/dashboard");
        } else {
            $this->redirect("/");
        }
    }

    // GET /register - hiển thị form đăng ký
    public function showRegister()
    {
        $this->render("auth/register", [
            "message" => ""
        ]);
    }

    // POST /register - xử lý đăng ký
    public function register()
    {
        $fullName = trim($_POST["full_name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $phone = trim($_POST["phone"] ?? "");

        if ($fullName === "" || $email === "" || $password === "") {
            $this->render("auth/register", [
                "message" => "Vui lòng nhập đầy đủ thông tin"
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render("auth/register", [
                "message" => "Email không hợp lệ"
            ]);
            return;
        }

        if ($this->userModel->findByEmail($email)) {
            $this->render("auth/register", [
                "message" => "Email đã tồn tại"
            ]);
            return;
        }

        $this->userModel->create($fullName, $email, $password, $phone);

        $this->redirect("/login");
    }

    // GET /logout
    public function logout()
    {
        session_unset();
        session_destroy();

        $this->redirect("/login");
    }
}
