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

    // Hiển thị form đăng ký
    public function showRegister()
    {
        $this->view("auth/register", [
            "message" => ""
        ]);
    }

    // Xử lý đăng ký
    public function register()
    {
        $result = $this->processRegister();

        if ($result["success"]) {
            $this->redirect("login");
        }

        $this->view("auth/register", [
            "message" => $result["message"]
        ]);
    }

    private function processRegister()
    {
        $fullName = trim($_POST["full_name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $phone = trim($_POST["phone"] ?? "");

        if ($fullName === "" || $email === "" || $password === "") {
            return [
                "success" => false,
                "message" => "Vui lòng nhập đầy đủ thông tin"
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                "success" => false,
                "message" => "Email không hợp lệ"
            ];
        }

        $user = $this->userModel->findByEmail($email);

        if ($user) {
            return [
                "success" => false,
                "message" => "Email đã tồn tại"
            ];
        }

        $this->userModel->create($fullName, $email, $password, $phone);

        return [
            "success" => true,
            "message" => "Đăng ký thành công"
        ];
    }

    // Hiển thị form đăng nhập
    public function showLogin()
    {
        $this->view("auth/login", [
            "message" => ""
        ]);
    }

    // Xử lý đăng nhập
    public function login()
    {
        $result = $this->processLogin();

        if ($result["success"]) {

            if ($result["role"] === "admin") {
                $this->redirect("admin/categories");
            } else {
                $this->redirect("");
            }
        }

        $this->view("auth/login", [
            "message" => $result["message"]
        ]);
    }

    private function processLogin()
    {
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        if ($email === "" || $password === "") {
            return [
                "success" => false,
                "message" => "Vui lòng nhập email và mật khẩu"
            ];
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user["password"])) {
            return [
                "success" => false,
                "message" => "Email hoặc mật khẩu không đúng"
            ];
        }

        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["full_name"] = $user["full_name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        return [
            "success" => true,
            "message" => "Đăng nhập thành công",
            "role" => $user["role"]
        ];
    }

    // Đăng xuất
    public function logout()
    {
        session_unset();
        session_destroy();

        session_start();

        $this->redirect("login");
    }
}