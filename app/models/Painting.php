<?php

class Painting
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Lấy danh sách tranh (dùng cho trang danh sách sản phẩm client)
    // $filters: category_id, keyword, sort (newest|price_asc|price_desc)
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
            $sql .= " AND p.painting_name LIKE :keyword";
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

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    // Chi tiết 1 tranh theo ID
    public function getById($id)
    {
        $sql = "SELECT p.*, c.category_name
                FROM paintings p
                JOIN categories c ON p.category_id = c.category_id
                WHERE p.painting_id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch();
    }

    // Thêm tranh (dùng cho phần quản lý sản phẩm - admin)
    public function create($data)
    {
        $sql = "INSERT INTO paintings
                (category_id, painting_name, description, artist, price, quantity, width, height, material, image, status)
                VALUES
                (:category_id, :painting_name, :description, :artist, :price, :quantity, :width, :height, :material, :image, :status)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
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

    // Sửa tranh (dùng cho phần quản lý sản phẩm - admin)
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

        $stmt = $this->conn->prepare($sql);

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

    // Xóa tranh (dùng cho phần quản lý sản phẩm - admin)
    public function delete($id)
    {
        $sql = "DELETE FROM paintings WHERE painting_id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":id" => $id
        ]);
    }

    // Giảm số lượng tồn kho (dùng khi đặt hàng thành công)
    public function decreaseStock($id, $quantity)
    {
        $sql = "UPDATE paintings
                SET quantity = quantity - :quantity
                WHERE painting_id = :id AND quantity >= :quantity";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":quantity" => $quantity
        ]);
    }
}
