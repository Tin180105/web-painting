<?php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Tìm user theo email
    public function findByEmail($email)
    {
        $sql = "SELECT *
                FROM users
                WHERE email = :email
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":email" => $email
        ]);

        return $stmt->fetch();
    }

    // Tạo customer mới
    public function create($fullName, $email, $password, $phone)
    {
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO users
                (full_name, email, password, phone)
                VALUES
                (:full_name, :email, :password, :phone)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":full_name" => $fullName,
            ":email" => $email,
            ":password" => $passwordHash,
            ":phone" => $phone
        ]);
    }

        // Lấy tất cả user (dùng cho admin), có thể tìm theo tên/email
    public function getAll($keyword = "")
    {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (full_name COLLATE utf8mb4_bin LIKE :keyword OR email COLLATE utf8mb4_bin LIKE :keyword)";
            $params[":keyword"] = "%" . $keyword . "%";
        }

        $sql .= " ORDER BY user_id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    // Chi tiết 1 user theo ID
    public function getById($id)
    {
        $sql = "SELECT * FROM users WHERE user_id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->fetch();
    }

    // Tạo tài khoản mới do admin thêm (cho phép chọn role)
    public function createByAdmin($fullName, $email, $password, $phone, $role)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users
                (full_name, email, password, phone, role)
                VALUES
                (:full_name, :email, :password, :phone, :role)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":full_name" => $fullName,
            ":email" => $email,
            ":password" => $passwordHash,
            ":phone" => $phone,
            ":role" => $role
        ]);
    }

    // Cập nhật thông tin tài khoản (dùng cho admin)
    public function updateProfile($id, $fullName, $email, $phone, $role)
    {
        $sql = "UPDATE users
                SET full_name = :full_name, email = :email, phone = :phone, role = :role
                WHERE user_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":full_name" => $fullName,
            ":email" => $email,
            ":phone" => $phone,
            ":role" => $role
        ]);
    }

    // Đổi mật khẩu tài khoản (dùng cho admin, chỉ gọi khi có nhập mật khẩu mới)
    public function updatePassword($id, $password)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password = :password WHERE user_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":password" => $passwordHash
        ]);
    }

    // Xóa tài khoản (dùng cho admin)
    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE user_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([":id" => $id]);
    }

    // Khóa / mở khóa tài khoản (dùng cho admin)
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE users SET status = :status WHERE user_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":status" => $status
        ]);
    }

    // Đếm tổng số user (dùng cho dashboard nếu cần sau này)
    public function countAll()
    {
        $sql = "SELECT COUNT(*) AS total FROM users";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return (int) $stmt->fetch()["total"];
    }
}
