<?php

class Address
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllByUserId($userId)
    {
        $sql = "SELECT *
                FROM addresses
                WHERE user_id = :user_id
                ORDER BY is_default DESC, address_id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":user_id" => $userId]);

        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM addresses WHERE address_id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->fetch();
    }

    public function countByUserId($userId)
    {
        $sql = "SELECT COUNT(*) AS total FROM addresses WHERE user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":user_id" => $userId]);

        return (int) $stmt->fetch()["total"];
    }

    public function create($userId, $data)
    {
        $isDefault = !empty($data["is_default"]) || $this->countByUserId($userId) === 0;

        if ($isDefault) {
            $this->clearDefault($userId);
        }

        $sql = "INSERT INTO addresses
                (user_id, receiver_name, phone, address_detail, ward, district, province, is_default)
                VALUES
                (:user_id, :receiver_name, :phone, :address_detail, :ward, :district, :province, :is_default)";

        $stmt = $this->pdo->prepare($sql);

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

        $stmt = $this->pdo->prepare($sql);

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

    public function delete($id, $userId)
    {
        $sql = "DELETE FROM addresses WHERE address_id = :id AND user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":user_id" => $userId
        ]);
    }

    public function setDefault($id, $userId)
    {
        $this->clearDefault($userId);

        $sql = "UPDATE addresses
                SET is_default = 1
                WHERE address_id = :id AND user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":user_id" => $userId
        ]);
    }

    private function clearDefault($userId)
    {
        $sql = "UPDATE addresses SET is_default = 0 WHERE user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":user_id" => $userId]);
    }
}
