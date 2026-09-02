<?php

require_once __DIR__ . "/../models/User.php";

class AuthController
{
    private $userModel;

    public function __construct($conn)
    {
        $this->userModel = new User($conn);
    }

    // Đăng ký
    public function register()
    {
        $fullName = trim($_POST["full_name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $phone = trim($_POST["phone"] ?? "");

        // Kiểm tra dữ liệu
        if ($fullName === "" || $email === "" || $password === "") {
            return [
                "success" => false,
                "message" => "Vui lòng nhập đầy đủ thông tin"
            ];
        }

        // Kiểm tra email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                "success" => false,
                "message" => "Email không hợp lệ"
            ];
        }

        // Kiểm tra email đã tồn tại
        $user = $this->userModel->findByEmail($email);

        if ($user) {
            return [
                "success" => false,
                "message" => "Email đã tồn tại"
            ];
        }

        // Tạo customer
        $this->userModel->create(
            $fullName,
            $email,
            $password,
            $phone
        );

        return [
            "success" => true,
            "message" => "Đăng ký thành công"
        ];
    }

    // Đăng nhập
    public function login()
    {
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        if ($email === "" || $password === "") {
            return [
                "success" => false,
                "message" => "Vui lòng nhập email và mật khẩu"
            ];
        }

        // Tìm user
        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            return [
                "success" => false,
                "message" => "Email hoặc mật khẩu không đúng"
            ];
        }

        // Kiểm tra password
        if (!password_verify($password, $user["password"])) {
            return [
                "success" => false,
                "message" => "Email hoặc mật khẩu không đúng"
            ];
        }

        // Tạo session
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
    }
}