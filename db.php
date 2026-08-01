<?php
// Thông tin kết nối MySQL
$host = 'localhost';
$dbname = "smartphone_store";
$username = 'root';
$password = ''; // Mặc định trên XAMPP để trống, thay đổi nếu cần

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Kết nối cơ sở dữ liệu thất bại: " . $e->getMessage());
}
?>