-- Migration: thêm bảng thư viện ảnh cho sản phẩm (hỗ trợ upload nhiều ảnh)
-- Chạy file này trên database đã tồn tại (painting_shop) nếu chưa có bảng painting_images.
-- (database.sql đã được cập nhật để bao gồm bảng này khi import mới từ đầu)

USE painting_shop;

CREATE TABLE IF NOT EXISTS painting_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    painting_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (painting_id)
        REFERENCES paintings(painting_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
