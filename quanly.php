<?php
session_start();
if (!isset($_SESSION['currentUser'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hệ thống</title>
    <link href="sell.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <a href="sell.php">Trang chủ</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="admin.php">Admin Dashboard</a>
        <?php endif; ?>
        <a href="login.php?logout=true">Đăng xuất</a>
    </nav>

    <div class="dashboard">
        <h2>Bảng điều khiển Quản lý</h2>
        <p style="text-align:center; color:#aaa; margin-bottom:20px;">Xin chào, <b><?php echo htmlspecialchars($_SESSION['currentUser']); ?></b>!</p>
        <div class="manage-grid">
            <div class="manage-card">
                <h3>Quản lý sản phẩm</h3>
                <p>Thêm mới, sửa thông tin hoặc xóa sản phẩm khỏi cửa hàng.</p>
                <a href="quanly_sanpham.php">Truy cập</a>
            </div>
            <div class="manage-card">
                <h3>Quản lý đơn hàng</h3>
                <p>Theo dõi, cập nhật trạng thái, trả góp, ưu đãi và xử lý đơn hàng.</p>
                <a href="quanly_donhang.php">Truy cập</a>
            </div>
            <div class="manage-card">
                <h3>Phản hồi hỗ trợ</h3>
                <p>Xem các yêu cầu và thắc mắc do khách hàng gửi tới.</p>
                <a href="quanly_supports.php">Truy cập</a>
            </div>
        </div>
    </div>
</body>
</html>