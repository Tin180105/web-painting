<?php

class Category
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT *
                FROM categories
                ORDER BY category_id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT *
                FROM categories
                WHERE category_id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch();
    }

public function create($categoryName, $description)
{
    $sql = "INSERT INTO categories
            (category_name, description)
            VALUES
            (:category_name, :description)";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        ":category_name" => $categoryName,
        ":description" => $description
    ]);
}

public function update($id, $categoryName, $description)
{
    $sql = "UPDATE categories
            SET category_name = :category_name,
                description = :description
            WHERE category_id = :id";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        ":id" => $id,
        ":category_name" => $categoryName,
        ":description" => $description
    ]);
}

    public function delete($id)
    {
        $sql = "DELETE FROM categories
                WHERE category_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id
        ]);
    }
}
