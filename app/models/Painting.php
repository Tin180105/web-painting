<?php

class Painting
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll($filters = [])
    {
        $sql = "SELECT p.*, c.category_name
                FROM paintings p
                JOIN categories c ON p.category_id = c.category_id
                WHERE p.status != 'hidden'";

        $params = [];

        if (!empty($filters["category_id"])) {
            $sql .= " AND p.category_id = :category_id";
            $params[":category_id"] = $filters["category_id"];
        }

        if (!empty($filters["keyword"])) {
            $sql .= " AND LOWER(p.painting_name) COLLATE utf8mb4_bin LIKE LOWER(:keyword) COLLATE utf8mb4_bin";
            $params[":keyword"] = "%" . $filters["keyword"] . "%";
        }

        switch ($filters["sort"] ?? "newest") {
            case "price_asc":
                $sql .= " ORDER BY p.price ASC";
                break;
            case "price_desc":
                $sql .= " ORDER BY p.price DESC";
                break;
            default:
                $sql .= " ORDER BY p.created_at DESC";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT p.*, c.category_name
                FROM paintings p
                JOIN categories c ON p.category_id = c.category_id
                WHERE p.painting_id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch();
    }

    public function getAllForAdmin()
{
    $sql = "SELECT p.*, c.category_name
            FROM paintings p
            JOIN categories c ON p.category_id = c.category_id
            ORDER BY p.created_at DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}

    public function create($data)
    {
        $sql = "INSERT INTO paintings
                (category_id, painting_name, description, artist, price, quantity, width, height, material, image, status)
                VALUES
                (:category_id, :painting_name, :description, :artist, :price, :quantity, :width, :height, :material, :image, :status)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":category_id" => $data["category_id"],
            ":painting_name" => $data["painting_name"],
            ":description" => $data["description"] ?? "",
            ":artist" => $data["artist"] ?? "",
            ":price" => $data["price"],
            ":quantity" => $data["quantity"] ?? 0,
            ":width" => $data["width"] ?? null,
            ":height" => $data["height"] ?? null,
            ":material" => $data["material"] ?? "",
            ":image" => $data["image"] ?? "",
            ":status" => $data["status"] ?? "available"
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE paintings SET
                    category_id = :category_id,
                    painting_name = :painting_name,
                    description = :description,
                    artist = :artist,
                    price = :price,
                    quantity = :quantity,
                    width = :width,
                    height = :height,
                    material = :material,
                    image = :image,
                    status = :status
                WHERE painting_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":category_id" => $data["category_id"],
            ":painting_name" => $data["painting_name"],
            ":description" => $data["description"] ?? "",
            ":artist" => $data["artist"] ?? "",
            ":price" => $data["price"],
            ":quantity" => $data["quantity"] ?? 0,
            ":width" => $data["width"] ?? null,
            ":height" => $data["height"] ?? null,
            ":material" => $data["material"] ?? "",
            ":image" => $data["image"] ?? "",
            ":status" => $data["status"] ?? "available"
        ]);
    }

    public function updateImage($id, $image)
    {
        $sql = "UPDATE paintings SET image = :image WHERE painting_id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":image" => $image
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM paintings WHERE painting_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id
        ]);
    }

    public function decreaseStock($id, $quantity)
    {
        $sql = "UPDATE paintings
                SET quantity = quantity - :decrease_quantity,
                    status = CASE
                        WHEN quantity = 0 THEN 'out_of_stock'
                        ELSE status
                    END
                WHERE painting_id = :id AND quantity >= :required_quantity";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":id" => $id,
            ":decrease_quantity" => $quantity,
            ":required_quantity" => $quantity
        ]);

        return $stmt->rowCount() === 1;
    }
}
