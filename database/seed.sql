USE painting_shop;

INSERT INTO categories (category_name, description, image) VALUES
('Tranh phong cảnh', 'Các tác phẩm tranh phong cảnh thiên nhiên', ''),
('Tranh trừu tượng', 'Nghệ thuật trừu tượng hiện đại', ''),
('Tranh chân dung', 'Tranh vẽ chân dung người', '');

INSERT INTO paintings (category_id, painting_name, description, artist, price, quantity, width, height, material, image, status) VALUES
(1, 'Hoàng hôn trên biển', 'Bức tranh sơn dầu vẽ cảnh hoàng hôn tuyệt đẹp trên biển', 'Nguyễn Văn A', 2500000, 5, 60, 40, 'Sơn dầu trên canvas', 'https://images.unsplash.com/photo-1544967082-d9d25d867d66?w=500', 'available'),
(1, 'Núi rừng Tây Bắc', 'Phong cảnh núi rừng hùng vĩ vùng Tây Bắc Việt Nam', 'Trần Thị B', 3200000, 3, 80, 60, 'Sơn dầu trên canvas', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=500', 'available'),
(2, 'Sắc màu cảm xúc', 'Tác phẩm trừu tượng thể hiện dòng cảm xúc qua màu sắc', 'Lê Văn C', 4500000, 2, 70, 70, 'Acrylic trên canvas', 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=500', 'available'),
(2, 'Hỗn loạn hình học', 'Tranh trừu tượng với các mảng hình học đan xen', 'Phạm Thị D', 3800000, 0, 65, 50, 'Acrylic trên canvas', 'https://images.unsplash.com/photo-1549490349-8643362247b5?w=500', 'out_of_stock'),
(3, 'Chân dung thiếu nữ', 'Tranh chân dung thiếu nữ áo dài truyền thống', 'Hoàng Văn E', 5000000, 4, 50, 70, 'Sơn dầu trên canvas', 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?w=500', 'available');
