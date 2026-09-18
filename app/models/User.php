<?php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

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

    public function getById($id)
    {
        $sql = "SELECT * FROM users WHERE user_id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->fetch();
    }

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

    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE user_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([":id" => $id]);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE users SET status = :status WHERE user_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":status" => $status
        ]);
    }

    public function countAll()
    {
        $sql = "SELECT COUNT(*) AS total FROM users";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return (int) $stmt->fetch()["total"];
    }
}
