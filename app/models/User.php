<?php

class User
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Tìm user theo email
    public function findByEmail($email)
    {
        $sql = "SELECT *
                FROM users
                WHERE email = :email
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

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

        $stmt = $this->conn->prepare($sql);

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
            $sql .= " AND (full_name LIKE :keyword OR email LIKE :keyword)";
            $params[":keyword"] = "%" . $keyword . "%";
        }

        $sql .= " ORDER BY user_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    // Chi tiết 1 user theo ID
    public function getById($id)
    {
        $sql = "SELECT * FROM users WHERE user_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->fetch();
    }

    // Khóa / mở khóa tài khoản (dùng cho admin)
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE users SET status = :status WHERE user_id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":status" => $status
        ]);
    }

    // Đếm tổng số user (dùng cho dashboard nếu cần sau này)
    public function countAll()
    {
        $sql = "SELECT COUNT(*) AS total FROM users";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return (int) $stmt->fetch()["total"];
    }
}
