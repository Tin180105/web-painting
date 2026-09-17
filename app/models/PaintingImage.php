<?php

class PaintingImage
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Lấy tất cả ảnh phụ của 1 tranh, theo thứ tự
    public function getByPaintingId($paintingId)
    {
        $sql = "SELECT * FROM painting_images WHERE painting_id = :painting_id ORDER BY sort_order ASC, image_id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":painting_id" => $paintingId]);

        return $stmt->fetchAll();
    }

    // Thêm nhiều ảnh cùng lúc cho 1 tranh (dùng khi thêm/sửa sản phẩm)
    public function addImages($paintingId, array $imagePaths)
    {
        if (empty($imagePaths)) {
            return;
        }

        $sql = "INSERT INTO painting_images (painting_id, image_path, sort_order) VALUES (:painting_id, :image_path, :sort_order)";
        $stmt = $this->pdo->prepare($sql);

        $nextOrder = $this->getNextSortOrder($paintingId);

        foreach ($imagePaths as $index => $path) {
            $stmt->execute([
                ":painting_id" => $paintingId,
                ":image_path" => $path,
                ":sort_order" => $nextOrder + $index
            ]);
        }
    }

    private function getNextSortOrder($paintingId)
    {
        $sql = "SELECT COALESCE(MAX(sort_order), -1) + 1 FROM painting_images WHERE painting_id = :painting_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":painting_id" => $paintingId]);

        return (int) $stmt->fetchColumn();
    }

    public function getById($imageId)
    {
        $sql = "SELECT * FROM painting_images WHERE image_id = :image_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":image_id" => $imageId]);

        return $stmt->fetch();
    }

    public function deleteById($imageId)
    {
        $sql = "DELETE FROM painting_images WHERE image_id = :image_id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([":image_id" => $imageId]);
    }

    public function deleteByPaintingId($paintingId)
    {
        $sql = "DELETE FROM painting_images WHERE painting_id = :painting_id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([":painting_id" => $paintingId]);
    }
}
