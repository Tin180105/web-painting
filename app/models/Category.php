<?php

class Category
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Lấy tất cả category
    public function getAll()
    {
        $sql = "SELECT *
                FROM categories
                ORDER BY category_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Lấy category theo ID
    public function getById($id)
    {
        $sql = "SELECT *
                FROM categories
                WHERE category_id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch();
    }

// Thêm category
public function create($categoryName, $description)
{
    $sql = "INSERT INTO categories
            (category_name, description)
            VALUES
            (:category_name, :description)";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        ":category_name" => $categoryName,
        ":description" => $description
    ]);
}

// Sửa category
public function update($id, $categoryName, $description)
{
    $sql = "UPDATE categories
            SET category_name = :category_name,
                description = :description
            WHERE category_id = :id";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        ":id" => $id,
        ":category_name" => $categoryName,
        ":description" => $description
    ]);
}

    // Xóa category
    public function delete($id)
    {
        $sql = "DELETE FROM categories
                WHERE category_id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":id" => $id
        ]);
    }
}
