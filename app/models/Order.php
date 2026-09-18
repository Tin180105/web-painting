<?php

class Order
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($userId, $addressId, $totalAmount, $paymentMethod, $note)
    {
        $sql = "INSERT INTO orders
                (user_id, address_id, total_amount, payment_method, note)
                VALUES
                (:user_id, :address_id, :total_amount, :payment_method, :note)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":user_id" => $userId,
            ":address_id" => $addressId,
            ":total_amount" => $totalAmount,
            ":payment_method" => $paymentMethod,
            ":note" => $note
        ]);

        return $this->pdo->lastInsertId();
    }

    public function addDetail($orderId, $paintingId, $quantity, $price)
    {
        $sql = "INSERT INTO order_details
                (order_id, painting_id, quantity, price)
                VALUES
                (:order_id, :painting_id, :quantity, :price)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":order_id" => $orderId,
            ":painting_id" => $paintingId,
            ":quantity" => $quantity,
            ":price" => $price
        ]);
    }

    public function getById($id)
    {
        $sql = "SELECT o.*, a.receiver_name, a.phone, a.address_detail, a.ward, a.district, a.province
                FROM orders o
                LEFT JOIN addresses a ON o.address_id = a.address_id
                WHERE o.order_id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->fetch();
    }

    public function getByIdAndUser($id, $userId)
    {
        $order = $this->getById($id);

        if (!$order || (int) $order["user_id"] !== (int) $userId) {
            return null;
        }

        return $order;
    }

    public function getAllByUserId($userId)
    {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":user_id" => $userId]);

        return $stmt->fetchAll();
    }

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

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getDetails($orderId)
    {
        $sql = "SELECT od.*, p.painting_name, p.image
                FROM order_details od
                JOIN paintings p ON od.painting_id = p.painting_id
                WHERE od.order_id = :order_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":order_id" => $orderId]);

        return $stmt->fetchAll();
    }

    public function markPaid($orderId)
    {
        $sql = "UPDATE orders
                SET payment_status = 'paid', status = 'confirmed'
                WHERE order_id = :id AND payment_status = 'unpaid'";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([":id" => $orderId]);
    }

    public function updateStatus($orderId, $status)
    {
        $sql = "UPDATE orders SET status = :status WHERE order_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $orderId,
            ":status" => $status
        ]);
    }

    public function getMonthlyRevenue($year)
    {
        $sql = "SELECT MONTH(created_at) AS month, SUM(total_amount) AS revenue
                FROM orders
                WHERE payment_status = 'paid' AND status != 'cancelled' AND YEAR(created_at) = :year
                GROUP BY MONTH(created_at)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":year" => $year]);

        $rows = $stmt->fetchAll();

        $result = array_fill(1, 12, 0);

        foreach ($rows as $row) {
            $result[(int) $row["month"]] = (float) $row["revenue"];
        }

        return $result;
    }

    public function getYearsWithOrders()
    {
        $sql = "SELECT DISTINCT YEAR(created_at) AS year
                FROM orders
                ORDER BY year DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return array_column($stmt->fetchAll(), "year");
    }
}