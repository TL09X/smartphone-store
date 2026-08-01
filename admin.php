<?php
session_start();
require_once 'db.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['currentUser']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Xử lý đăng xuất nếu có yêu cầu
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Lấy tổng số thành viên thực tế từ CSDL MySQL
$stmt_users = $pdo->query("SELECT COUNT(*) as total FROM users");
$total_users = $stmt_users->fetch()['total'];

// Lấy tổng số sản phẩm thực tế từ CSDL MySQL
$stmt_prods = $pdo->query("SELECT COUNT(*) as total FROM products");
$total_products = $stmt_prods->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
    <link href="sell.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <a href="sell.php">Xem trang web</a>
        <a href="quanly.php">Quản lý</a>
        <a href="admin.php?logout=true">Đăng xuất</a>
    </nav>

    <div class="admin-container">
        <div class="admin-header">
            <h2>Trang Quản Trị Hệ Thống (Admin)</h2>
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <h4>Tổng số sản phẩm</h4>
                <span><?php echo $total_products; ?></span>
            </div>
            <div class="stat-box">
                <h4>Đơn hàng mới</h4>
                <span>12</span>
            </div>
            <div class="stat-box">
                <h4>Thành viên</h4>
                <span><?php echo $total_users; ?></span>
            </div>
        </div>

        <div class="admin-actions">
            <h3>Cài đặt & Tác vụ hệ thống</h3>
            <div class="action-links">
                <a href="quanly.php">Quản lý chung</a>
                <a href="#">Cấu hình hệ thống</a>
                <a href="support.php">Xem phản hồi hỗ trợ</a>
            </div>
        </div>
    </div>
</body>
</html>