<?php

class Cart
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Lấy cart_id của user, tự động tạo giỏ hàng mới nếu user chưa có
    public function getOrCreateCartId($userId)
    {
        $sql = "SELECT cart_id FROM carts WHERE user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":user_id" => $userId]);

        $cart = $stmt->fetch();

        if ($cart) {
            return $cart["cart_id"];
        }

        $sql = "INSERT INTO carts (user_id) VALUES (:user_id)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":user_id" => $userId]);

        return $this->pdo->lastInsertId();
    }

    // Lấy danh sách sản phẩm trong giỏ (join với paintings để có tên, giá, ảnh, tồn kho)
    public function getItems($cartId)
    {
        $sql = "SELECT
                    cd.cart_detail_id,
                    cd.painting_id,
                    cd.quantity,
                    p.painting_name,
                    p.price,
                    p.image,
                    p.quantity AS stock
                FROM cart_details cd
                JOIN paintings p ON cd.painting_id = p.painting_id
                WHERE cd.cart_id = :cart_id
                ORDER BY cd.cart_detail_id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":cart_id" => $cartId]);

        return $stmt->fetchAll();
    }

    // Tìm 1 dòng cart_detail theo ID, kèm điều kiện thuộc đúng cart (chống sửa giỏ người khác)
    public function findItem($cartDetailId, $cartId)
    {
        $sql = "SELECT cd.*, p.price, p.quantity AS stock
                FROM cart_details cd
                JOIN paintings p ON cd.painting_id = p.painting_id
                WHERE cd.cart_detail_id = :id AND cd.cart_id = :cart_id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":id" => $cartDetailId,
            ":cart_id" => $cartId
        ]);

        return $stmt->fetch();
    }

    // Thêm sản phẩm vào giỏ - nếu đã có (unique cart_id + painting_id) thì cộng dồn số lượng
    public function addItem($cartId, $paintingId, $quantity)
    {
        $sql = "INSERT INTO cart_details (cart_id, painting_id, quantity)
                VALUES (:cart_id, :painting_id, :quantity)
                ON DUPLICATE KEY UPDATE quantity = quantity + :quantity2";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":cart_id" => $cartId,
            ":painting_id" => $paintingId,
            ":quantity" => $quantity,
            ":quantity2" => $quantity
        ]);
    }

    // Cập nhật số lượng 1 sản phẩm trong giỏ
    public function updateQuantity($cartDetailId, $cartId, $quantity)
    {
        $sql = "UPDATE cart_details
                SET quantity = :quantity
                WHERE cart_detail_id = :id AND cart_id = :cart_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":quantity" => $quantity,
            ":id" => $cartDetailId,
            ":cart_id" => $cartId
        ]);
    }

    // Xóa 1 sản phẩm khỏi giỏ
    public function removeItem($cartDetailId, $cartId)
    {
        $sql = "DELETE FROM cart_details
                WHERE cart_detail_id = :id AND cart_id = :cart_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $cartDetailId,
            ":cart_id" => $cartId
        ]);
    }

    // Xóa sạch giỏ hàng (dùng sau khi đặt hàng thành công)
    public function clear($cartId)
    {
        $sql = "DELETE FROM cart_details WHERE cart_id = :cart_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([":cart_id" => $cartId]);
    }

    // Đếm tổng số lượng sản phẩm trong giỏ (hiển thị badge trên navbar)
    public function countItems($cartId)
    {
        $sql = "SELECT COALESCE(SUM(quantity), 0) AS total
                FROM cart_details
                WHERE cart_id = :cart_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":cart_id" => $cartId]);

        return (int) $stmt->fetch()["total"];
    }

    // Tính tổng tiền giỏ hàng
    public function getTotal($cartId)
    {
        $sql = "SELECT COALESCE(SUM(cd.quantity * p.price), 0) AS total
                FROM cart_details cd
                JOIN paintings p ON cd.painting_id = p.painting_id
                WHERE cd.cart_id = :cart_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":cart_id" => $cartId]);

        return (float) $stmt->fetch()["total"];
    }
}
