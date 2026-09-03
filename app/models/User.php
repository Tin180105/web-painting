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
}
