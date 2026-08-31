<?php
$conn = mysqli_connect("localhost", "root", "", "ecommerceshop");

if (!$conn) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
?>