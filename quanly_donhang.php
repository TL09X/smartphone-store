<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['currentUser'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Xử lý Xóa đơn hàng
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    if ($stmt->execute([$_GET['id']])) {
        $message = "Đã xóa đơn hàng thành công!";
    }
}

// Lấy danh sách đơn hàng
$stmt = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <link href="sell.css" rel="stylesheet">
    <style>
        .container-box { max-width: 1200px; margin: 30px auto; padding: 0 15px; }
        .table-custom { width: 100%; border-collapse: collapse; background: #2b2b2b; border-radius: 12px; overflow: hidden; margin-top: 15px; }
        .table-custom th, .table-custom td { padding: 12px; text-align: left; border-bottom: 1px solid #444; font-size: 0.85rem; }
        .table-custom th { background: #1f1f1f; color: #E53935; }
        .btn-act { padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 0.75rem; color: #fff; margin-right: 3px; }
        .btn-edit { background: #2196F3; }
        .btn-del { background: #E53935; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="quanly.php">Quản lý chung</a>
        <a href="sell.php">Trang chủ</a>
        <a href="login.php?logout=true">Đăng xuất</a>
    </nav>

    <div class="container-box">
        <h2>Quản Lý Đơn Hàng Khách Hàng</h2>
        <?php if(!empty($message)): ?>
            <p style="text-align:center; color:#4CAF50; font-weight:bold; margin: 10px 0;"><?php echo $message; ?></p>
        <?php endif; ?>

        <div style="overflow-x:auto;">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>SĐT & Địa chỉ</th>
                        <th>Sản phẩm (SL)</th>
                        <th>Tổng tiền (Sau ưu đãi)</th>
                        <th>Thanh toán / Trả góp</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($orders) > 0): foreach($orders as $o): ?>
                    <tr>
                        <td><?php echo $o['id']; ?></td>
                        <td><b><?php echo htmlspecialchars($o['customer_name']); ?></b></td>
                        <td>
                            <?php echo htmlspecialchars($o['phone']); ?><br>
                            <small style="color:#aaa;"><?php echo htmlspecialchars($o['address']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($o['product_name']); ?> (<b><?php echo $o['quantity']; ?></b>)</td>
                        <td style="color:#E53935; font-weight:bold;"><?php echo number_format($o['final_price'], 0, ',', '.'); ?>₫</td>
                        <td>
                            <small><b>TT:</b> <?php echo htmlspecialchars($o['payment_method']); ?></small><br>
                            <small style="color:#ff9800;"><b>Hình thức:</b> <?php echo htmlspecialchars($o['installment_info']); ?></small>
                        </td>
                        <td><span style="background:#444; padding:3px 8px; border-radius:4px; font-size:0.75rem;"><?php echo htmlspecialchars($o['status']); ?></span></td>
                        <td>
                            <a href="sua_donhang.php?id=<?php echo $o['id']; ?>" class="btn-act btn-edit">Sửa</a>
                            <a href="quanly_donhang.php?action=delete&id=<?php echo $o['id']; ?>" class="btn-act btn-del" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này không?');">Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center; color:#aaa; padding:20px;">Chưa có đơn hàng nào.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>