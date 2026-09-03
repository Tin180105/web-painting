<?php

class Address
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Lấy tất cả địa chỉ của 1 user, địa chỉ mặc định hiển thị lên đầu
    public function getAllByUserId($userId)
    {
        $sql = "SELECT *
                FROM addresses
                WHERE user_id = :user_id
                ORDER BY is_default DESC, address_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":user_id" => $userId]);

        return $stmt->fetchAll();
    }

    // Chi tiết 1 địa chỉ theo ID
    public function getById($id)
    {
        $sql = "SELECT * FROM addresses WHERE address_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->fetch();
    }

    // Đếm số địa chỉ hiện có của user (dùng để tự đặt mặc định cho địa chỉ đầu tiên)
    public function countByUserId($userId)
    {
        $sql = "SELECT COUNT(*) AS total FROM addresses WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":user_id" => $userId]);

        return (int) $stmt->fetch()["total"];
    }

    // Thêm địa chỉ mới
    public function create($userId, $data)
    {
        // Nếu đây là địa chỉ đầu tiên của user -> tự động đặt làm mặc định
        $isDefault = !empty($data["is_default"]) || $this->countByUserId($userId) === 0;

        if ($isDefault) {
            $this->clearDefault($userId);
        }

        $sql = "INSERT INTO addresses
                (user_id, receiver_name, phone, address_detail, ward, district, province, is_default)
                VALUES
                (:user_id, :receiver_name, :phone, :address_detail, :ward, :district, :province, :is_default)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":user_id" => $userId,
            ":receiver_name" => $data["receiver_name"],
            ":phone" => $data["phone"],
            ":address_detail" => $data["address_detail"],
            ":ward" => $data["ward"] ?? "",
            ":district" => $data["district"] ?? "",
            ":province" => $data["province"] ?? "",
            ":is_default" => $isDefault ? 1 : 0
        ]);
    }

    // Sửa địa chỉ
    public function update($id, $userId, $data)
    {
        if (!empty($data["is_default"])) {
            $this->clearDefault($userId);
        }

        $sql = "UPDATE addresses SET
                    receiver_name = :receiver_name,
                    phone = :phone,
                    address_detail = :address_detail,
                    ward = :ward,
                    district = :district,
                    province = :province,
                    is_default = :is_default
                WHERE address_id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":user_id" => $userId,
            ":receiver_name" => $data["receiver_name"],
            ":phone" => $data["phone"],
            ":address_detail" => $data["address_detail"],
            ":ward" => $data["ward"] ?? "",
            ":district" => $data["district"] ?? "",
            ":province" => $data["province"] ?? "",
            ":is_default" => !empty($data["is_default"]) ? 1 : 0
        ]);
    }

    // Xóa địa chỉ (kèm điều kiện user_id để chống xóa địa chỉ người khác)
    public function delete($id, $userId)
    {
        $sql = "DELETE FROM addresses WHERE address_id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":user_id" => $userId
        ]);
    }

    // Đặt 1 địa chỉ làm mặc định (bỏ mặc định của các địa chỉ khác trước)
    public function setDefault($id, $userId)
    {
        $this->clearDefault($userId);

        $sql = "UPDATE addresses
                SET is_default = 1
                WHERE address_id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":user_id" => $userId
        ]);
    }

    // Bỏ mặc định toàn bộ địa chỉ của user (dùng nội bộ trước khi set 1 địa chỉ khác làm mặc định)
    private function clearDefault($userId)
    {
        $sql = "UPDATE addresses SET is_default = 0 WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":user_id" => $userId]);
    }
}
