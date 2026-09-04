<?php

class Order
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Tạo đơn hàng mới, trả về order_id vừa tạo
    public function create($userId, $addressId, $totalAmount, $paymentMethod, $note)
    {
        $sql = "INSERT INTO orders
                (user_id, address_id, total_amount, payment_method, note)
                VALUES
                (:user_id, :address_id, :total_amount, :payment_method, :note)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":user_id" => $userId,
            ":address_id" => $addressId,
            ":total_amount" => $totalAmount,
            ":payment_method" => $paymentMethod,
            ":note" => $note
        ]);

        return $this->conn->lastInsertId();
    }

    // Thêm 1 dòng chi tiết đơn hàng (snapshot giá tại thời điểm đặt)
    public function addDetail($orderId, $paintingId, $quantity, $price)
    {
        $sql = "INSERT INTO order_details
                (order_id, painting_id, quantity, price)
                VALUES
                (:order_id, :painting_id, :quantity, :price)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":order_id" => $orderId,
            ":painting_id" => $paintingId,
            ":quantity" => $quantity,
            ":price" => $price
        ]);
    }

    // Chi tiết 1 đơn hàng theo ID (kèm thông tin địa chỉ giao hàng)
    public function getById($id)
    {
        $sql = "SELECT o.*, a.receiver_name, a.phone, a.address_detail, a.ward, a.district, a.province
                FROM orders o
                LEFT JOIN addresses a ON o.address_id = a.address_id
                WHERE o.order_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->fetch();
    }

    // Lấy đơn hàng theo ID, kèm điều kiện thuộc đúng user (chống xem đơn người khác)
    public function getByIdAndUser($id, $userId)
    {
        $order = $this->getById($id);

        if (!$order || (int) $order["user_id"] !== (int) $userId) {
            return null;
        }

        return $order;
    }

    // Danh sách đơn hàng của 1 user, mới nhất lên đầu
    public function getAllByUserId($userId)
    {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":user_id" => $userId]);

        return $stmt->fetchAll();
    }

    // Danh sách toàn bộ đơn hàng (dùng cho admin), lọc theo status nếu có
    public function getAll($status = "")
    {
        $sql = "SELECT o.*, u.full_name, u.email
                FROM orders o
                JOIN users u ON o.user_id = u.user_id";

        $params = [];

        if (!empty($status)) {
            $sql .= " WHERE o.status = :status";
            $params[":status"] = $status;
        }

        $sql .= " ORDER BY o.order_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    // Danh sách sản phẩm trong 1 đơn hàng
    public function getDetails($orderId)
    {
        $sql = "SELECT od.*, p.painting_name, p.image
                FROM order_details od
                JOIN paintings p ON od.painting_id = p.painting_id
                WHERE od.order_id = :order_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":order_id" => $orderId]);

        return $stmt->fetchAll();
    }

    // Đánh dấu đơn hàng thanh toán thành công (chỉ áp dụng khi đang unpaid)
    public function markPaid($orderId)
    {
        $sql = "UPDATE orders
                SET payment_status = 'paid', status = 'confirmed'
                WHERE order_id = :id AND payment_status = 'unpaid'";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([":id" => $orderId]);
    }

    // Cập nhật trạng thái đơn hàng (dùng cho admin)
    public function updateStatus($orderId, $status)
    {
        $sql = "UPDATE orders SET status = :status WHERE order_id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":id" => $orderId,
            ":status" => $status
        ]);
    }
}